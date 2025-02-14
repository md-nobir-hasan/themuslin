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
    $rating_width = round(($product->ratings_avg_rating ?? 0) * 20);
    $isAllowBuyNow = $isAllowBuyNow ?? false;
@endphp

<div class="slick-slider-items radius-10">
    <div class="single-flash-item">
        <div class="flash-flex-item">
            <div class="flash-item-image radius-5">
                {!! render_image($product->image) !!}

                {!! view('product::components.frontend.common.badge-and-discount', compact('product', 'campaign_percentage')) !!}

                <ul class="flash-thumb-icons hover-color-two">
                    {!! view('product::components.frontend.common.link-option', compact('product','attributes','isAllowBuyNow')) !!}
                </ul>
            </div>
            <div class="flash-item-contents">
                <h6 class="title hover-color-two">
                    <a href="{{ route("frontend.products.single",$product->slug) }}"> {{ $product->name }}</a>
                </h6>
                <div class="rating-wrap mt-2 {{ $product->reviews_avg_rating < 1 ? "d-none" : "" }}">
                    {!! view('product::components.frontend.common.rating-markup', compact('product','attributes')) !!}
                </div>
                <div class="price-update-through margin-top-15">
                    <span class="fs-20 fw-500 ff-rubik flash-prices color-two"> {{ float_amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </span>
                    <span class="fs-16 flash-old-prices"> {{ float_amount_with_currency_symbol(calculatePrice($deleted_price, $product)) }} </span>
                </div>
            </div>
        </div>
    </div>
</div>