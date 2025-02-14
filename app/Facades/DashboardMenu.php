<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class DashboardMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DashboardMenu';
    }
}