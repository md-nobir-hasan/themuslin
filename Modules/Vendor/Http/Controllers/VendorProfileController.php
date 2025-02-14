<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Entities\Country;
use Modules\Vendor\Entities\BusinessType;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Http\Requests\Backend\UpdateVendorRequest;
use Modules\Vendor\Http\Services\VendorServices;

class VendorProfileController extends Controller
{
    function vendor_profile()
    {
        // prepare data for updating vendor profile
        $vendor = auth("vendor")->id();

        $data = [
            "country" => Country::select("id","name")->orderBy("name","ASC")->get(),
            "business_type" => BusinessType::select()->get(),
            "vendor" => Vendor::with(["vendor_address","vendor_shop_info","business_type","vendor_bank_info","vendor_shop_info.cover_photo","vendor_shop_info.logo"])->where("id",$vendor)->first()
        ];

        return view("vendor::vendor.edit", $data);
    }

    function vendor_profile_update(UpdateVendorRequest $request)
    {
        $data = $request->validated();

        \DB::beginTransaction();

        try {
            // store vendor
            VendorServices::store_vendor(VendorServices::prepare_data_for_update($data + ["status_id" => 1],"vendor") + ["id" => $data["id"]],"update");
            // store vendor address
            VendorServices::store_vendor_address(VendorServices::prepare_data_for_update($data,"vendor_address"),"update");
            // store Shop Info
            VendorServices::store_vendor_shop_info(VendorServices::prepare_data_for_update($data,"vendor_shop_info"),"update");
            // store vendor bank
            VendorServices::store_vendor_bank_info(VendorServices::prepare_data_for_update($data,"vendor_bank_info"),"update");
            // Database Commit
            \DB::commit();

            return response()->json(["success" => true,"type" => "success"]);
        }catch(\Exception $e){
            \DB::rollBack();
            return response()->json(["msg" => $e,"custom_msg" => "Failed to create vendor account..","success" => false,"type" => "danger"])->setStatusCode(422);
        }
    }

    public function vendor_settings()
    {

    }
}
