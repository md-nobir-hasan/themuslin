@php
    $campaign_product = $product->campaign_product ?? null;
    $campaignProductEndDate = $product->campaign->end_date ?? $product->campaign->end_date->end_date ?? '';
    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
    $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
    $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
    $campaignSoldCount = $product?->campaign_sold_product;
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
    $stock_count = $stock_count > 0 ? $stock_count : 0;
    $rating_width = round(($product->ratings_avg_rating ?? 0) * 20);
    $campaign_percentage = round($campaign_percentage, 0);
@endphp

<div class="col-xl-2 col-lg-4 col-sm-6 mt-4 campaign-counter" data-date="{{ $campaign_product->end_date->format("Y-m-d h:i:s") }}">
    <div class="global-card-item style-03 center-text no-shadow">
        <div class="global-card-thumb radius-10">
            <a href="{{ route("frontend.products.single",$product->slug) }}">
                {!! render_image($product->image) !!}
            </a>

            @if($campaign_percentage > 0)
                <div class="thumb-top-contents">
                    <span class="percent-box bg-color-two radius-5"> -{{ $campaign_percentage }}% </span>
                </div>
            @endif
            <ul class="global-thumb-icons">
                <x-product::frontend.common.link-option :$product />
            </ul>
        </div>
        <div class="global-card-contents">
            <div class="campaign-countdown">
                <div><span class="counter-days"></span></div>
                <div><span class="counter-hours"></span></div>
                <div><span class="counter-minutes"></span></div>
                <div><span class="counter-seconds"></span></div>
            </div>

            <h4 class="common-title"> <a href="{{ route("frontend.products.single", $product->slug) }}">{{ $product->name }}</a> </h4>

            <div class="global-card-flex-contents">
                <div class="single-global-card d-flex justify-content-between">
                    <div class="global-card-left">
                        <span class="stock-available {{ $stock_count ? "text-success" : "text-danger" }}"> {{ $stock_count ? "In Stock ($stock_count)" : "Out of stock" }} </span>
                    </div>
                    <div class="global-card-right">
                        <div class="rating-wrap">
                            <x-product::frontend.common.rating-markup :rating-count="$product->ratings_count" :avg-rattings="$product->ratings_avg_rating ?? 0" />
                        </div>
                    </div>
                </div>
                <div class="single-global-card mt-2">
                    <div class="global-card-left">
                        <div class="price-update-through">
                            <span class="fs-24 fw-500 flash-prices"> {{ float_amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </span>
                            <span class="fs-18 flash-old-prices"> {{ float_amount_with_currency_symbol(calculatePrice($deleted_price, $product)) }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>