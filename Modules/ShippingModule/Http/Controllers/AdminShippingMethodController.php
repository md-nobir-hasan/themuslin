<?php

namespace Modules\ShippingModule\Http\Controllers;

use App\InternationalShipping;
use App\Status;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\ShippingModule\Entities\AdminShippingMethod;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Http\Requests\StoreShippingMethodRequest;

class AdminShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = [
            "all_shipping_methods" => AdminShippingMethod::with("zone","status")->get(),
            "all_international_shippings" => InternationalShipping::all(),
        ];

        return view('shippingmodule::admin.shipping-method.index',$data);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function create()
    {
        $all_zones = Zone::all();
        $all_publish_status = Status::all()->pluck("name","id")->toArray();

        return view('shippingmodule::admin.shipping-method.create', compact(
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
        $method = AdminShippingMethod::where("id", $id)->first();

        return view('shippingmodule::admin.shipping-method.edit', compact(
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
        $query = AdminShippingMethod::create($request->validated());

        return redirect(route("admin.shipping-method.index"))->with([
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
        $query = AdminShippingMethod::where("id", $id)->update($request->validated());

        return redirect(route("admin.shipping-method.index"))->with([
            "msg" => $query ? "Successfully created shipping method" : "Failed to create shipping method",
            "type" => $query ? "success" : "danger"
        ]);
    }

    public function makeDefault(){
        // First I need to check requested id is valid or not if valid then update all methods as not default after that update requested id row with value 1
        $vendor = AdminShippingMethod::where("id",request()->id)->first();
        if(!empty($vendor)){
            AdminShippingMethod::where("id","!=",request()->id)->update([
                "is_default" => 0
            ]);

            $vendor->update([
                "is_default" => 1
            ]);

            return back()->with(["msg" => "Updated successfully", "type" => "success"]);
        }

        return back()->with(["msg" => "Failed to update"]);
    }

    public function destroy($id){
        // delete method
        $delete = AdminShippingMethod::where("id",$id)->delete();

        return back()->with([
            "msg" => $delete ? "Successfully deleted shipping method" : "Failed to delete shipping method",
            "type" => $delete ? "success" : "danger"
        ]);
    }
}
