<?php

namespace Modules\MobileApp\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Modules\MobileApp\Http\Resources\MobileSlider\MobileSliderResource;
use Modules\MobileApp\Entities\MobileSlider;

class MobileSliderController extends Controller
{
    public function index($type){
         return MobileSliderResource::collection(MobileSlider::with("sliderCategory:id,name")->where("type",$type)->paginate(5))->withQueryString();
    }
}
