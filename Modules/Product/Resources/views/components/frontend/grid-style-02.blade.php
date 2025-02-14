@php
    $attributes = $product?->inventory_detail_count ?? null;
    $campaign_product = $product->campaign_product ?? null;
    $campaignProductEndDate = $product->campaign->end_date ?? $product->campaign->end_date->end_date ?? '';
    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
    $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
    $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
    $campaignSoldCount = $product?->campaign_sold_product;
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
    $stock_count = $stock_count > 0 ? $stock_count : 0;
    $filter = $filter ?? false;
@endphp
@if(!$filter)
<div class="col-xxl-3 col-xl-3 col-lg-4 col-sm-6 mt-4 wow fadeInUp" data-wow-delay=".{{ $loop->iteration }}s">
@endif
    <div class="global-card-item style-02 center-text radius-10">
        <div class="global-card-thumb radius-10">
            <a href="{{ route("frontend.products.single",$product->slug) }}">
                {!! render_image($product->image) !!}
            </a>

            {!! view('product::components.frontend.common.badge-and-discount', compact('product', 'campaign_percentage')) !!}

            <ul class="global-thumb-icons hover-color-two">
                {!! view('product::components.frontend.common.link-option', compact('product','attributes')) !!}
            </ul>
        </div>
        <div class="global-card-contents">
            <div class="single-global-card {{ $product->reviews_avg_rating < 1 ? "d-none" : "" }}">
                <div class="global-card-right">
                    <div class="rating-wrap">
                        {!! view('product::components.frontend.common.rating-markup', compact('product')) !!}
                    </div>
                </div>
            </div>
            <h5 class="common-title-two hover-color-two mt-2"><a href="{{ route("frontend.products.single",$product->slug) }}"> {{ $product->name }}</a></h5>
            <div class="global-card-flex-contents">
                <div class="single-global-card">
                    <div class="global-card-left">
                        <div class="price-update-through">
                            <span class="fs-20 fw-500 ff-rubik flash-prices color-two"> {{ float_amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </span>
                            <span class="fs-16 flash-old-prices"> {{ float_amount_with_currency_symbol(calculatePrice($deleted_price, $product)) }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$filter)
</div>
@endif