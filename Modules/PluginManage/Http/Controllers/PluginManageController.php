<?php

namespace Modules\PluginManage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\PluginManage\Http\Helpers\PluginManageHelpers;

class PluginManageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        //todo write a call to access all json content and update it
        //todo return list of plugins devided by core and external
        $pluginList = PluginManageHelpers::getPluginLists();

        return view('pluginmanage::plugin-manage.index',compact("pluginList"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function add_new()
    {
        return view('pluginmanage::plugin-manage.add_new');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store_plugin(Request $request)
    {
        $request->validate([
            "plugin_file" => "required|file|mimes:zip|max:200000"
        ]);

        //todo work for upload plugin
        //todo validate the plugin file is valid or not.. it has contain nazmart meta data or not
        if ($this->replacePluginFile($request->file("plugin_file"))){
            $file_name = pathinfo($request->file("plugin_file")->getClientOriginalName(),PATHINFO_FILENAME);
            return redirect()->to(route("admin.plugin.manage.all"))->with(["msg" => $file_name." ".__("upload success, now you can activate the plugin from here"),"type" => "success"]);
        }

        return back()->with(["msg" => __("the file you have uploaded it not a valid plugin.."),"type" => "danger"]);
        //todo if plugin file uploaded show option to active that plugin.. or redirect theme to all plugins page

    }

    private  function  replacePluginFile($file){
        $plugin_name = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);

        $uploaded_plugin_path = Storage::putFile('/plugins-file', $file);

        $getLatestUpdateFile = storage_path('app/'.$uploaded_plugin_path);
        $zipArchive = new \ZipArchive();
        $zipArchive->open($getLatestUpdateFile);

        $updatedFileLocation = "plugins-file/".$plugin_name;

        $zipExtracted = $zipArchive->extractTo(storage_path('app/plugins-file'));

        if ($zipExtracted) {
            $zipArchive->close();
            //delete zip after extracted
            @unlink(storage_path('app/'.$uploaded_plugin_path));
            //todo move full folder into module_path folder
            $updateFiles = Storage::allFiles($updatedFileLocation);
            if (!in_array("plugins-file/".$plugin_name."/module.json",$updateFiles)){
                return false;
            }

            //todo get modules.json file content
            $plugin_info = json_decode(file_get_contents(storage_path("app/plugins-file/".$plugin_name."/module.json")));

            if (!property_exists($plugin_info,"xgMetaData")){
                return false;
            }


            if ($plugin_info->alias === 'pos') {
                $this->move_pos($plugin_info, $updatedFileLocation);
            }

            foreach ($updateFiles as $updateFile) {
                $folderName = pathinfo($updateFile, PATHINFO_DIRNAME);
                $fileName = pathinfo($updateFile, PATHINFO_FILENAME);
                if (str_contains($folderName, '.vscode') || str_contains($folderName, '.idea') || str_contains($folderName, '.fleet') || str_contains($folderName, '.git')) {
                    continue;
                }

                if (str_contains($folderName, 'build') || str_contains($folderName, 'vue')) {
                    continue;
                }

                $file = new HttpFile(storage_path("app/" . $updateFile));
                $skipFiles = ['.DS_Store','.gitkeep'];

                if (!in_array($fileName, $skipFiles)) {
                    $file->move(storage_path('../Modules/' . str_replace("plugins-file/", "", $folderName)));
                }
            }

        }

        Storage::deleteDirectory($updatedFileLocation);

        return true;
    }

    private function move_pos($plugin_info, $updatedFileLocation): void
    {
        if ($plugin_info->alias !== 'pos') {
            return;
        }

        $folders = ['build', 'vue'];

        foreach ($folders as $folder) {
            $updateFiles = Storage::drive('local')->allFiles($updatedFileLocation . '/' . $folder);

            foreach ($updateFiles as $updateFile) {
                $folderName = pathinfo($updateFile, PATHINFO_DIRNAME);
                $fileName = pathinfo($updateFile, PATHINFO_FILENAME);

                if (str_contains($folderName, '.vscode') || str_contains($folderName, '.idea') || str_contains($folderName, '.fleet') || str_contains($folderName, '.git')) {
                    continue;
                }
                $file = new HttpFile(storage_path("app/" . $updateFile));
                $skipFiles = ['.DS_Store', '.gitkeep'];

                if (!in_array($fileName, $skipFiles)) {
                    if ($folder === 'build') {
                        $file->move(storage_path('../../' . str_replace("plugins-file/", "", str_replace('/Pos', '', $folderName))));
                    }

                    if ($folder === 'vue') {
                        $file->move(storage_path('../resources/js/' . str_replace("plugins-file/", "", str_replace('/Pos', '', $folderName))));
                    }
                }
            }

            Storage::drive('local')->deleteDirectory($updatedFileLocation . '/' . $folder);
        }
    }

    public function delete_plugin(Request $request)
    {
        $request->validate([
           "plugin" => "required|string"
        ]);

        $this->changeModuleStatus($request->plugin, false);

        //todo:: remove this name from modules_status.json file
        File::deleteDirectory(module_path(implode("",explode(" ",$request->plugin))));
        return response()->json("ok");
    }

    public function change_status(Request $request)
    {
        $request->validate([
           "plugin" => "required|string",
           "status" => "required|string",
        ]);

        $this->changeModuleStatus($request->plugin, ((int) $request->status) === 0);

        return response()->json("ok");
    }

    private function changeModuleStatus($plugin, $status): void
    {
        $jsonFile = file_get_contents(base_path('modules_statuses.json'));
        $jsonObj = json_decode($jsonFile);
        $plugin = implode("",explode(" ",$plugin));

        $jsonObj->$plugin = $status;

        file_put_contents(base_path('modules_statuses.json'), json_encode($jsonObj));
    }
}
