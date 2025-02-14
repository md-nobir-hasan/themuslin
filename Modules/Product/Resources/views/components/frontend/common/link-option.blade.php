@php
    $attributes = $product?->inventory_detail_count ?? null;
@endphp

@if(isset($attributes) && $attributes > 0)
    <li class="lists">
        <a class="product-quick-view-ajax favourite icon cart-loading" href="#1"
           data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}">
            <i class="las la-shopping-bag"></i>
        </a>
    </li>
@else
    <li class="lists">
        <a href="#1" data-attributes="{{ $product->attribute }}" data-id="{{ $product->id }}"
           class="icon cart-loading {{ ($isAllowBuyNow ?? false) ? "add_to_buy_now_ajax" : "add_to_cart_ajax" }}" >
            <i class="las la-shopping-bag"></i>
        </a>
    </li>
@endif

@if(isset($attributes) && $attributes > 0)
    <li class="lists">
        <a class="product-quick-view-ajax favourite icon cart-loading" href="#1"
           data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}">
            <i class="lar la-heart"></i>
        </a>
    </li>
@else
    <li class="lists">
        <a href="#1" data-id="{{ $product->id }}" class="favourite add_to_wishlist_ajax icon cart-loading">
            <i class="lar la-heart"></i>
        </a>
    </li>
@endif

<li class="lists">
    <a href="#1" data-id="{{ $product->id }}" class="favourite add_to_compare_ajax icon cart-loading">
        <i class="las la-retweet"></i>
    </a>
</li>

<li class="lists">
    <a class="product-quick-view-ajax favourite icon cart-loading" href="#1"
       data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}">
        <i class="lar la-eye"></i>
    </a>
</li>