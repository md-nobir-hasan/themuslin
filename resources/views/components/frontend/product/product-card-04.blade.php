@php
    // campaign data check
    $campaign_product = !is_null($item->campaignProduct) ? $item->campaignProduct : getCampaignProductById($item->id);
    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $item->sale_price;
    $deleted_price = !is_null($campaign_product) ? $item->sale_price : $item->price;
    $campaign_percentage = !is_null($campaign_product) ? getPercentage($item->sale_price, $sale_price) : false;
    $buttons = \App\PageBuilder\Services\ProductItemServices::product_hover_button($item);

    $attributes = \Modules\Product\Entities\ProductInventoryDetails::where("product_id",$item->id)->count();
    $quick_view_data = getQuickViewDataMarkup($item);
    $quick_view_markup = '<a href="#1" id="quickview" class="quick-view" '.$quick_view_data.'}><i class="lar la-eye icon"></i></a>';
@endphp


<div class="single-new-popular-dress-item style-02">
    <div class="img-box bg-color-off-white">
        <span class="tag-box-new top-right">
            @if(!empty($item->badge))
                <span class="tag-new bg-dark border-radius">
                    {{$item->badge}}
                </span>
            @endif
            @if(!empty($campaign_percentage))
                <span class="tag-new bg-orange border-radius">
                    {{$item->badge}}
                </span>
            @endif
        </span>
        <span class="product-bg-img bg-position-bottom"
              {!! render_background_image_markup_by_attachment_id($item->image) !!} data-width="100%"
              data-height="380"></span>
        <div class="hover">
            <ul class="product-interaction-list">
                <li>
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $item->slug) }}">
                            <i class="las la-shopping-cart icon"></i>
                        </a>
                    @else
                        <a href="#1" data-attributes="{{ $item->attributes }}" data-id="{{ $item->id }}"
                           class="add_to_cart_ajax">
                            <i class="las la-shopping-cart icon"></i>
                        </a>
                    @endif
                </li>
                <li>
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $item->slug) }}">
                            <i class="lar la-heart icon"></i>
                        </a>
                    @else
                        <a href="#1" data-attributes="{{ $item->attributes }}" data-id="{{ $item->id }}"
                           class="add_to_wishlist_ajax">
                            <i class="lar la-heart icon"></i></a>
                        </a>
                    @endif
                </li>
                <li>
                    <a href="#1" data-id="{{$item->id}}" class="add_to_compare_ajax"> <i class="las la-retweet icon"></i></a>
                </li>
                <li>
                    @if(isset($attributes) && $attributes > 0)
                        <a class="product-quick-view-ajax" href="#1" data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}">
                            <i class="las la-expand-arrows-alt icon"></i>
                        </a>
                    @else
                        {!! $quick_view_markup !!}
                    @endif
                </li>
            </ul>
        </div>
    </div>
    <div class="content">
        <h3 class="title">
            <a href="{{ route('frontend.products.single', $item->slug) }}" tabindex="0">{{ html_entity_decode($item->title) }}</a>
        </h3>
        <ul class="pricing">
            <li class="on-price">{{ float_amount_with_currency_symbol($sale_price) }}</li>
            <li class="off-price"><del>{{ float_amount_with_currency_symbol($deleted_price) }}</del></li>
        </ul>
        <div class="details">
            <div class="left-content">
                @if(isset($attributes) && $attributes > 0)
                    <a href="{{ route('frontend.products.single', $item->slug) }}" class=" add-to-cart-new">
                        Add to Cart
                    </a>
                @else
                    <a href="#1" data-attributes="{{ $item->attributes }}" data-id="{{ $item->id }}"
                       class="add_to_cart_ajax add-to-cart-new">
                        Add to Cart
                    </a>
                @endif
            </div>
            <div class="right-content">
                <ul class="reaction">
                    @if(isset($attributes) && $attributes > 0)
                        <a href="{{ route('frontend.products.single', $item->slug) }}">
                            <i class="lar la-heart icon"></i>
                        </a>
                    @else
                        <a href="#1" data-attributes="{{ $item->attributes }}" data-id="{{ $item->id }}"
                           class="add_to_wishlist_ajax">
                            <i class="lar la-heart icon"></i></a>
                        </a>
                    @endif
                    {!! $quick_view_markup !!}
                </ul>
            </div>
        </div>
    </div>
</div>
