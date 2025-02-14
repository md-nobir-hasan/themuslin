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
    $rating_width = round(($product->ratings_avg_rating ?? 0) * 20);
@endphp
<div class="slick-slider-items radius-10">
    <div class="global-card-item style-02 center-text radius-10 no-shadow">
        <div class="global-card-thumb radius-10">
            <a href="{{ route("frontend.products.single",$product->slug) }}">
                {!! render_image($product->image) !!}
            </a>

            {!! view('product::components.frontend.common.badge-and-discount', compact('product', 'campaign_percentage')) !!}

            <ul class="global-thumb-icons hover-color-two">
                <x-product::frontend.common.link-option :product="$product"/>
            </ul>
        </div>
        <div class="global-card-contents">
            <h5 class="common-title hover-color-two"> <a href="{{ route("frontend.products.single",$product->slug) }}"> {{ $product->name }}</a> </h5>
            <div class="global-card-flex-contents">
                <div class="single-global-card {{ $product->reviews_avg_rating < 1 ? "d-none" : "" }}">
                    <div class="global-card-right">
                        <div class="rating-wrap">
                            <div class="ratings {{ $rating_width == 0 ? "d-none" : "" }}">
                                <span class="hide-rating"></span>
                                <span class="show-rating" style="width: {{ $rating_width }}%!important"></span>
                            </div>
                            <p> <span class="total-ratings">{{ $product->ratings_count ? "(". $product->ratings_count .")" : "" }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="single-global-card">
                    <div class="global-card-left">
                        <div class="price-update-through">
                            <span class="fs-20 fw-500 ff-rubik flash-prices color-two"> {{ float_amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </span>
                            <span class="fs-16 flash-old-prices"> {{ float_amount_with_currency_symbol(calculatePrice($deleted_price, $product)) }} </span>
                        </div>
                    </div>
                </div>
                <div class="global-card-left mt-2">
                    <a href="#1" class="stock-available color-stock {{ $stock_count ?: "text-danger" }}"> {{ $stock_count ? "Stock Available($stock_count)" : "Out of stock" }} </a>
                </div>
            </div>
        </div>
    </div>
</div>