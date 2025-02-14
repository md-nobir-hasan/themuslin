<?php

namespace App\Http\Controllers;

use App\AdminShopManage;
use App\Http\Requests\Admin\ShopManage\ShopManageUpdateRequest;
use Illuminate\Http\Request;
use Modules\CountryManage\Entities\Country;
use Modules\Vendor\Entities\BusinessType;

class AdminShopManageController extends Controller
{
    public function index(){
        $data = [
            "country" => Country::select("id","name")->orderBy("name","ASC")->get(),
            "business_type" => BusinessType::select()->get(),
            "shopManage" => AdminShopManage::with("logo","cover_photo")->find(1)
        ];

        return view("backend.shop-manage.update", $data);
    }

    public function update(ShopManageUpdateRequest $request){
        $data = $request->validated();
        $update = AdminShopManage::updateOrCreate(["id" => 1],$data);

        return back()->with([
            "type" => $update ? "success" : "danger",
            "msg" => $update ? __("Shop Manage Updated successfully") : __("Something is going wrong.")
        ]);
    }

    public function invoiceNote(){
        return view("backend.shop-manage.invoice-note");
    }

    public function saveInvoiceNote(Request $request){
        update_static_option("admin_invoice_note",str_replace("\n","", $request->invoice_note));

        return back()->with([
            "type" => "success",
            "msg" => __("Successfully invoice note")
        ]);
    }
}
