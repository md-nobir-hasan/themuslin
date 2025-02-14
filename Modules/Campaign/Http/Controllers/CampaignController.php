<?php

namespace Modules\Campaign\Http\Controllers;

use App\Helpers\FlashMsg;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Campaign\Http\Requests\CampaignValidationRequest;
use Modules\Campaign\Http\Services\GlobalCampaignService;
use Modules\Product\Entities\Product;
use Throwable;

class CampaignController extends Controller
{
    const BASE_URL = 'campaign::backend.';

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $all_campaigns =  GlobalCampaignService::fetch_campaigns(); //Campaign::with("campaignImage")->get();

        return view(self::BASE_URL.'all', compact('all_campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return GlobalCampaignService::renderCampaignProduct(self::BASE_URL);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignValidationRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(CampaignValidationRequest $request): RedirectResponse
    {   
        $data = $request->validated();

        try{
            DB::beginTransaction();
            $campaign = Campaign::create($data);

            if ($campaign->id) {
                GlobalCampaignService::insertCampaignProducts($campaign->id, $data["products"]);
            }

            DB::commit();
            return back()->with(FlashMsg::create_succeed('Campaign'));
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with(FlashMsg::create_failed('Campaign'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $item
     * @return Application|Factory|View
     */
    public function edit($item): Application|Factory|View
    {
        return GlobalCampaignService::renderCampaignProduct(self::BASE_URL,$item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignValidationRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(CampaignValidationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::beginTransaction();
        try{
            Campaign::findOrFail($request->id)->update($data);
            GlobalCampaignService::updateCampaignProducts($request->id, $data);

            DB::commit();
            return back()->with(FlashMsg::update_succeed('Campaign'));
        } catch (Throwable $th) {
            DB::rollBack();
            return back()->with(FlashMsg::update_failed('Campaign'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Campaign $item
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Campaign $item): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $products = $item->products;
            if ($products->count()) {
                foreach ($products as $product) {
                    $product->delete();
                }
            }
            $item_deleted = $item->delete();
            DB::commit();
            return back()->with($item_deleted ? ["msg" => __("Successfully deleted campaign"), "type" => "success"] : ["msg" => __("Failed to delete, Something went wrong."), "type" => "danger"]);
        } catch (Throwable $th) {
            DB::rollBack();
            return back()->with(["msg" => __("Failed to delete campaign"), "type" => "danger"]);
        }
    }

    public function bulk_action(Request $request)
    {
        try {
            DB::beginTransaction();
            Campaign::whereIn('id', $request->ids)->delete();
            CampaignProduct::whereIn('campaign_id', $request->ids)->delete();
            DB::commit();
            return 'ok';
        } catch (Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getProductPrice(Request $request): JsonResponse
    {
        $price = Product::findOrFail($request->id)->price;
        return response()->json(['price' => $price], 200);
    }

    public function deleteProductSingle(Request $request): bool
    {
        $delete = (bool) CampaignProduct::findOrFail($request->id)->delete();

        return back()->with([
            "type" => $delete ? "success" : "danger",
            "msg" => $delete ? __("Successfully deleted") : __("Failed to delete"),
        ]);
    }
}
