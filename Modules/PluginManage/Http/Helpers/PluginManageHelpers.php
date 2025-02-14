<?php

namespace Modules\PluginManage\Http\Helpers;

use App\Helpers\ModuleMetaData;

class PluginManageHelpers
{
    //todo:: with with multiple plugin meta example, plugin list etc

    public static function getPluginInfo($pluginDirName){
        return (new PluginJsonFileHelper($pluginDirName));
    }

    public static function getPluginLists()
    {
        $allDirectories = glob(base_path() . '/Modules/*', GLOB_ONLYDIR);

        $pluginList = [];
        foreach ($allDirectories as $dir){
            $currFolderName = pathinfo($dir, PATHINFO_BASENAME);

            $pluginInfo = (new PluginJsonFileHelper($currFolderName))->metaInfo();

            $pluginList[] = $pluginInfo;
        };
        return $pluginList;
    }
}
