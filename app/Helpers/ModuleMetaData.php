<?php

namespace App\Helpers;

class ModuleMetaData
{
    public function __construct(public ?string $moduleName = null)
    {

    }

    public function paymentGatewayData()
    {
        $allMetaData = $this->getMetaData();
        if (property_exists($allMetaData, 'xgMetaData')) {
            //todo: check payment meta is available or not
            $metaInstance = $allMetaData->xgMetaData;
            return $this->getPaymentMetaInfo($metaInstance);
        }

        return null;
    }

    private function getMetaData()
    {
        if (moduleExists($this->moduleName)) {
            return $this->getIndividualModuleMetaData($this->moduleName);
        }

        return null;
    }

    public function renderAllPaymentGatewaySettingsBlade()
    {
        //todo return blade partials to render it in
        $outputMarkup = '';
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            foreach ($paymentMeta as $inPay) {
                if (property_exists($inPay, 'settingsBlade')) {
                    if (view()->exists($inPay->settingsBlade)) {
                        $outputMarkup .= view($inPay->settingsBlade)->render();
                    }
                }
            }
        }

        return $outputMarkup;
    }

    public function getChargeCustomerMethodNameByPaymentGatewayNameSpace($gateway)
    {
        //todo return blade partials to render it in
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if (empty($paymentMeta)) {
                continue;
            }
            if ($gateway !== strtolower(current($paymentMeta)->slug)) {
                continue;
            }
            return current($paymentMeta)->chargeCustomerMethodNameSpace;
        }

        return '';
    }

    public function getChargeCustomerMethodNameByPaymentGatewayName($gateway)
    {
        //todo return blade partials to render it in
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if (empty($paymentMeta)) {
                continue;
            }
            if ($gateway !== strtolower(current($paymentMeta)->slug)) {
                continue;
            }
            return current($paymentMeta)->chargeCustomerMethodName;
        }

        return '';
    }

    public function renderAllPaymentGatewayExtraInfoBlade()
    {
        //todo return blade partials to render it in
        $outputMarkup = '';
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $index => $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if (!empty($paymentMeta)) {
                foreach ($paymentMeta as $inPay) {
                    $view_file = get_module_view($index, $inPay->extraInfoMarkupBlade);
                    if (property_exists($inPay, 'extraInfoMarkupBlade')) {
                        if (view()->exists($view_file)) {
                            $outputMarkup .= '<div id="' . $inPay->slug . '-parent-wrapper">' . view($view_file)->render() . '</div>';
                        }
                    }
                }
            }
        }

        return $outputMarkup;
    }

    public function saveAllPaymentGatewaySettings()
    {
        $outputMarkup = [];
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            foreach ($paymentMeta as $inPay) {
                if (property_exists($inPay, 'settingsData')) {
                    $outputMarkup[] = $inPay->settingsData;
                }
            }
        }

        return $outputMarkup;
    }

    public function getAllPaymentGatewayList()
    {
        $outputMarkup = [];
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if (!empty($paymentMeta)) {
                foreach ($paymentMeta as $meta) {
                    if (property_exists($meta, 'slug')) {
                        $outputMarkup[] = $meta->slug;
                    }
                }
            }
        }

        return $outputMarkup;
    }

    public function getAllPaymentGatewayListWithImage()
    {
        $eachIndex = 0;
        $outputMarkup = [];
        $allMetaInformation = $this->getAllMetaData();
        foreach ($allMetaInformation as $index => $metaInfo) {
            $paymentMeta = $this->getPaymentMetaInfo($metaInfo);
            if (!empty($paymentMeta)) {
                foreach ($paymentMeta as $key => $meta) {
                    if (tenant() && property_exists($meta, 'tenant') && !$meta->tenant)
                    {
                        continue;
                    }

                    if (property_exists($meta, 'slug') && $meta?->status) {
                        $outputMarkup[$eachIndex]['name'] = $meta->slug;
                        $outputMarkup[$eachIndex]['image'] = $this->getPaymentGatewayImagePath($meta->slug);
                        $outputMarkup[$eachIndex]['module'] = $index;
                    }
                    $eachIndex++;
                }
            }
        }

        return $outputMarkup;
    }

    private function getPaymentMetaInfo($metaInstance)
    {
        $paymentGateway = [];
        if (property_exists($metaInstance, 'paymentGateway')) {
            $paymentGateway[] = $metaInstance->paymentGateway;
        }

        return $paymentGateway;
    }

    public function getAllMetaData()
    {
        $allModuleMeta = [];
        $allDirectories = glob(base_path() . '/Modules/*', GLOB_ONLYDIR);

        $modules_status_data = [];
        if (file_exists(base_path() . "/modules_statuses.json") && !is_dir(base_path() . "/modules_statuses.json")) {
            $modules_status_data = json_decode(file_get_contents(base_path() . "/modules_statuses.json"), true);
        }

        foreach ($allDirectories as $dire) {
            //todo scan all the json file
            $currFolderName = pathinfo($dire, PATHINFO_BASENAME);
            try {
                $metaInformation = $this->getIndividualModuleMetaData($currFolderName);
            }catch ()

            //did not collect  meta info of the module which is disabled from module_status.json file
            if (!array_key_exists($currFolderName, $modules_status_data)) {
                continue;
            }

            if (
                array_key_exists($metaInformation->name, $modules_status_data)
                && isset($modules_status_data[$metaInformation->name])
                && $modules_status_data[$metaInformation->name] === false
            ) {
                continue;
            }

            if (property_exists($metaInformation, 'xgMetaData')) {
                $allModuleMeta[$currFolderName] = $metaInformation->xgMetaData;
            }
        }

        return $allModuleMeta;
    }

    private function getIndividualModuleMetaData(string $moduleName, bool $returnType = false)
    {
        $filePath = module_path($moduleName) . '/module.json';

        if (file_exists($filePath) && !is_dir($filePath)) {
            return json_decode(file_get_contents($filePath), $returnType);
        }
    }

    public function getIndividualModulePath(string $moduleName)
    {
        $name = '';
        $moduleMeta = $this->getIndividualModuleMetaData($moduleName);
        if (!empty($moduleMeta) && property_exists($moduleMeta, 'name')) {
            $name = $moduleMeta->name;
        }
        return $name;
    }

    public function getPageBuilderAddonList()
    {
        $addonNames = [];
        $allModuleMeta = $this->getAllMetaData();
        if (!empty($allModuleMeta)) {
            $addonNames = $this->getOnlyPageBuilder($allModuleMeta);
        }

        return $addonNames;
    }

    public function getLandlordPageBuilderAddonList(): array
    {
        $addonNames = [];
        $addonList = $this->getPageBuilderAddonList();
        if (!empty($addonList)) {
            foreach ($addonList as $item) {
                if (!$item['landlord']) {
                    continue;
                }

                $addonNames[] = $item['addon'];
            }
        }

        return $addonNames;
    }

    public function getTenantPageBuilderAddonList(): array
    {
        $addonNames = [];
        $addonList = $this->getPageBuilderAddonList();
        if (!empty($addonList)) {
            foreach ($addonList as $item) {
                if (!$item['tenant']) {
                    continue;
                }

                $addonNames[] = $item['addon'];
            }
        }

        return $addonNames;
    }

    private function getOnlyPageBuilder($allModuleMeta)
    {
        $classList = [];
        foreach ($allModuleMeta as $eachModuleMeta) {
            if (property_exists($eachModuleMeta, 'pageBuilderAddon')) {
                $pageBuilderAddon = $eachModuleMeta->pageBuilderAddon;
                if (!empty($pageBuilderAddon)) {
                    foreach ($pageBuilderAddon as $key => $item) {
                        if (!empty($item)) {
                            if (!property_exists($item, 'addon')) {
                                continue;
                            }

                            $classList[$key] = [
                                "addon" => $this->addonPath($item->addon),
                                "landlord" => isset($item->landlord) && is_bool($item->landlord) ? $item->landlord : false,
                                "tenant" => isset($item->tenant) && is_bool($item->tenant) ? $item->tenant : false,
                            ];
                        }
                    }
                }
            }
        }

        return $classList;
    }

    public function getWidgetBuilderAddonList()
    {
        $addonNames = [];
        $allModuleMeta = $this->getAllMetaData();

        if (!empty($allModuleMeta)) {
            $addonNames = $this->getOnlyWidgetBuilder($allModuleMeta);
        }

        return $addonNames;
    }

    private function getOnlyWidgetBuilder($allModuleMeta)
    {
        $classList = [];
        foreach ($allModuleMeta as $eachModuleMeta) {
            if (property_exists($eachModuleMeta, 'widgetBuilderAddon')) {
                $widgetBuilderAddon = $eachModuleMeta->widgetBuilderAddon;
                if (!empty($widgetBuilderAddon)) {
                    $classList = $this->addonPath($widgetBuilderAddon);
                }
            }
        }

        return $classList;
    }

    private function addonPath($widgetBuilderAddon)
    {
        $addonNames = "";
//        foreach ($widgetBuilderAddon as $addon) {
        if (file_exists(str_replace('\\', '/', base_path($widgetBuilderAddon)) . '.php')) {
            $addonNames = $widgetBuilderAddon;
        }
//        }

        return $addonNames;
    }

    public function getAllExternalPaymentGatewayMenu()
    {
        $allExternalPaymentGateway = $this->getExternalPaymentGateway();
        return $this->getEachPaymentMenu($allExternalPaymentGateway);
    }

    private function getEachPaymentMenu($allModuleMeta)
    {
        $menuList = [];
        if (!empty($allModuleMeta)) {
            foreach ($allModuleMeta ?? [] as $metaData) {
                $adminSettings = $this->getAdminSettings($metaData);
                $adminSettings = is_array($adminSettings) ? (object)$adminSettings : $adminSettings;

                if (tenant() && property_exists($metaData, "show_admin_tenant") && $metaData->show_admin_tenant === false) {
                    continue;
                }
                if (!tenant() && property_exists($metaData, "show_admin_landlord") && $metaData->show_admin_landlord === false) {
                    continue;
                }
                $menuItem = $this->getAdminMenuSettings($adminSettings);
                if (!empty((array)$menuItem)) {
                    //if it is tenant then load route param as tenant route param
                    if (tenant() && property_exists(current($menuItem), 'tenantRoute')) {
                        current($menuItem)->route = current($menuItem)?->tenantRoute;
                    }

                    $menuList[] = $menuItem;
                }
            }
        }

        return $menuList;
    }

    public function getAllExternalMenu(): array
    {
        $allModuleMeta = $this->getAllMetaData();

        return $this->getEachMenu($allModuleMeta);
    }

    private function getEachMenu($allModuleMeta)
    {
        $menuList = [];
        if (!empty($allModuleMeta)) {
            foreach ($allModuleMeta ?? [] as $metaData) {
                $adminSettings = $this->getAdminSettings($metaData);
                $adminSettings = is_array($adminSettings) ? (object)$adminSettings : $adminSettings;

                $menuItem = $this->getAdminMenuSettings($adminSettings);
                if (!empty((array)$menuItem)) {
                    if (property_exists(current($menuItem), "permissions") && !empty(current($menuItem)->permissions)) {
                        continue;
                    }

                    $menuList[] = $menuItem;
                }
            }
        }

        return $menuList;
    }

    public function getAdminSettings($metaData)
    {
        $adminSettings = [];
        if (property_exists($metaData, 'admin_settings')) {
            $adminSettings = $metaData->admin_settings;
        }

        return $adminSettings;
    }

    public function getAdminMenuSettings($adminSettings)
    {
        $menuItem = [];
        $adminSettings = is_array($adminSettings) ? (object)$adminSettings : $adminSettings;
        if (property_exists($adminSettings, 'menu_item') && !empty($adminSettings->menu_item)) {
            $menuItem = $adminSettings->menu_item;
        }

        return $menuItem;
    }

    public function getExternalPaymentGateway()
    {
        $allModuleMeta = $this->getAllMetaData();
        return $this->getEachPaymentMetaData($allModuleMeta);
    }

    private function getEachPaymentMetaData($allModuleMeta)
    {
        $paymentMeta = [];
        if (!empty($allModuleMeta)) {
            foreach ($allModuleMeta as $metaItem) {
                if (property_exists($metaItem, 'paymentGateway')) {
                    $paymentMeta[] = $metaItem->paymentGateway;
                }
            }
        }

        return $paymentMeta;
    }

    public function getPaymentGatewayImagePath($paymentGatewaySlug)
    {
        $file_name = '';
        $allMetaData = $this->getExternalPaymentGateway();
        if (!empty($allMetaData)) {
            foreach ($allMetaData as $eachMeta) {
                if (property_exists($eachMeta, 'slug')) {
                    if ($eachMeta->slug == $paymentGatewaySlug) {
                        if (property_exists($eachMeta, 'logo_file')) {
                            $file_name = $eachMeta->logo_file;
                        }
                    }
                }
            }
        }

        return $file_name;
    }

    public function renderPaymentGatewayImage($imageName, $moduleName)
    {
        $moduleDir = '';
        if (!empty($imageName) && !empty($moduleName)) {
            $moduleDir = '<img src="' . asset(module_dir($moduleName) . 'assets/payment-gateway-image/' . $imageName) . '"/>';
        }

        return $moduleDir;
    }

    public function getMegaMenu()
    {
        $allModuleMeta = $this->getAllMetaData();

        $menuList = [];
        if (!empty($allModuleMeta)) {
            foreach ($allModuleMeta as $metaData) {
                if (!property_exists($metaData, 'menuBuilder')) {
                    continue;
                }

                $menuBuilder = $this->getMenuBuilder($metaData);
                if (!empty($menuBuilder)) {
                    $eachMenuItems = is_array($menuBuilder) ? (object)$menuBuilder : $menuBuilder;
                    if (!empty($eachMenuItems) && property_exists($eachMenuItems, 'megaMenu')) {
                        $eachMenuItems = $eachMenuItems->megaMenu;
                        foreach ($eachMenuItems ?? [] as $menuItem) {
                            if (tenant() && property_exists($menuItem, "tenant") && $menuItem->tenant === false) {
                                continue;
                            }
                            if (!tenant() && property_exists($menuItem, "landlord") && $menuItem->landlord === false) {
                                continue;
                            }

                            if (property_exists($menuItem, "menu") && !empty($menuItem->menu))
                            {
                                $menuList[] = $menuItem->menu;
                            }
                        }
                    }
                }
            }
        }

        return $menuList;
    }

    public function getMenuBuilder($metaData)
    {
        $menuBuilder = [];
        if (property_exists($metaData, 'menuBuilder')) {
            $menuBuilder = $metaData->menuBuilder;
        }

        return $menuBuilder;
    }

    public function getAllHooks()
    {
        $all_module = $this->getAllMetaData();

        $all_hooks = [];
        foreach ($all_module as $index => $each_module) {
            if (property_exists($each_module, 'hooks') && !empty($each_module->hooks)) {
                $all_hooks[$index] = $each_module->hooks;
            }
        }

        return $all_hooks;
    }

    public function getHeadStartHooks(): array
    {
        $all_hooks = $this->getAllHooks();

        $all_head_start_hooks = [];
        foreach ($all_hooks as $index => $each_hook) {
            if (property_exists($each_hook, 'head_start') && !empty($each_hook->head_start)) {
                foreach ($each_hook->head_start as $hook) {
                    $all_head_start_hooks[$index] = $hook;
                }
            }
        }

        return $all_head_start_hooks;
    }

    public function getHeadEndHooks(): array
    {
        $all_hooks = $this->getAllHooks();

        $all_head_end_hooks = [];
        foreach ($all_hooks as $index => $each_hook) {
            if (property_exists($each_hook, 'head_end') && !empty($each_hook->head_end)) {
                foreach ($each_hook->head_end as $hook) {
                    $all_head_end_hooks[$index] = $hook;
                }
            }
        }

        return $all_head_end_hooks;
    }

    public function getBodyStartHooks(): array
    {
        $all_hooks = $this->getAllHooks();

        $all_body_start_hooks = [];
        foreach ($all_hooks as $index => $each_hook) {
            if (property_exists($each_hook, 'body_start') && !empty($each_hook->body_start)) {
                foreach ($each_hook->body_start as $hook) {
                    $all_body_start_hooks[$index] = $hook;
                }
            }
        }

        return $all_body_start_hooks;
    }

    public function getBodyEndHooks(): array
    {
        $all_hooks = $this->getAllHooks();

        $all_body_end_hooks = [];
        foreach ($all_hooks as $index => $each_hook) {
            if (property_exists($each_hook, 'body_end') && !empty($each_hook->body_end)) {
                foreach ($each_hook->body_end as $hook) {
                    $all_body_end_hooks[$index] = $hook;
                }
            }
        }

        return $all_body_end_hooks;
    }

    public function renderHeadStartHooks(): string
    {
        return $this->renderHooks(__FUNCTION__);
    }

    public function renderHeadEndHooks(): string
    {
        return $this->renderHooks(__FUNCTION__);
    }

    public function renderBodyStartHooks(): string
    {
        return $this->renderHooks(__FUNCTION__);
    }

    public function renderBodyEndHooks(): string
    {
        return $this->renderHooks(__FUNCTION__);
    }

    private function renderHooks($hook_name)
    {
        $all_hooks = match ($hook_name) {
            'renderHeadStartHooks' => $this->getHeadStartHooks(),
            'renderHeadEndHooks' => $this->getHeadEndHooks(),
            'renderBodyStartHooks' => $this->getBodyStartHooks(),
            'renderBodyEndHooks' => $this->getBodyEndHooks(),
            default => []
        };

        $all_blades = '';
        foreach ($all_hooks ?? [] as $index => $hook) {
            $hook = str_replace(['.blade', '.blade.php', '.php'], '', $hook);
            $view_path = strtolower($index) . '::' . $hook;
            if (\View::exists($view_path)) {
                $all_blades .= view($view_path)->render();
            }
        }

        return $all_blades;
    }

    public function getAllExternalPlugins(): array
    {
        $allMetaData = $this->getAllMetaData();

        $allExternalPlugins = [];
        foreach ($allMetaData ?? [] as $key => $metaData)
        {
            if (property_exists($metaData, 'plugin_type') && !empty($metaData->plugin_type))
            {
                if ($metaData->plugin_type == 'external')
                {
                    $allExternalPlugins[$key] = $this->getIndividualModuleMetaData($key);
                }
            }
        }

        return $allExternalPlugins;
    }

    public function getExternalPluginsName(): array
    {
        $externalPlugins = $this->getAllExternalPlugins();

        $pluginInfo = [];
        foreach ($externalPlugins ?? [] as $key => $plugin)
        {
            if (!empty($plugin))
            {
                if (!property_exists($plugin, 'name') || !property_exists($plugin, 'alias'))
                {
                    continue;
                }

                $pluginInfo[$key] = [
                    'name' => $plugin->name,
                    'alias' => $plugin->alias
                ];
            }
        }

        return $pluginInfo;
    }
}
