<?php
namespace App\traits;

 use Config;

 trait URL_PARSE{
     public static function url($url){
         return str_replace("[url/]",Config::get('app.url'),$url);
     }
 }