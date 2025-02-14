<?php

namespace Modules\PluginManage\Http\Helpers;

use Illuminate\Support\Facades\Cache;

class PluginJsonFileHelper
{
    //todo only work with single json file
    protected array|string|object $fileContents = "";
    protected string $pluginDirName;
    protected object $moduleList;

    public function __construct($pluginDirName)
    {
        $this->pluginDirName = $pluginDirName;
        $this->setModuleLists();
        if ($this->checkModuleFileExists()){
            $this->setFileContent();
        }
    }

    public function metaInfo(): \stdClass
    {
        $data = $this->getFileContent();

        $metaObject  = new \stdClass;
        $metaObject->name = !empty($data) ? $this->deliciousCamelcase($data->name) : "";
        $metaObject->alias = !empty($data) ? $data->alias : "";
        $metaObject->description = !empty($data) ? $data->description : "";
        $metaObject->version = !empty($data) ? property_exists($data,"version") ? $data->version : "1.0.0" : "";
        $metaObject->category = !empty($data) ? property_exists($data,"xgMetaData") ? __("External Plugin") : __("Core Plugin") : "";

        $metaObject->external = false;
        if (property_exists($data,"xgMetaData") && !empty($data->xgMetaData))
        {
            if (property_exists($data->xgMetaData,"plugin_type") && !empty($data->xgMetaData->plugin_type))
                $metaObject->external = (bool)$data->xgMetaData->plugin_type;
        }

        $metaObject->status = $this->isPluginActive(); //check the status using a private method;
        return $metaObject;
    }

    public function isPluginActive(): bool
    {
        $moduleList = json_decode(file_get_contents(base_path("modules_statuses.json")),true);
        return array_key_exists($this->pluginDirName,$moduleList) && $moduleList["$this->pluginDirName"];
    }
    public function deliciousCamelcase($str): string
    {
        $formattedStr = '';
        $re = '/
              (?<=[a-z])
              (?=[A-Z])
            | (?<=[A-Z])
              (?=[A-Z][a-z])
            /x';
        $a = preg_split($re, $str);
        $formattedStr = implode(' ', $a);
        return $formattedStr;
    }
    public function overrideData(array $data): static
    {
        $existingData  = $this->fileContents;
        foreach($data as $col => $value){
            if (property_exists($existingData,$col)){
                $existingData->$col = $value;
            }
        }

        $this->fileContents = $existingData;

        return $this;
    }
    public function saveFile(): void
    {
        file_put_contents($this->getModuleMetaFilePath(),$this->fileContents);
    }
    private function getJsonData(){

    }
    private function decodeData(){
        return json_decode($this->fileContents);
    }
    private function encodeData(): bool|string
    {
        return json_encode($this->fileContents);
    }
    private function pluginName(){
        return $this->pluginDirName;
    }
    private function setFileContent(): void
    {
        $this->fileContents = json_decode(file_get_contents($this->getModuleMetaFilePath()));
    }

    private function getFileContent(): object|array|string
    {
        return $this->fileContents;
    }

    private function getModuleMetaFilePath(): string
    {
        if (file_exists( base_path('Modules/'.$this->pluginDirName)) && is_dir( base_path('Modules/'.$this->pluginDirName))){
            return  base_path('Modules/'.$this->pluginDirName)."/module.json";
        }
    }

    private function checkModuleFileExists(): bool
    {
        return file_exists($this->getModuleMetaFilePath()) && !is_dir($this->getModuleMetaFilePath());
    }
    public function saveModuleListFile(): void
    {
        file_put_contents(base_path("modules_statuses.json"),json_encode($this->moduleList));
    }
    private function setModuleLists(): static
    {
        $this->moduleList = Cache::remember("allModuleStatus",3600,function (){
            return json_decode(file_get_contents(base_path("modules_statuses.json")));
        });

        return $this;
    }
    public function changePluginStatus($status): static
    {
        Cache::forget("allModuleStatus");
        $pluginName = $this->pluginDirName;
        $moduleList = $this->moduleList;

        if (property_exists($moduleList,$pluginName)){
            $this->moduleList->$pluginName = $status;
        }

        return $this;
    }
    public function removePlugin(): static
    {
        Cache::forever("allModuleStatus");
        $pluginName = $this->pluginDirName;
        $moduleList = $this->moduleList;
        if (property_exists($moduleList,$pluginName)){
            unset($this->moduleList->$pluginName);
        }

        return $this;
    }
}
