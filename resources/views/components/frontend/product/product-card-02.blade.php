@php
    $attributes = \Modules\Product\Entities\ProductInventoryDetails::where("product_id",$product->id)->count();

    $campaign_product = getCampaignProductById($product->id);
    $sale_price = $campaign_product ? $campaign_product->campaign_price : $product->sale_price;
    $deleted_price = $campaign_product ? $product->sale_price : $product->price;
    $campaign_percentage = $campaign_product ? getPercentage($product->sale_price, $sale_price) : false;
    $campaignProductEndDate = $product->campaign->end_date ?? $product->campaign->end_date->end_date ?? '';

    $campaignSoldCount = \Modules\Campaign\Entities\CampaignSoldProduct::where("product_id",$product->id)->first();
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
    $stock_count = $stock_count > 0 ? $stock_count : 0;
@endphp

<div class="single-product-view-grid-style-02 product_card">
    <div class="product-thumb lazy"
            {!! render_background_image_markup_by_attachment_id($product->image, 'grid', true) !!}
    >
        <div class="other-content">
            @if(!empty($product->badge))
                <span class="badge-tag">{{ $product->badge }}</span>
            @endif
            @if(!empty($campaign_percentage))
                <span class="discount-tag">{{ round($campaign_percentage,0) }}%</span>
            @endif
        </div>
        <div class="hover-content">
            <div class="cart-control">
                <div class="value-button minus decrease"><i class="las la-minus"></i></div>
                <input type="number" name="quantity" class="qty_" value="1" />
                <div class="value-button plus increase"><i class="las la-plus"></i></div>
            </div>
        </div>
    </div>
    <div class="product-content">
        <div class="main-content">
            <div class="product-pricing">
                <del>{{ float_amount_with_currency_symbol($deleted_price) }}</del>
                <span class="price">{{ float_amount_with_currency_symbol($sale_price) }}</span>
            </div>
            <h4 class="product-title">
                <a href="{{ route('frontend.products.single', $product->slug) }}">{{ $product->title }}</a>
            </h4>
            <div class="product-meta">
                <span class="quantity">{{ $product->uom?->quantity }} {{ $product->uom?->unit?->name }}</span>

                @if ($stock_count > 0)
                    <span class="availability">{{ __('In stock') }} ({{ $stock_count }})</span>
                @else
                    <span class="availability text-danger">{{ __('Out of stock') }} ({{ $stock_count }})</span>
                @endif
            </div>
            <div class="ratings">
                @if($product->ratingCount())
                    {!! ratingMarkup($product->ratingAvg(), $product->ratingCount()) !!}
                @endif
            </div>
            <div class="btn-wrapper">
                @if(isset($attributes) && $attributes > 0)
                    <a href="{{ route('frontend.products.single', $product->slug) }}" class="add-cart-style-02">{{ __('View Options') }}</a>
                    <a href="{{ route('frontend.products.single', $product->slug) }}" class="favourite">
                        <i class="las la-heart icon hover"></i>
                        <i class="lar la-heart icon regular"></i>
                    </a>
                @else
                    <a href="#1" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}" class="add-cart-style-02 add_to_cart_ajax_with_quantity">{{ __('Add to Bag') }}</a>
                    <a href="#1" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}" class="favourite add_to_wishlist_ajax">
                        <i class="las la-heart icon hover"></i>
                        <i class="lar la-heart icon regular"></i>
                    </a>
                @endif
                <a href="#1" data-id="{{ $product->id }}" class="favourite add_to_compare_ajax">
                    <i class="las la-plus-square icon hover"></i>
                    <i class="lar la-plus-square icon regular"></i>
                </a>
            </div>
        </div>
    </div>
</div>
