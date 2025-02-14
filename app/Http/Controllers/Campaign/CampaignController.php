<?php

namespace Modules\CountryManage\Http\Controllers\Campaign;

use App\Helpers\FlashMsg;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Campaign\Entities\Campaign;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\Product\Entities\Product;

class CampaignController extends Controller
{
    const BASE_URL = 'backend.campaign.';

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:campaign-list|campaign-create|campaign-edit|campaign-delete', ['only', ['index']]);
        $this->middleware('permission:campaign-create', ['only', ['store']]);
        $this->middleware('permission:campaign-edit', ['only', ['update']]);
        $this->middleware('permission:campaign-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_campaigns = Campaign::all();

        return view(self::BASE_URL.'all', compact('all_campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_campaign_products = CampaignProduct::select('product_id')->pluck('product_id')->toArray();
        $all_products = Product::with('inventory')->where('status', 'publish')->whereNotIn('id', $all_campaign_products)->get();

        return view(self::BASE_URL.'new', compact('all_products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:191',
            'campaign_subtitle' => 'required|string',
            'image' => 'required|string',
            'status' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $campaign = Campaign::create([
                'title' => $request->sanitize_html('campaign_name'),
                'subtitle' => $request->sanitize_html('campaign_subtitle'),
                'image' => $request->sanitize_html('image'),
                'status' => $request->sanitize_html('status'),
            ]);

            if ($campaign->id) {
                $validated_product_data = $this->getValidatedCampaignProducts($request);
                $this->insertCampaignProducts($campaign->id, $validated_product_data);
            }

            DB::commit();

            return back()->with(FlashMsg::create_succeed('Campaign'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(FlashMsg::create_failed('Campaign'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modules\Campaign\Entities\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $item)
    {
        $campaign = Campaign::with(['products', 'products.product'])->findOrFail($item->id);
        $other_campaign_products = CampaignProduct::select('product_id')->where('campaign_id', '!=', $campaign->id)->pluck('product_id')->toArray();
        $all_products = Product::with('inventory')->where('status', 'publish')->whereNotIn('id', $other_campaign_products)->get();

        return view(self::BASE_URL.'edit', compact('campaign', 'all_products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Campaign\Entities\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|string|max:191',
            'campaign_subtitle' => 'required|string',
            'image' => 'required|string',
            'status' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            $campaign = Campaign::findOrFail($request->id)->update([
                'title' => $request->sanitize_html('campaign_name'),
                'subtitle' => $request->sanitize_html('campaign_subtitle'),
                'image' => $request->sanitize_html('image'),
                'status' => $request->sanitize_html('status'),
            ]);
            $this->updateCampaignProducts($request->id, $request);
            DB::commit();

            return back()->with(FlashMsg::update_succeed('Campaign'));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with(FlashMsg::update_failed('Campaign'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modules\Campaign\Entities\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $item)
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

            return (bool) $item_deleted;
        } catch (\Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    public function bulk_action(Request $request)
    {
        try {
            DB::beginTransaction();
            $all_campaigns = Campaign::whereIn('id', $request->ids)->delete();
            $campaign_products = CampaignProduct::whereIn('campaign_id', $request->ids)->delete();
            DB::commit();

            return 'ok';
        } catch (\Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    public function getProductPrice(Request $request)
    {
        $price = Product::findOrFail($request->id)->price;

        return response()->json(['price' => $price], 200);
    }

    public function deleteProductSingle(Request $request)
    {
        return (bool) CampaignProduct::findOrFail($request->id)->delete();
    }

    /**====================================================================
     *                  CAMPAIGN PRODUCT FUNCTIONS
    ==================================================================== */
    public function updateCampaignProducts($campaign_id, Request $request)
    {
        try {
            DB::beginTransaction();
            $delete = $this->deleteCampaignProducts($request->product_id);
            $validated_product_data = $this->getValidatedCampaignProducts($request);
            $campaign_products = $this->insertCampaignProducts($campaign_id, $validated_product_data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return false;
        }
    }

    public function getValidatedCampaignProducts(Request $request)
    {
        return $request->validate([
            'product_id' => 'required|array',
            'campaign_price' => 'required|array',
            'units_for_sale' => 'required|array',
            'start_date' => 'required|array',
            'end_date' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'campaign_price.*' => 'required|string',
            'units_for_sale.*' => 'required|string',
            'start_date.*' => 'required|date',
            'end_date.*' => 'required|date',
        ]);
    }

    public function insertCampaignProducts($campaign_id, $products_data)
    {
        $insert_data = [];

        foreach ($products_data['product_id'] as $key => $value) {
            $insert_data[$products_data['product_id'][$key]] = [
                'campaign_id' => $campaign_id,
                'product_id' => $products_data['product_id'][$key],
                'campaign_price' => $products_data['campaign_price'][$key],
                'units_for_sale' => $products_data['units_for_sale'][$key],
                'start_date' => $products_data['start_date'][$key],
                'end_date' => $products_data['end_date'][$key],
            ];
        }

        return (bool) CampaignProduct::insert($insert_data);
    }

    public function deleteCampaignProducts($all_product_id)
    {
        return (bool) CampaignProduct::whereIn('product_id', $all_product_id)->delete();
    }
}
