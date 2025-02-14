@php
    $attributes = $product?->inventory_detail_count ?? null;
    $campaign_product = $product->campaign_product ?? null;
    $campaignProductEndDate = $product->campaign->end_date ?? $product->campaign->end_date->end_date ?? '';
    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product?->sale_price;
    $deleted_price = !is_null($campaign_product) ? $product?->sale_price : $product->price;
    $campaign_percentage = !is_null($campaign_product) ? getPercentage($product?->sale_price, $sale_price) : false;
    $campaignSoldCount = $product?->campaign_sold_product;
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
    $stock_count = $stock_count > 0 ? $stock_count : 0;
    $filter = $filter ?? false;
    $isAllowBuyNow = $isAllowBuyNow ?? false;
@endphp
<div class="slick-slider-items">
    <div class="global-card-item center-text style-03 no-shadow">
        <div class="global-card-thumb radius-10">
            <a href="{{ route("frontend.products.single",$product->slug) }}">
                {!! render_image($product->image) !!}
            </a>

            {!! view('product::components.frontend.common.badge-and-discount', compact('product', 'campaign_percentage')) !!}

            <ul class="global-thumb-icons">
                {!! view('product::components.frontend.common.link-option', compact('product','attributes','isAllowBuyNow')) !!}
            </ul>
        </div>
        <div class="global-card-contents">
            <h4 class="common-title-two"> <a href="{{ route("frontend.products.single",$product->slug) }}"> {{ $product->name }}</a> </h4>
            <div class="global-card-flex-contents">
                <div class="single-global-card">
                    <div class="global-card-left">
                        <span class="stock-available @if($stock_count > 0) color-stock @else text-danger @endif">
                            @if($stock_count > 0) {{ __("Stock Available") }}({{ $stock_count }}) @else {{ __("Out of stock") }} @endif
                        </span>
                    </div>
                    <div class="global-card-right {{ $product->reviews_avg_rating < 1 ? "d-none" : "" }}">
                        <div class="rating-wrap">
                            {!! view("product::components.frontend.common.rating-markup", compact('product')) !!}
                        </div>
                    </div>
                </div>
                <div class="single-global-card mt-2">
                    <div class="global-card-left">
                        <div class="price-update-through">
                            <span class="fs-24 fw-500 ff-rubik flash-prices color-one"> {!! amount_with_currency_symbol(calculatePrice($sale_price, $product)) !!} </span>
                            <span class="fs-18 flash-old-prices ff-rubik">{!! amount_with_currency_symbol(calculatePrice($deleted_price, $product)) !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
