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
    $quick_view_data = getQuickViewDataMarkup($product);

    if(isset($attributes) && $attributes > 0){
        $quick_view_markup = '<a class="product-quick-view-ajax" href="#1" data-action-route="'. route('frontend.products.single-quick-view', $product->slug) .'">
                                <i class="lar la-eye icon"></i>
                            </a>';
    }else{
        $quick_view_markup = '<a href="#1" id="quickview" class="quick-view" '.$quick_view_data.'}><i class="lar la-eye icon"></i></a>';
    }
@endphp

<div class="single-product-view-grid-style-03">
    <div class="product-thumb">
        <a href="#1" class="img-link">
            {!! render_image_markup_by_attachment_id($product->image) !!}
        </a>
        <div class="other-content">
            @if(!empty($product->badge))
                <span class="badge-tag">{{ $product->badge }}</span>
            @endif
            @if(!empty($campaign_percentage))
                <span class="discount-tag">{{ round($campaign_percentage,0) }}%</span>
            @endif
        </div>
        <div class="hover-content">
            <ul class="hover-element-list">
                <li>
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $product->slug) }}">
                            <i class="las la-shopping-cart icon"></i>
                        </a>
                    @else
                        <a href="#1" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}"
                           class="add_to_cart_ajax">
                            <i class="las la-shopping-cart icon"></i>
                        </a>
                    @endif
                </li>
                <li>
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $product->slug) }}">
                            <i class="lar la-heart icon"></i>
                        </a>
                    @else
                        <a href="#1" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}"
                           class="add_to_wishlist_ajax">
                            <i class="lar la-heart icon"></i></a>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="#1" data-id="{{$product->id}}" class="add_to_compare_ajax"> <i class="las la-retweet icon"></i></a>
                </li>
                <li>
{{--                    {!! $quick_view_markup !!}--}}
                </li>
            </ul>
        </div>
    </div>
    <div class="product-content">
        <div class="main-content">
            <div class="ratings">
{{--                @if($product->ratingCount())--}}
{{--                {!! ratingMarkup($product->ratingAvg(), $product->ratingCount()) !!}--}}
{{--                @endif--}}
            </div>
            <h4 class="product-title">
                <a href="{{ route('frontend.products.single', $product->slug) }}">{{ html_entity_decode($product->title) }}</a>
            </h4>
            <div class="product-meta-and-pricing">
                <span class="product-meta">{{ $product->uom?->quantity }} {{ $product->uom?->unit?->name }}</span>
                <div class="product-pricing">
                    <del>{{ float_amount_with_currency_symbol(round($deleted_price,2)) }}</del>
                    <span class="price">{{ float_amount_with_currency_symbol(round($sale_price,2)) }}</span>
                </div>
            </div>

            <div class="btn-wrapper">
                @if(isset($attributes) && $attributes > 0)
                    <a href="{{ route('frontend.products.single', $product->slug) }}" class="add-cart-style-02">
                        {{__("View Option")}}
                    </a>
                @else
                    <a href="#1" data-attributes="{{ $product->attributes }}" data-id="{{ $product->id }}"
                       class="add_to_cart_ajax add-cart-style-02">
                        {{__("Add to Bag")}}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>