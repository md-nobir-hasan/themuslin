<?php

namespace Modules\ShippingModule\Http\Controllers\Api;

use App\Status;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Modules\ShippingModule\Entities\VendorShippingMethod;
use Modules\ShippingModule\Entities\Zone;
use Modules\ShippingModule\Http\Requests\StoreShippingMethodRequest;

class VendorShippingMethodApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        $vendor_id = auth("sanctum")->id();

        return [
            "all_shipping_methods" => VendorShippingMethod::with("zone","vendor","status")->where("vendor_id", $vendor_id)->get(),
        ];
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function create()
    {
        $all_zones = Zone::all();
        $all_publish_status = Status::all()->pluck("name","id")->toArray();

        return ['all_zones' => $all_zones,'all_publish_status' => $all_publish_status];
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function edit($id)
    {
        $all_zones = Zone::all();
        $all_publish_status = Status::all()->pluck("name","id")->toArray();
        $method = VendorShippingMethod::where("vendor_id", auth("sanctum")->id())
            ->where("id", $id)->first();

        return [
            'all_zones' => $all_zones,
            'all_publish_status' => $all_publish_status,
            'method' => $method
        ];
    }
    /**
     * Display a listing of the resource.
     * @return string[]
     */
    public function store(StoreShippingMethodRequest $request)
    {
        $query = VendorShippingMethod::create($request->validated());

        return ["msg" => $query ? "Successfully created shipping method" : "Failed to create shipping method"];
    }

    /**
     * Display a listing of the resource.
     * @return string[]
     */
    public function update(StoreShippingMethodRequest $request, $id)
    {
        $query = VendorShippingMethod::where("vendor_id", auth("sanctum")->id())
            ->where("id", $id)->update($request->validated());

        return ["msg" => $query ? "Successfully updated shipping method" : "Failed to updated shipping method"];
    }

    public function makeDefault(){
        // First I need to check requested id is valid or not if valid then update all methods as not default after that update requested id row with value 1
        $vendor = VendorShippingMethod::where("vendor_id", auth("sanctum")->id())
            ->where("id",request()->id)->first();
        if(!empty($vendor)){
            VendorShippingMethod::where("vendor_id", auth("sanctum")->id())->update([
                "is_default" => 0
            ]);

            $vendor->update([
                "is_default" => 1
            ]);

            return ["msg" => "Updated successfully"];
        }

        return ["msg" => "Failed to update"];
    }

    public function destroy($id){
        // delete method
        $delete = VendorShippingMethod::where("vendor_id", auth("sanctum")->id())->where("id",$id)->delete();

        return response()->json(["msg" => $delete ? "Successfully deleted shipping method" : "Failed to delete shipping method"]);
    }
}
