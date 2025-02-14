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

<div class="col-xxl-6 col-lg-12 mt-4 campaign-counter" data-date="{{ $campaign_product->end_date->format("Y-m-d h:i:s") }}">
    <div class="shop-list-wrapper single-border">
        <div class="shop-wrapper-flex">
            <div class="signle-shop-list">
                <div class="shop-list-flex">
                    <div class="shop-thumbs">
                        <a href="{{ route("frontend.products.single",$product->slug) }}">
                            {!! render_image($product->image) !!}
                        </a>
                        @if($campaign_percentage > 0)
                            <div class="thumb-top-contents">
                                <span class="percent-box bg-color-two radius-5"> -{{ $campaign_percentage }}% </span>
                            </div>
                        @endif
                        <div class="campaign-countdown">
                            <div><span class="counter-days"></span></div>
                            <div><span class="counter-hours"></span></div>
                            <div><span class="counter-minutes"></span></div>
                            <div><span class="counter-seconds"></span></div>
                        </div>
                    </div>

                    <div class="shop-list-contents">
                        <h2 class="common-title"> <a href="{{ route("frontend.products.single", $product->slug) }}">{{ $product->name }}</a> </h2>

                        <div class="global-card-right">
                            <div class="rating-wrap">
                                <x-product::frontend.common.rating-markup :rating-count="$product->ratings_count" :avg-rattings="$product->ratings_avg_rating ?? 0" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-shop-cart center-text">
                <h2 class="price-title"> {{ float_amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </h2>
                <div class="shop-cart-flex mt-3">
                    <div class="btn-wrapper">
                        <a href="#1" class="cmn-btn btn-bg-1  btn-small radius-0 cart-loading"> {{ __("Add to Cart") }} </a>
                    </div>
                </div>
                <div class="btn-shop-botttom mt-3">
                    <ul class="global-thumb-icons">
                        <x-product::frontend.common.link-option :$product />
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>