<?php

namespace Modules\Vendor\Http\Controllers;

use App\City;
use App\StaticOption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Vendor\Entities\BusinessType;
use Modules\Vendor\Entities\Vendor;
use Modules\Vendor\Http\Requests\Backend\UpdateVendorRequest;
use Modules\Vendor\Http\Requests\Backend\VendorStoreRequest;
use Modules\Vendor\Http\Services\VendorServices;
use Modules\Wallet\Http\Services\WalletService;

class VendorBackendController extends Controller
{
    public function index(): Factory|View|Application
    {
        $vendors = Vendor::with(["vendor_address","vendor_shop_info","business_type:id,name"])
            ->latest()->paginate(20);

        return view("vendor::backend.index",compact("vendors"));
    }

    public function show(Request $request): string
    {
        $id = $request->validate(["id" => "required"]);
        $vendor = Vendor::with(["vendor_address","vendor_shop_info","business_type","vendor_bank_info"])
            ->where($id)->first();

        return view("vendor::backend.details",compact("vendor"))->render();
    }

    public function update_status(Request $request): JsonResponse
    {
        $data = $request->validate(["status_id" => "required","vendor_id" => "required"]);
        Vendor::where("id",$data["vendor_id"])->update(["status_id" => $data["status_id"]]);

        return response()->json(["success" => true,"type" => "success"]);
    }

    public function create(): Factory | View | Application
    {
        $data = [
            "country" => Country::select("id","name")->orderBy("name","ASC")->get(),
            "business_type" => BusinessType::select()->get()
        ];

        return view("vendor::backend.create",with($data));
    }

    public function store(VendorStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data["password"] = \Hash::make($data["password"]);

        \DB::beginTransaction();

        try {
            // store vendor
            $vendor = VendorServices::store_vendor($data + ["status_id" => 1]);
            // create wallet for this vendor if wallet module is exists
            if(moduleExists("Wallet")){
                WalletService::createWallet($vendor->id, "vendor");
            }

            // get vendor id and create an array for next insert
            $vendor_id = ["vendor_id" => $vendor->id];
            // store vendor address
            VendorServices::store_vendor_address($data + $vendor_id);
            // store Shop Info
            $data['email'] = $data['shop_email'];
            VendorServices::store_vendor_shop_info($data + $vendor_id);
            // store vendor bank
            VendorServices::store_vendor_bank_info($data + $vendor_id);
            // Database Commit
            \DB::commit();

            return response()->json(["success" => true,"type" => "success"]);
        }catch(\Exception $e){
            \DB::rollBack();
            return response()->json(["msg" => $e,"custom_msg" => "Failed to create vendor account..","success" => false,"type" => "danger"])->setStatusCode(422);
        }

    }

    public function edit($vendor): Factory|View|Application
    {
        $data = [
            "country" => Country::select("id","name")->orderBy("name","ASC")->get(),
            "business_type" => BusinessType::select()->get(),
            "vendor" => Vendor::with([
                "vendor_address",
                "vendor_shop_info",
                "business_type",
                "vendor_bank_info",
                "vendor_shop_info.cover_photo",
                "vendor_shop_info.logo"
            ])->findOrFail($vendor)
        ];

        return view("vendor::backend.edit",with($data));
    }

    public function update($vendor,UpdateVendorRequest $request): JsonResponse
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

    public function get_state(Request $request): JsonResponse
    {
        $id = $request->validate(["country_id" => "required"]);
        $states = State::where("country_id",$id)->get();

        return response()->json(["success" => true,"type" => "success"] + render_view_for_nice_select($states));
    }

    public function get_city(Request $request): JsonResponse
    {
        $id = $request->validate(["country_id" => "required","state_id" => "required"]);
        $states = City::where($id)->get();

        return response()->json(["success" => true,"type" => "success"] + render_view_for_nice_select($states));
    }

    public function destroy(Vendor $vendor): ?bool
    {
        return $vendor->delete();
    }

    public function settings(){
        // add vendor registration settings is active or not
        // render a view file from hare

        return view("vendor::backend.settings");
    }

    public function updateSettings(Request $req){
        // update all vendor settings in hare
        $reqSettings = $req->validate([
            "enable_vendor_registration" => "nullable",
            "disable_vendor_email_verify" => "nullable",
            "order_vendor_list" => "nullable",
            "vendor_firebase_server_key" => "nullable"
        ]);

        update_static_option("enable_vendor_registration", $reqSettings["enable_vendor_registration"] ?? 'off');
        update_static_option("disable_vendor_email_verify", $reqSettings["disable_vendor_email_verify"] ?? null);
        update_static_option("order_vendor_list", $reqSettings["order_vendor_list"] ?? null);
        update_static_option("vendor_firebase_server_key", $reqSettings["vendor_firebase_server_key"] ?? null);

        return back()->with([
            "msg" => __("Vendor settings updated"),
            "type" => "success"
        ]);
    }

    public function commissionSettings(){
        $vendor = Vendor::select(["id","owner_name","username"])->get();

        return view("vendor::backend.commission-settings", compact("vendor"));
    }
    public function updateCommissionSettings(Request $request){
        // step one is validation
        $data = $request->validate([
            "system_type" => "required",
            "commission_type" => "nullable",
            "commission_amount" => "nullable"
        ]);
        // step two is to saving data on database
        update_static_option("system_type", $data["system_type"]);
        update_static_option("commission_type", $data["commission_type"]);
        update_static_option("commission_amount", $data["commission_amount"]);

        //todo:: step three is send response with message
        return response()->json([
            "msg" => __("Global vendor commission settings updated."),
            "success" => true,"type" => "success"
        ]);
    }
    public function updateIndividualCommissionSettings(Request $request){
        // step one is need to validate vendor commission data
        $data = $request->validate([
            "vendor_id" => "required|exists:vendors,id",
            "commission_type" => "required|string",
            "commission_amount" => "required"
        ]);

        //todo:: step two is now update vendor information
        $query = Vendor::where("id", $data["vendor_id"])->update([
            "commission_type" => $data["commission_type"],
            "commission_amount" => $data["commission_amount"],
        ]);

        return response()->json([
            "msg" => $query ? __("Successfully updated individual vendor commission") : __("Failed to update vendor commission data"),
            "success" => (bool) $query,
        ]);
    }

    public function getVendorCommissionInformation($id){
        // this method will send vendor commission type and vendor commission amount
        return Vendor::select("commission_type", "commission_amount")
            ->without('status')->where("id", $id)->first();
    }
}