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
@endphp

<div class="slick-slider-items wow fadeInUp" data-wow-delay=".{{ $loop->iteration }}s">
    <div class="global-card-item vendor-global-card-item radius-10">
        <div class="global-card-thumb radius-10">
            <a href="#1">
                {!! render_image($product->image) !!}
            </a>

            @if($campaign_percentage > 0)
                <div class="thumb-top-contents">
                    <span class="percent-box bg-color-two radius-5"> -{{ $campaign_percentage }}% </span>
                </div>
            @endif

            <ul class="global-thumb-icons hover-color-two">
                <x-product::frontend.common.link-option :$product />
            </ul>
        </div>
        <div class="global-card-contents">
            <h4 class="common-title"> <a href="{{ route("frontend.products.single", $product->slug) }}">{{ $product->name }}</a> </h4>
            <div class="d-flex flex-wrap justify-content-between">
                <div class="stock mt-2">
                    <span class="stock-available {{ $stock_count ? "text-success" : "text-danger" }}"> {{ $stock_count ? "In Stock ($stock_count)" : "Out of stock" }} </span>
                </div>

                <div class="rating-wrap mt-2">
                    <div class="rating-wrap">
                        <x-product::frontend.common.rating-markup :rating-count="$product->ratings_count" :avg-rattings="$product->ratings_avg_rating ?? 0" />
                    </div>
                </div>
            </div>
            <div class="price-update-through mt-2">
                <span class="fs-24 fw-500 flash-prices"> {{ float_amount_with_currency_symbol($sale_price) }} </span>
                <span class="fs-18 flash-old-prices"> {{ float_amount_with_currency_symbol($deleted_price) }} </span>
            </div>
            <div class="btn-wrapper mt-3">
                @if($product?->inventory_detail_count > 0)
                    <a href="javacript:void(0)" data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}" class="cmn-btn btn-outline-two color-two btn-small product-quick-view-ajax">{{ __("Buy Now") }}</a>
                @else
                    <a href="javacript:void(0)" data-id="{{ $product->id }}" class="cmn-btn btn-outline-two color-two btn-small add_to_buy_now_ajax">{{ __("Buy Now") }}</a>
                @endif
            </div>
        </div>
    </div>
</div>