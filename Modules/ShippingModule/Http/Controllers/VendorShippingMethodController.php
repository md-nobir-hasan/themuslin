<?php

namespace Modules\ShippingModule\Http\Controllers;

use App\Enums\ShippingEnum;
use App\Status;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\ProductCoupon;
use Modules\ShippingModule\Entities\ShippingMethod;
use Modules\ShippingModule\Entities\VendorShippingMethod;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Http\Requests\StoreShippingMethodRequest;

class VendorShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $vendor_id = auth("vendor")->id();

        $data = [
            "all_shipping_methods" => VendorShippingMethod::with("zone","vendor","status")->where("vendor_id", $vendor_id)->get(),
        ];

        return view('shippingmodule::vendor.index',$data);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function create()
    {
        $all_zones = Zone::all();
        $all_publish_status = Status::all()->pluck("name","id")->toArray();

        return view('shippingmodule::vendor.create', compact(
            'all_zones',
            'all_publish_status',
        ));
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function edit($id)
    {
        $all_zones = Zone::all();
        $all_publish_status = Status::all()->pluck("name","id")->toArray();
        $method = VendorShippingMethod::where("vendor_id", auth("vendor")->id())->where("id", $id)->first();

        return view('shippingmodule::vendor.edit', compact(
    'all_zones',
    'all_publish_status',
    'method',
        ));
    }
    /**
     * Display a listing of the resource.
     * @return RedirectResponse
     */
    public function store(StoreShippingMethodRequest $request)
    {
        $query = VendorShippingMethod::create($request->validated());

        return redirect(route("vendor.shipping-method.index"))
            ->with([
                "msg" => $query ? "Successfully created shipping method" : "Failed to create shipping method",
                "type" => $query ? "success" : "danger"
            ]);
    }

    /**
     * Display a listing of the resource.
     * @return RedirectResponse
     */
    public function update(StoreShippingMethodRequest $request, $id)
    {
        $query = VendorShippingMethod::where("vendor_id", auth("vendor")->id())->where("id", $id)->update($request->validated());

        return redirect(route("vendor.shipping-method.index"))->with([
            "msg" => $query ? "Successfully created shipping method" : "Failed to create shipping method",
            "type" => $query ? "success" : "danger"
        ]);
    }

    public function makeDefault(){
        // First I need to check requested id is valid or not if valid then update all methods as not default after that update requested id row with value 1
        $vendor = VendorShippingMethod::where("vendor_id", auth("vendor")->id())->where("id",request()->id)->first();
        if(!empty($vendor)){
            VendorShippingMethod::where("vendor_id", auth("vendor")->id())->update([
                "is_default" => 0
            ]);
            $vendor->update([
                "is_default" => 1
            ]);

            return back()->with(["msg" => "Updated successfully"]);
        }

        return back()->with(["msg" => "Failed to update","type" => "success"]);
    }

    public function destroy($id){
        // delete method
        $delete = VendorShippingMethod::where("vendor_id", auth("vendor")->id())->where("id",$id)->delete();

        return back()->with([
            "msg" => $delete ? "Successfully deleted shipping method" : "Failed to delete shipping method",
            "type" => $delete ? "success" : "danger"
        ]);
    }
}
