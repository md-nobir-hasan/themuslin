<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\MobileApp\Entities\MobileIntro;
use Modules\MobileApp\Http\Resources\MobileIntroResource;

class MobileIntroApiController extends Controller
{
    public function mobileIntro(){
        return MobileIntroResource::collection(MobileIntro::where("type","admin")->get());
    }

    public function vendorMobileIntro(){
        return MobileIntroResource::collection(MobileIntro::where("type","vendor")->get());
    }
}