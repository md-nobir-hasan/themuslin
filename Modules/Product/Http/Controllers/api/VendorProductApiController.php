<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\NoReturn;
use Modules\Product\Http\Resources\Api\VendorProductListResource;
use Modules\Product\Http\Services\Admin\AdminProductServices;

class VendorProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[NoReturn]
    public function index(Request $request)
    {
        $products = AdminProductServices::productSearch($request, "api.vendor", "vendor", false);

        return VendorProductListResource::collection($products);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        return response()->json((new AdminProductServices)->delete($id) ? [
            "success" => true,
        ] : [
            "success" => false
        ]);
    }

    public function updateStatus(Request $request){
        $data = $this->validateUpdateStatus($request->all());

        return (new AdminProductServices)->updateStatus($data["id"],$data["status_id"]);
    }

    private function validateUpdateStatus($req): array
    {
        return Validator::make($req,[
            "id" => "required",
            "status_id" => "required"
        ])->validated();
    }
}