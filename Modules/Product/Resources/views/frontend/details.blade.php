@extends('frontend.frontend-page-master')

@section('product-name')
    {{ $product->name }}
@endsection

@section('product-category')
    @if($product?->category)
        <li class="category-list">
            <a class="list-item" href="{{ route('frontend.products.category', $product?->category?->slug) }}">
                {{ $product?->category?->name }}
            </a>
        </li>
    @endif

    @if($product?->subCategory)
        <li class="category-list">
            <a class="list-item" href="{{ route('frontend.products.subcategory', $product?->subCategory?->slug) }}">
                {{ $product?->subCategory?->name }}
            </a>
        </li>
    @endif
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/common/css/font-awesome.min.css') }}">
    @if(moduleExists('Chat'))
        @include("chat::components.frontend-css")
    @endif
@endsection

@php
    $attributes = $product?->inventory_detail_count ?? null;
    $campaign_product = $product->campaign_product ?? null;
    $campaignProductEndDate = $product->campaign->end_date ?? ($product->campaign->end_date->end_date ?? '');
    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
    $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
    $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
    $campaignSoldCount = $product?->campaign_sold_product;
    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
    $stock_count = $stock_count > 0 ? $stock_count : 0;
    $filter = $filter ?? false;
    $product_img_url = render_image($product->image, render_type: 'url');

    $vendor_information = $product->vendor ?? '';
    $product_id = $product->id ?? 0;
@endphp

