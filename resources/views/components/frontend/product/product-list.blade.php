@php
    $attributes = \Modules\Product\Entities\ProductInventoryDetails::where("product_id",$product->id)->count();
@endphp

@if(isset($isCampaign) && $isCampaign)
    @php
        // campaign data check
        $campaign_product = !is_null($product->campaignProduct) ? $product->campaignProduct : getCampaignProductById($product->id);
        $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
        $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
        $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
    @endphp
@else
    @php
        // campaign data check
        $campaign_product = !is_null($product->campaignProduct) ? $product->campaignProduct : getCampaignProductById($product->id);
        $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
        $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
        $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
    @endphp
@endif

<div class="single-product-view-list">
    <div class="product-thumb">
        <a href="{{ route('frontend.products.single', $product->slug) }}" class="img-link">
            @php
                $isAjax = isset($isAjax) && $isAjax ? $isAjax : null;
                $is_lazy = isset($isAjax) && $isAjax ? false : true; // if loaded on product filter or any other ajax, disable lazy laoding
            @endphp
            {!! render_image_markup_by_attachment_id($product->image, '', 'grid', $is_lazy, $isAjax) !!}
        </a>
            <div class="other-content">
                @if(isset($isCampaign) && $isCampaign)
                    <span class="discount-tag">{{ $campaign_percentage }}%</span>
                @endif
            </div>
    </div>
    <div class="product-content">
        <div class="main-content">
            <h4 class="product-title">
                <a href="{{ route('frontend.products.single', $product->slug) }}">{{ $product->title }}</a>
            </h4>
            <div class="product-meta">
                <span class="product-meta">{{ amount_with_currency_symbol($sale_price) }} / {{ $product->uom?->quantity }} {{ $product->uom?->unit?->name }}</span>
            </div>
            <div class="ratings list-card">
{{--                @if($product->ratingCount())--}}
{{--                {!! ratingMarkup($product->ratingAvg(), $product->ratingCount(), false) !!}--}}
{{--                @endif--}}
            </div>
            <div class="product-pricing">
                <del>{{ amount_with_currency_symbol($deleted_price) }}</del>
                <span class="price">{{ amount_with_currency_symbol($sale_price) }}</span>
            </div>
            <div class="btn-and-quick-key">
                <div class="btn-wrapper">
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $product->slug) }}" class="add-cart-style-02">
                            {{ __('View Options') }}
                        </a>
                    @else
                        <a href="#1" class="add-cart-style-02 add_to_cart_ajax"
                           data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}"
                        >
                            {{ __('Add to Bag') }}
                        </a>
                    @endif
                </div>
                <div class="quick-key">
                    <ul class="quick-key-list">
                        <li>
                            @if(isset($attributes) && $attributes > 0)
                                <a href="{{ route('frontend.products.single', $product->slug) }}">
                                    <i class="lar la-heart icon"></i>
                                </a>
                            @else
                                <a href="{{ route('frontend.products.single', $product->slug) }}"
                                   class="add_to_wishlist_ajax"
                                   data-attributes="{{ $product->attributes }}"
                                   data-id="{{ $product->id }}"
                                >
                                    <i class="lar la-heart icon"></i>
                                </a>
                            @endif
                        </li>
                        <li>
                            <a href="#1" data-id="{{ $product->id }}" class="add_to_compare_ajax">
                                <i class="las la-random icon"></i>
                            </a>
                        </li>
                        <li>
                            @if(isset($attributes) && $attributes > 0)
                                <a class="product-quick-view-ajax" href="#1" data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}">
                                    <i class="las la-expand-arrows-alt icon"></i>
                                </a>
                            @else
                                <a class="quick-view" {!! getQuickViewDataMarkup($product) !!}>
                                    <i class="las la-expand-arrows-alt icon"></i>
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
