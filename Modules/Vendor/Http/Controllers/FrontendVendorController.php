<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Attributes\Entities\Category;
use Modules\Campaign\Entities\Campaign;
use Modules\Product\Entities\Product;
use Modules\Vendor\Entities\Vendor;

class FrontendVendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with("cover_photo","logo")
            ->withAvg("vendorProductRating","product_ratings.rating")
            ->withCount(["vendorProductRating","product"])
            ->whereHas("product")
            ->orderByDesc("vendor_product_rating_count")
            ->get();

        return view("vendor::frontend.vendors-page", compact("vendors"));
    }

    public function vendor($username) {
        $vendor = Vendor::with("cover_photo","logo")
            ->withAvg("vendorProductRating","product_ratings.rating")
            ->withCount(["vendorProductRating","product"])
            ->whereHas("product")->where("username", $username)
            ->orderByDesc("vendor_product_rating_count")->firstOrFail();

        $ourPopularProducts = Product::withCount("orderItems","inventoryDetail","ratings")
            ->with("campaign_product","campaign_sold_product","inventory")
            ->withAvg("ratings",'rating')
            ->where("vendor_id", $vendor->id)
            ->orderByDesc("order_items_count")->limit(20)->get();

        $ourAllProducts = Category::with(["image","product" => function ($categoryQuery) use ($vendor){
            $categoryQuery->withCount("inventoryDetail","ratings")
            ->with(["campaign_product","campaign_sold_product","inventory","category" => function ($categoryQuery){
                $categoryQuery->limit(1);
            }])->withAvg("ratings",'rating')
            ->where("vendor_id", $vendor->id)
            ->limit(8);
        }])->withCount(["product" => function ($productQuery) use ($vendor) {
            $productQuery->where("vendor_id", $vendor->id);
        }])->whereHas("product")
        ->havingRaw("product_count > 0")
        ->get();

        $vendorCampaigns = Campaign::with("campaignImage")->whereHas("product")->where("vendor_id", $vendor->id)->get();

        return view("vendor::frontend.single-vendors", compact("vendor","ourPopularProducts","ourAllProducts","vendorCampaigns"));
    }

    public function vendorProducts($username){
        return $username;
    }

    public function searchVendor(){
        $vendors = Vendor::query()->with('logo','cover_photo')
            ->withCount('vendorProductRating','orderItems')
            ->withAvg('vendorProductRating','rating')->where('status_id', 1);

        // check search type
        if(request()->type === 'top_rated'){
            $vendors = $vendors->orderByDesc('vendor_product_rating_count');
        }elseif (request()->type === 'top_selling'){
            $vendors = $vendors->orderByDesc('order_items_count');
        }elseif(request()->type === 'weekly_top'){
            $vendors = $vendors->withCount('orderItems')
                ->whereHas('order', function ($query){
                    $query->whereBetween("orders.created_at", [now()->subWeek()->format("Y-m-d"), now()->format("Y-m-d")]);
                })
                ->orderByDesc('order_items_count');
        }

        $vendors = $vendors->when(request()->has('limit'), function ($query){
            $query->limit(request()->limit ?? 12);
        })->get();


        return view('vendor::frontend.vendor-addon', compact('vendors'));
    }
}