@section('content')
    <!-- Shop Details area end -->
    <section class="shop-details-area padding-top-100 padding-bottom-50">
        <div class="container container-one">
            <div class="row justify-content-center">
                <div class="col-xxl-9 col-xl-9">
                    <div class="row">
                        <div class="col-lg-8 col-xl-7">
                            <div class="shop-details-top-slider big-product-image">
                                <div class="shop-details-thumb-wrapper text-center bg-item-five product-image"
                                     data-img-src="{{ render_image($product->image, render_type: 'url', class: 'lazyloads') }}">
                                    <div class="shop-details-thums">
                                        {!! render_image($product->image, class: 'lazyloads') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="shop-details-bottom-slider-area mt-4">
                                <div class="global-slick-init shop-details-click-img dot-style-one banner-dots dot-absolute slider-inner-margin"
                                     data-infinite="true" data-slidesToShow="5" data-dots="true" data-autoplaySpeed="3000"
                                     data-autoplay="true"
                                     data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 3,"arrows": false,"dots": true}},{"breakpoint": 768, "settings": {"slidesToShow": 2} },{"breakpoint": 376, "settings": {"slidesToShow": 2} }]'>
                                    <div class="shop-details-thumb-wrapper text-center bg-item-five">
                                        <div class="shop-details-thums shop-details-thums-small">
                                            {!! render_image($product->image, class: 'lazyloads') !!}
                                        </div>
                                    </div>
                                    
                                    @foreach ($product->gallery_images ?? [] as $image)
                                        <div class="shop-details-thumb-wrapper text-center bg-item-five">
                                            <div class="shop-details-thums shop-details-thums-small">
                                                {!! render_image($image, class: 'lazyloads') !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xl-5">
                            <div class="single-shop-details-wrapper padding-left-50">
                                <h2 class="details-title">{{ $product->name }}</h2>
                                <div class="rating-wrap">
                                    {!! view('product::components.frontend.common.rating-markup', compact('product')) !!}
                                </div>

                                @if ($stock_count > 0)
                                    <span data-stock-text="{{ $stock_count }}"
                                          class="availability text-success">{{ filter_static_option_value('product_in_stock_text', $setting_text, __('In stock')) }}
                                        ({{ $stock_count }})</span>
                                @else
                                    <span data-stock-text="{{ $stock_count }}"
                                          class="availability text-danger">{{ filter_static_option_value('product_out_of_stock_text', $setting_text, __('Out of stock')) }}</span>
                                @endif

                                <div class="price-update-through mt-4">
                                    <h3 class="ff-rubik flash-prices color-one price" data-main-price="{{ $sale_price }}"
                                        data-price-percentage="{{ \Modules\TaxModule\Services\CalculateTaxServices::pricesEnteredWithTax() ? $product->tax_options_sum_rate : 0 }}"
                                        data-currency-symbol="{{ site_currency_symbol() }}" id="price">
                                        {{ amount_with_currency_symbol(calculatePrice($sale_price, $product)) }} </h3>
                                    <span class="fs-22 flash-old-prices" id="deleted_price"
                                          data-deleted-price="{{ calculatePrice($deleted_price, $product) }}">
                                        {{ amount_with_currency_symbol(calculatePrice($deleted_price, $product)) }} </span>
                                </div>

                                <div class="short-description mt-3">
                                    <p class="info">{!! purify_html($product->summary) !!}</p>
                                </div>

                                @if ($productSizes->count() > 0 && !empty(current(current($productSizes))))
                                    <div class="value-input-area margin-top-15 size_list">
                                        <span class="input-list">
                                            <strong class="color-light">{{ __('Size:') }}</strong>
                                            <input class="form--input value-size" name="size" type="text"
                                                   value="">
                                            <input type="hidden" id="selected_size">
                                        </span>

                                        <ul class="size-lists" data-type="Size">
                                            @foreach ($productSizes as $product_size)
                                                @if (!empty($product_size))
                                                    <li class="" data-value="{{ optional($product_size)->id }}"
                                                        data-display-value="{{ optional($product_size)->name }}">
                                                        {{ optional($product_size)->name }} </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($productColors->count() > 0 && current(current($productColors)))
                                    <div class="value-input-area mt-4 color_list">
                                        <span class="input-list">
                                            <strong class="color-light">{{ __('Color:') }}</strong>
                                            <input class="form--input value-size" disabled name="color" type="text"
                                                   value="">
                                            <input type="hidden" id="selected_color">
                                        </span>

                                        <ul class="size-lists color-list" data-type="Color">
                                            @foreach ($productColors as $product_color)
                                                @if (!empty($product_color))
                                                    <li style="background: {{ optional($product_color)->color_code }}"
                                                        class="radius-percent-50"
                                                        data-value="{{ optional($product_color)->id }}"
                                                        data-display-value="{{ optional($product_color)->name }}">
                                                        <span class="color-list-overlay"></span>
                                                        <span
                                                                style="background: {{ optional($product_color)->color_code }}"></span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @foreach ($available_attributes as $attribute => $options)
                                    <div class="value-input-area margin-top-15 attribute_options_list">
                                        <span class="input-list">
                                            <strong class="color-light">{{ $attribute }}</strong>
                                            <input class="form--input value-size" type="text" value="">
                                            <input type="hidden" id="selected_attribute_option"
                                                   name="selected_attribute_option">
                                        </span>

                                        <ul class="size-lists" data-type="{{ $attribute }}">
                                            @foreach ($options as $option)
                                                <li class="" data-value="{{ $option }}"
                                                    data-display-value="{{ $option }}"> {{ $option }} </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach

                                <div class="quantity-area mt-4">
                                    <div class="quantity-flex">
                                        <span class="quantity-title color-light"> {{ __('Quantity:') }} </span>
                                        <div class="product-quantity">
                                            <span class="substract">
                                                <i class="las la-minus"></i>
                                            </span>

                                            <input class="quantity-input" id="quantity" type="number"
                                                   value="01" />

                                            <span class="plus">
                                                <i class="las la-plus"></i>
                                            </span>
                                        </div>
                                        <span data-stock-text="{{ $stock_count }}"
                                              class="stock-available {{ $stock_count ? 'text-success' : 'text-danger' }}">
                                            {{ $stock_count ? "In Stock ($stock_count)" : 'Out of stock' }} </span>
                                    </div>
                                    <div class="quantity-btn margin-top-40">
                                        <div class="btn-wrapper">
                                            <a href="#1" data-id="{{ $product->id }}"
                                               class="cmn-btn btn-bg-1 radius-0 cart-loading add_to_cart_single_page">
                                                {{ __('Add to Cart') }} </a>
                                        </div>
                                        <a href="#1" data-id="{{ $product->id }}"
                                           class="heart-btn fs-32 color-one radius-0 add_to_wishlist_single_page">
                                            <i class="lar la-heart"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="wishlist-compare">
                                    <div class="wishlist-compare-btn">
                                        <a href="#1" data-id="{{ $product->id }}"
                                           class="btn-wishlist buy_now_single_page btn-details btn-buyNow mt-4"> <i
                                                    class="lar la-cart-arrow-down"></i> {{ __('Buy now') }} </a>
                                        <a href="#1" data-id="{{ $product->id }}"
                                           class="btn-wishlist add_to_compare_single_page btn-details btn-addCompare mt-4">
                                            <i class="las la-retweet"></i> {{ __('Add Compare') }} </a>
                                    </div>
                                </div>
                                <div class="shop-details-stock shop-border-top pt-4 mt-4">
                                    <div class="details-checkout-shop">
                                        <span class="guaranteed-checkout fw-500 color-light">
                                            {{ __('Guaranteed Safe Checkout') }} </span>
                                        <div class="global-slick-init payment-slider nav-style-two dot-style-one slider-inner-margin"
                                             data-infinite="true" data-arrows="true" data-dots="false"
                                             data-slidesToShow="5" data-swipeToSlide="true" data-autoplay="true"
                                             data-autoplaySpeed="2500"
                                             data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>'
                                             data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>'
                                             data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 5}},{"breakpoint": 1200,"settings": {"slidesToShow": 4}},{"breakpoint": 992,"settings": {"slidesToShow": 4,"arrows": false,"dots": true}},{"breakpoint": 768, "settings": {"slidesToShow": 2} },{"breakpoint": 576, "settings": {"slidesToShow": 2} }]'>
                                            @foreach ($paymentGateways as $gateway)
                                                <div class="slick-item">
                                                    <div class="payment-slider-item">
                                                        {!! render_image($gateway->oldImage) !!}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <ul class="stock-category mt-4">
                                        @if($product?->category)
                                            <li class="category-list">
                                                <strong> {{ __('Category:') }} </strong>
                                                <a class="list-item"
                                                   href="{{ route('frontend.products.category', $product?->category?->slug) }}">
                                                    {{ $product?->category?->name }}
                                                </a>
                                            </li>
                                        @endif
                                        @if($product?->subCategory)
                                            <li class="category-list">
                                                <strong> {{ __('Sub Category:') }} </strong>
                                                <a class="list-item"
                                                   href="{{ route('frontend.products.subcategory', $product?->subCategory?->slug) }}">
                                                    {{ $product?->subCategory?->name }}
                                                </a>
                                            </li>
                                        @endif
                                        @if($product->childCategory)
                                            <li class="category-list">
                                                <strong> {{ __('Child Category:') }} </strong>
                                                @foreach ($product?->childCategory ?? [] as $childCategory)
                                                    <a class="list-item"
                                                       href="{{ route('frontend.products.child-category', $childCategory?->slug) }}">
                                                        {{ $childCategory?->name }}
                                                    </a>
                                                @endforeach
                                            </li>
                                        @endif
                                        <li class="category-list">
                                            <strong> {{ __('Sku:') }} </strong>
                                            <label class="list-item"> {{ $product->inventory?->sku }} </label>
                                        </li>
                                    </ul>

                                    @if ($product->tag?->isNotEmpty())
                                        <div class="tags-area-shop shop-border-top pt-4 mt-4">
                                            <span class="tags-span color-light"> <strong> {{ __('Tags:') }} </strong>
                                            </span>
                                            <ul class="tags-shop-list">
                                                @foreach ($product->tag ?? [] as $tag)
                                                    <li class="list">
                                                        <a
                                                                href="{{ route('frontend.products.all', ['tag-name' => $tag->tag_name]) }}">
                                                            {{ $tag->tag_name }} </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Details tab area starts -->
                    <section class="tab-details-tab-area padding-top-50">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="details-tab-wrapper">
                                    <ul class="tabs details-tab">
                                        <li class="{{ $product->reviews_count > 0 ? '' : 'active' }} ff-rubik fw-500"
                                            data-tab="description"> {{ __('Description') }} </li>
                                        <li class="ff-rubik fw-500 {{ empty($product?->vendor) ? 'd-none' : '' }}"
                                            data-tab="information"> {{ __('Information') }} </li>
                                        <li class="{{ $product->reviews_count > 0 ? 'active' : '' }} ff-rubik fw-500"
                                            data-tab="reviews"> {{ __('Reviews') }}
                                            ({{ $product->reviews_count }}) </li>
                                    </ul>
                                    <div id="description"
                                         class="tab-content-item {{ $product->reviews_count > 0 ? '' : 'active' }}">
                                        {!! purify_html($product->description) !!}
                                    </div>
                                    <div id="information" class="tab-content-item">
                                        <div class="single-details-tab mt-2">
                                            @if ($product?->vendor?->username)
                                                <div class="tab-information">
                                                    <div class="about-seller-flex-content align-items-center">
                                                        <div class="about-seller-thumb">
                                                            <a
                                                                    href="{{ route('frontend.vendors.single', $product?->vendor?->username) }}">
                                                                {!! render_image($product?->vendor?->vendor_shop_info?->logo) !!} </a>
                                                        </div>
                                                        <div class="about-seller-content">
                                                            <h5 class="title">
                                                                <a
                                                                        href="{{ route('frontend.vendors.single', $product?->vendor?->username) }}">
                                                                    {{ $product?->vendor?->owner_name }} </a>
                                                            </h5>

                                                            <div class="rating-wrap mt-2">
                                                                <div class="rating-wrap">
                                                                    <x-product::frontend.common.rating-markup
                                                                            :avg-rattings="$product?->vendor
                                                                            ?->vendor_product_rating_avg_product_ratingsrating" :rating-count="$product?->vendor
                                                                            ?->vendor_product_rating_count" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="seller-details-box">
                                                        <ul class="seller-box-list">
                                                            <li class="box-list"> {{ __('From') }} <strong>
                                                                    {{ $product?->vendor?->vendor_address?->country?->name }}
                                                                </strong> </li>
                                                            <li class="box-list"> {{ __('About Since') }} <strong>
                                                                    {{ $product?->vendor?->created_at?->format('Y') }}
                                                                </strong> </li>
                                                        </ul>
                                                        <p class="seller-details-para">{!! $product?->vendor?->description !!}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="reviews"
                                         class="tab-content-item {{ $product->reviews_count > 0 ? 'active' : '' }} ">
                                        <div class="single-details-tab">
                                            <div class="tab-review">
                                                @forelse($product->reviews as $review)
                                                    <div class="about-seller-flex-content">
                                                        <div class="about-seller-thumb">
                                                            <a href="#1"> {!! render_image($review->user->profile_image) !!} </a>
                                                        </div>
                                                        <div class="about-seller-content">
                                                            <h5 class="title"> <a href="#1">
                                                                    {{ $review?->user?->name }} </a> </h5>
                                                            <div class="rating-wrap mt-2">
                                                                <x-product::frontend.common.rating-markup
                                                                        :avg-rattings="$product->reviews_avg_rating" />
                                                            </div>
                                                            <p class="about-review-para">
                                                                {{ $review->review_text }}
                                                            </p>
                                                            <span
                                                                    class="review-date">{{ $review->created_at->format('d F Y') }}</span>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <h3 class="title text-warning">{{ __('No review found') }}
                                                    </h3>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Shop Details tab area end -->
                </div>
                <div class="col-xxl-3 col-xl-3">
                    <div class="shop-details-right-sidebar">
                        @if ($reward ?? '' == true)
                            <div class="single-sidebar-details single-border">
                                <div class="shop-details-gift center-text">
                                    <a href="#1" class="gift-icon"> <i class="las la-gifts"></i> </a>
                                    <h5 class="reward-title fw-500"> {{ __('Reward Point: 300') }} </h5>
                                </div>
                            </div>
                        @endif
                        @if ($product->vendor)
                            <div class="single-sidebar-details single-border margin-top-40">
                                <div class="shop-details-sold center-text">
                                    <h5 class="title-sidebar-global"> {{ __('Sold By:') }} </h5>
                                    <div class="best-seller-sidebar mt-4">
                                        <a href="{{ route('frontend.vendors.single', $product->vendor->username) }}"
                                           class="thumb-brand">
                                            {!! render_image($product->vendor?->vendor_shop_info?->logo) !!}
                                        </a>
                                        <div class="best-seller-contents mt-3">
                                            <h5 class="common-title-two">
                                                <a
                                                        href="{{ route('frontend.vendors.single', $product->vendor->username) }}">
                                                    {{ $product->vendor->business_name }}
                                                </a>
                                            </h5>

                                            <div class="rating-wrap mt-2">
                                                <div class="rating-wrap">
                                                    <x-product::frontend.common.rating-markup :avg-rattings="$product->vendor
                                                        ->vendor_product_rating_avg_product_ratingsrating"
                                                                                              :rating-count="$product->vendor->vendor_product_rating_count" />
                                                </div>
                                            </div>

                                            <a href="{{ route('frontend.vendor.product', $product->vendor->username) }}"
                                               class="color-stock d-block fs-16 fw-500 mt-3">
                                                {{ $product->vendor?->product_count ?? 0 }} {{ __('Products') }}
                                            </a>

                                            <div class="sidebar-wrapper-btn">
                                                <a href="{{ route('frontend.vendors.single', $product->vendor->username) }}"
                                                   class="visit-btn btn-visit-chat visit__btn__outline mt-3">
                                                    {{ __('Visit Store') }}
                                                </a>
                                                @if(moduleExists("Chat"))
                                                    {{-- todo:: first need to check two things one is user authenticated and check live chat module is exist --}}
                                                    {{--                                                    <x-chat::live-chat-button from="product" :$product />--}}
                                                    @include("chat::components.live-chat-button", ["from" => "product", "product" => $product])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($product->productDeliveryOption->isNotEmpty())
                            <div class="single-sidebar-details single-border margin-top-40">
                                <div class="shop-details-list">
                                    <ul class="promo-list">
                                        @foreach ($product->productDeliveryOption ?? [] as $option)
                                            <li class="list">
                                                <div class="icon"> <i class="{{ $option->icon }}"></i> </div>
                                                <div class="promon-icon-contents">
                                                    <h6 class="promo-title fw-500"> {{ $option->title }} </h6>
                                                    <span class="promo-para"> {{ $option->sub_title }} </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="single-sidebar-details single-border margin-top-40">
                            <div class="shop-details-share center-text">
                                <h5 class="title-sidebar-global"> {{ __('Share:') }} </h5>
                                <ul class="share-list mt-4">
                                    {!! single_post_share(
                                        route('frontend.products.single', purify_html($product->slug)),
                                        purify_html($product->name),
                                        $product_img_url,
                                    ) !!}
                                </ul>
                            </div>
                        </div>
                        @if ($product->vendor?->product_count > 0)
                            <div class="single-sidebar-details single-border margin-top-40">
                                <div class="shop-product-slider center-text">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h5 class="title-sidebar-global text-left"> {{ __("Seller's Products") }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="global-slick-init deal-slider nav-style-two dot-style-one slider-inner-margin"
                                                 data-infinite="true" data-arrows="true" data-dots="false"
                                                 data-slidesToShow="1" data-swipeToSlide="true" data-autoplay="true"
                                                 data-autoplaySpeed="2500"
                                                 data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>'
                                                 data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>'
                                                 data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 1}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2,"arrows": false,"dots": true}},{"breakpoint": 768, "settings": {"slidesToShow": 2} },{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                                                @foreach ($product->vendor->product as $product)
                                                    <div class="slick-slider-items wow fadeInUp"
                                                         data-wow-delay=".{{ $loop->iteration }}s">
                                                        <x-product::frontend.grid-style-03 :$product />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details area end -->
    <!-- Related Products area Starts -->
    <section class="related-products-area padding-top-50 padding-bottom-100">
        <div class="container container-one">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-left section-border-bottom">
                        <div class="title-left">
                            <h2 class="title"> {{ __('Related Products') }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 mt-5">
                    <div class="global-slick-init relatedProducts-slider recent-slider nav-style-one slider-inner-margin"
                         data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="6"
                         data-swipeToSlide="true" data-autoplay="true" data-autoplaySpeed="2500"
                         data-prevArrow='<div class="prev-icon"><i class="las la-arrow-left"></i></div>'
                         data-nextArrow='<div class="next-icon"><i class="las la-arrow-right"></i></div>'
                         data-responsive='[{"breakpoint": 1800,"settings": {"slidesToShow": 5}},{"breakpoint": 1400,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 768, "settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>
                        @foreach ($related_products as $product)
                            <x-product::frontend.grid-style-03 :$product :$loop />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Related Products area end -->

    @if(moduleExists("Chat"))
        @include("chat::components.live-chat-modal", ["vendor" => $vendor_information])
    @endif
@endsection

@section('script')
    @if (!empty($vendor_information) && moduleExists("Chat"))
        @include("chat::components.frontend-js", ["id" => $product_id, "vendor" => $vendor_information])
    @endif

    <script>
        let attribute_store = JSON.parse('{!! json_encode($product_inventory_set) !!}');

        let additional_info_store = JSON.parse('{!! json_encode($additional_info_store) !!}');
        let available_options = $('.value-input-area');
        let selected_variant = '';
        let site_currency_symbol = '{{ site_currency_symbol() }}';

        $(document).on('click', '.size-lists li', function(event) {
            let el = $(this);
            let value = el.data('displayValue');
            let parentWrap = el.parent().parent();
            el.addClass('active');
            el.siblings().removeClass('active');
            parentWrap.find('input[type=text]').val(value);
            parentWrap.find('input[type=hidden]').val(el.data('value'));

            $('.size-lists li').addClass('disabled-option');

            // selected attributes
            productDetailSelectedAttributeSearch(this);
        });

        $(document).on('click', '.add_to_cart_single_page', function(e) {
            e.preventDefault();
            let selected_size = $('#selected_size').val();
            let selected_color = $('#selected_color').val();
            let site_currency_symbol = "{{ site_currency_symbol() }}";

            $(".size-lists.active")

            let pid_id = getAttributesForCart();

            let product_id = $(this).data('id');
            let quantity = Number($('#quantity').val().trim());
            let price = $('#price').text().split(site_currency_symbol)[1];
            let attributes = {};
            let product_variant = pid_id;

            attributes['price'] = price;

            // if selected attribute is a valid product item
            if (validateSelectedAttributes()) {
                $.ajax({
                    url: '{{ route('frontend.products.add.to.cart.ajax') }}',
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        pid_id: pid_id,
                        product_variant: product_variant,
                        selected_size: selected_size,
                        selected_color: selected_color,
                        attributes: attributes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.type ?? false) {
                            toastr[data.type](data.msg);
                        } else {
                            toastr.success(data.msg);
                        }

                        if (data.quantity_msg) {
                            toastr.warning(data.quantity_msg)
                        }

                        loadHeaderCardAndWishlistArea(data);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('An error occurred') }}');
                    }
                });
            } else {
                toastr.error('{{ __('Select all attribute to proceed') }}');
            }
        });

        $(document).on('click', '.buy_now_single_page', function(e) {
            e.preventDefault();
            let selected_size = $('#selected_size').val();
            let selected_color = $('#selected_color').val();
            let site_currency_symbol = "{{ site_currency_symbol() }}";

            $(".size-lists.active")

            let pid_id = getAttributesForCart();

            let product_id = $(this).data('id');
            let quantity = Number($('#qty_text').text().trim());
            let price = $('#price').text().split(site_currency_symbol)[1];
            let attributes = {};
            let product_variant = pid_id;

            attributes['price'] = price;

            // if selected attribute is a valid product item
            if (validateSelectedAttributes()) {
                $.ajax({
                    url: '{{ route('frontend.products.add.to.cart.ajax') }}',
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        pid_id: pid_id,
                        product_variant: product_variant,
                        selected_size: selected_size,
                        selected_color: selected_color,
                        attributes: attributes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.type ?? false) {
                            toastr[data.type](data.msg);
                        } else {
                            toastr.success(data.msg);
                        }

                        if (data.quantity_msg) {
                            toastr.warning(data.quantity_msg)
                        }

                        setTimeout(function() {
                            window.location.href = "{{ route('frontend.checkout') }}";
                        }, 1500);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('An error occurred') }}');
                    }
                });
            } else {
                toastr.error('{{ __('Select all attribute to proceed') }}');
            }
        });

        $(document).on('click', '.add_to_wishlist_single_page', function(e) {
            e.preventDefault();
            let selected_size = $('#selected_size').val();
            let selected_color = $('#selected_color').val();
            let site_currency_symbol = "{{ site_currency_symbol() }}";

            $(".size-lists.active")

            let pid_id = getAttributesForCart();

            let product_id = $(this).data('id');
            let quantity = Number($('#quantity').val().trim());
            let price = $('#price').text().split(site_currency_symbol)[1];
            let attributes = {};
            let product_variant = pid_id;

            attributes['price'] = price;

            // if selected attribute is a valid product item
            if (validateSelectedAttributes()) {
                $.ajax({
                    url: '{{ route('frontend.products.add.to.wishlist.ajax') }}',
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        pid_id: pid_id,
                        product_variant: product_variant,
                        selected_size: selected_size,
                        selected_color: selected_color,
                        attributes: attributes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.type ?? false) {
                            toastr[data.type](data.msg);
                        } else {
                            toastr.success(data.msg);
                        }

                        if (data.quantity_msg) {
                            toastr.warning(data.quantity_msg)
                        }

                        loadHeaderCardAndWishlistArea(data);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('An error occurred') }}');
                    }
                });
            } else {
                toastr.error('{{ __('Select all attribute to proceed') }}');
            }
        });

        $(document).on('click', '.add_to_compare_single_page', function(e) {
            e.preventDefault();
            let selected_size = $('#selected_size').val();
            let selected_color = $('#selected_color').val();
            let site_currency_symbol = "{{ site_currency_symbol() }}";

            $(".size-lists.active")

            let pid_id = getAttributesForCart();

            let product_id = $(this).data('id');
            let quantity = Number($('#quantity').val().trim());
            let price = $('#price').text().split(site_currency_symbol)[1];
            let attributes = {};
            let product_variant = pid_id;

            attributes['price'] = price;

            // if selected attribute is a valid product item
            if (validateSelectedAttributes()) {
                $.ajax({
                    url: '{{ route('frontend.products.add.to.compare') }}',
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        pid_id: pid_id,
                        product_variant: product_variant,
                        selected_size: selected_size,
                        selected_color: selected_color,
                        attributes: attributes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.type ?? false) {
                            toastr[data.type](data.msg);
                        } else {
                            toastr.success(data.msg);
                        }

                        if (data.quantity_msg) {
                            toastr.warning(data.quantity_msg)
                        }

                        loadHeaderCardAndWishlistArea(data);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('An error occurred') }}');
                    }
                });
            } else {
                toastr.error('{{ __('Select all attribute to proceed') }}');
            }
        });


        function productDetailSelectedAttributeSearch(selected_item) {
            /*
             * search based on all selected attributes
             *
             * 1. get all selected attributes in {key:value} format
             * 2. search in attribute_store for all available matches
             * 3. display available matches (keep available matches selectable, and rest as disabled)
             * */

            let available_variant_types = [];
            let selected_options = {};
            let selected_option_with_val = {};

            // get all selected attributes in {key:value} format
            available_options.map(function(k, option) {
                let selected_option = $(option).find('li.active');
                let type = selected_option.closest('.size-lists').data('type');
                let value = selected_option.data('displayValue');

                if (type) {
                    available_variant_types.push(type);
                }

                if (type && value) {
                    selected_options[type] = value;
                }
            });

            syncImage(view_selected_options());
            syncPrice(view_selected_options());
            syncStock(view_selected_options());

            // search in attribute_store for all available matches
            let available_variants_selection = [];
            let selected_attributes_by_type = {};

            attribute_store.map(function(arr) {
                let matched = true;

                Object.keys(selected_options).map(function(type) {
                    if (arr[type] != selected_options[type]) {
                        matched = false;
                    }
                })

                if (matched) {
                    available_variants_selection.push(arr);

                    // insert as {key: [value, value...]}
                    Object.keys(arr).map(function(type) {
                        // not array available for the given key
                        if (!selected_attributes_by_type[type]) {
                            selected_attributes_by_type[type] = []
                        }

                        // insert value if not inserted yet
                        if (selected_attributes_by_type[type].indexOf(arr[type]) <= -1) {
                            selected_attributes_by_type[type].push(arr[type]);
                        }
                    })
                }
            });

            // selected item doesn't contain a product then deselect all selected option hare
            if (Object.keys(selected_attributes_by_type).length == 0) {
                $('.size-lists li.active').each(function() {
                    let sizeItem = $(this).parent().parent();

                    sizeItem.find('input[type=hidden]').val('');
                    sizeItem.find('input[type=text]').val('');
                });

                $('.size-lists li.active').removeClass("active");
                $('.size-lists li.disabled-option').removeClass("disabled-option");

                let el = $(selected_item);
                let value = el.data('displayValue');

                el.addClass("active");

                $(this).find('input[type=hidden]').val(value);
                $(this).find('input[type=text]').val(el.data('value'));

                productDetailSelectedAttributeSearch();
            }

            // keep only available matches selectable
            Object.keys(selected_attributes_by_type).map(function(type) {
                // initially, disable all buttons
                $('.size-lists[data-type="' + type + '"] li').addClass('disabled-option');

                // make buttons selectable for the available options
                selected_attributes_by_type[type].map(function(value) {
                    let available_buttons = $('.size-lists[data-type="' + type +
                        '"] li[data-display-value="' + value + '"]');
                    available_buttons.map(function(key, el) {
                        $(el).removeClass('disabled-option');
                    })
                });
            });
            //  check is empty object
            // selected_attributes_by_type
        }

        function syncImage(selected_options) {
            //todo fire when attribute changed
            let hashed_key = getSelectionHash(selected_options);

            let product_image_el = $('.shop-details-thumb-wrapper.product-image img');

            let img_original_src = $('.shop-details-thumb-wrapper.product-image').attr('data-img-src');

            // if selection has any image to it
            if (additional_info_store[hashed_key]) {
                let attribute_image = additional_info_store[hashed_key].image;
                if (attribute_image) {
                    product_image_el.attr('src', attribute_image);
                } else {
                    product_image_el.attr('src', img_original_src);
                }
            } else {
                product_image_el.attr('src', img_original_src);
            }
        }

        function syncPrice(selected_options) {
            let hashed_key = getSelectionHash(selected_options);

            let product_price_el = $('#price');
            let product_main_price = Number(product_price_el.data('mainPrice')).toFixed(0);
            let tax_percentage = Number(String(product_price_el.data('price-percentage'))).toFixed(0);
            let site_currency_symbol = product_price_el.data('currencySymbol');

            // if selection has any additional price to it
            if (additional_info_store[hashed_key]) {
                let attribute_price = additional_info_store[hashed_key]['additional_price'];
                if (attribute_price) {
                    product_main_price = Number(product_main_price) + Number(attribute_price);
                    let price = calculatePercentage(product_main_price, Number(tax_percentage));

                    product_price_el.text(site_currency_symbol + (Number(price) + Number(product_main_price)));
                } else {
                    product_price_el.text(site_currency_symbol + (calculatePercentage(Number(product_main_price), Number(
                        tax_percentage)) + Number(product_main_price)));
                }
            } else {
                product_price_el.text(site_currency_symbol + (calculatePercentage(Number(product_main_price), Number(
                    tax_percentage)) + Number(product_main_price)));
            }
        }

        function syncStock(selected_options) {
            let hashed_key = getSelectionHash(selected_options);
            let product_stock_el = $('.availability');
            let product_item_left_el = $('.stock-available');

            // if selection has any size and color to it

            if (additional_info_store[hashed_key]) {
                let stock_count = additional_info_store[hashed_key]['stock_count'];

                let stock_message = '';
                if (Number(stock_count) > 0) {
                    stock_message = `<span class="text-success">{{ __('In Stock') }}</span>`;
                    product_item_left_el.text(`Only! ${stock_count} Item Left!`);
                    product_item_left_el.addClass('text-success');
                    product_item_left_el.removeClass('text-danger');
                } else {
                    stock_message = `<span class="text-danger">{{ __('Our fo Stock') }}</span>`;
                    product_item_left_el.text(`No Item Left!`);
                    product_item_left_el.addClass('text-danger');
                    product_item_left_el.removeClass('text-success');
                }

                product_stock_el.html(stock_message);

            } else {
                product_stock_el.html(product_stock_el.data("stock-text"))
                product_item_left_el.html(product_item_left_el.data("stock-text"))
            }
        }

        function attributeSelected() {
            let total_options_count = $('.size-lists').length;
            let selected_options_count = $('.size-lists li.active').length;
            return total_options_count === selected_options_count;
        }

        function view_selected_options() {
            let selected_options = {};
            let available_options = $('.value-input-area');
            // get all selected attributes in {key:value} format
            available_options.map(function(k, option) {
                let selected_option = $(option).find('li.active');
                let type = selected_option.closest('.size-lists').data('type');
                let value = selected_option.data('displayValue');

                if (type && value) {
                    selected_options[type] = value;
                }
            });

            let ordered_data = {};
            let selected_options_keys = Object.keys(selected_options).sort();

            selected_options_keys.map(function(e) {
                ordered_data[e] = String(selected_options[e]);
            });

            console.log("====OrderData====", ordered_data);

            return ordered_data;
        }

        function getAttributesForCart() {
            let selected_options = view_selected_options();
            let cart_selected_options = selected_options;
            let hashed_key = getSelectionHash(selected_options);

            // if selected attribute set is available
            if (additional_info_store[hashed_key]) {
                console.log('additional_info_store', additional_info_store);

                return additional_info_store[hashed_key]['pid_id'];
            }


            // if selected attribute set is not available
            if (Object.keys(selected_options).length) {
                toastr.error('{{ __('Attribute not available') }}')
            }

            return '';
        }

        function send_ajax_response_get_response(type, url) {
            $.ajax({
                url: url,
                type: type,
                data: {
                    style: "two",
                    limit: $(".product-filter-two-wrapper").data("item-limit")
                },
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $(".product-filter-two-wrapper").attr("style", "height:912px");
                    $(".filter-style-block-preloader.lds-ellipsis").show();
                },
                success: function(data) {
                    $(".filter-style-block-preloader.lds-ellipsis").hide(300);
                    $(".product-filter-two-wrapper").removeAttr("style");
                    $(".product-filter-two-wrapper").html(data).removeAttr("style");

                    if (data.success == false) {
                        toastr.warning('There something is wrong please try again');
                    }
                },
                erorr: function(err) {
                    $(".product-filter-two-wrapper").removeAttr("style");
                    $(".filter-style-block-preloader.lds-ellipsis").hide(300);
                    toastr.error('{{ __('An error occurred') }}');
                }
            });
        }

        function validateSelectedAttributes() {
            let selected_options = view_selected_options();
            let hashed_key = getSelectionHash(selected_options);

            // validate if product has any attribute
            if (attribute_store.length) {
                if (!Object.keys(selected_options).length) {
                    return false;
                }

                if (!additional_info_store[hashed_key]) {
                    return false;
                }

                return !!additional_info_store[hashed_key]['pid_id'];
            }

            return true;
        }

        function getSelectionHash(selected_options) {
            return MD5(JSON.stringify(selected_options));
        }
    </script>
@endsection