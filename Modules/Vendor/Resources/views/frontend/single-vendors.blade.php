@extends('frontend.frontend-page-master')

@section('page-title', __('Vendor Page'))
@section('title', __('Vendor Page'))

@section('style')
    <style>
        /* Vendor Banner Css */
        .vendor-banner-area {
            padding: 120px 0;
            position: relative;
        }

        .vendor-banner-area::before {
            content: "";
            position: absolute;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .vendor-banner-contents {
            position: relative;
        }

        .vendor-banner-contents-title {
            font-size: 70px;
            font-weight: 500;
            line-height: 1.2;
            color: #fff;
            margin: -10px 0 0;
        }

        @media (min-width: 1200px) and (max-width: 1399.98px) {
            .vendor-banner-contents-title {
                font-size: 64px;
            }
        }

        @media (min-width: 992px) and (max-width: 1199.98px) {
            .vendor-banner-contents-title {
                font-size: 56px;
            }
        }

        @media (min-width: 300px) and (max-width: 991.98px) {
            .vendor-banner-contents-title {
                font-size: 48px;
            }
        }

        @media only screen and (max-width: 767.98px) {
            .vendor-banner-contents-title {
                font-size: 42px;
            }
        }

        @media only screen and (max-width: 575.98px) {
            .vendor-banner-contents-title {
                font-size: 36px;
            }
        }

        @media only screen and (max-width: 480px) {
            .vendor-banner-contents-title {
                font-size: 30px;
            }
        }

        @media only screen and (max-width: 375px) {
            .vendor-banner-contents-title {
                font-size: 28px;
            }
        }

        /* Vendor SuperMarket Css */
        .vendor-superMarker-area {
            position: relative;
        }

        .vendor-superMarket-shape img {
            max-width: 120px;
        }

        @media (min-width: 300px) and (max-width: 991.98px) {
            .vendor-superMarket-shape img {
                max-width: 100px;
            }
        }

        @media only screen and (max-width: 575.98px) {
            .vendor-superMarket-shape img {
                max-width: 80px;
            }
        }

        .vendor-superMarket-shape img:nth-child(1) {
            position: absolute;
            left: 16%;
            top: -60px;
        }

        @media (min-width: 300px) and (max-width: 991.98px) {
            .vendor-superMarket-shape img:nth-child(1) {
                top: -50px;
            }
        }

        @media only screen and (max-width: 575.98px) {
            .vendor-superMarket-shape img:nth-child(1) {
                top: -40px;
            }
        }

        .vendor-superMarket-flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            gap: 20px 24px;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .vendor-superMarket-title {
            font-size: 46px;
            line-height: 1.2;
            font-weight: 500;
            color: var(--heading-color);
        }

        @media (min-width: 300px) and (max-width: 991.98px) {
            .vendor-superMarket-title {
                font-size: 36px;
            }
        }

        @media only screen and (max-width: 480px) {
            .vendor-superMarket-title {
                font-size: 32px;
            }
        }

        @media only screen and (max-width: 375px) {
            .vendor-superMarket-title {
                font-size: 28px;
            }
        }

        .vendor-superMarket-para {
            font-size: 18px;
            line-height: 26px;
            font-weight: 400;
            color: var(--light-color);
            -webkit-hyphens: none;
            -ms-hyphens: none;
            hyphens: none;
            -webkit-line-clamp: unset;
        }

        @media only screen and (max-width: 767.98px) {
            .vendor-superMarket-para {
                font-size: 16px;
            }
        }

        .vendor-superMarket-contents {
            max-width: 600px;
        }

        @media (min-width: 300px) and (max-width: 991.98px) {
            .vendor-superMarket-contents {
                width: 100%;
            }
        }

        .vendor-superMarket-contents-contact-item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            gap: 12px;
            font-size: 16px;
            font-weight: 400;
            color: var(--light-color);
        }

        .vendor-superMarket-contents-contact-item:not(:last-child) {
            margin-bottom: 10px;
        }

        /* vendor Section title */
        .section-title .title .title-color {
            color: var(--main-color-two);
        }

        /* vendor Global card */
        .vendor-global-card-item {
            border: 1px solid #EDEDED;
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        .vendor-global-card-item .global-card-thumb {
            background-color: #F9F9F9;
            padding: 20px;
            height: 240px;
            width: 100%;
            text-align: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .vendor-global-card-item .global-card-thumb img {
            margin-inline: auto;
            width: auto;
            max-height: 190px;
            max-width: 100%;
        }

        .vendor-global-card-item .common-title {
            font-size: 18px;
            font-weight: 500;
            line-height: 24px;
            color: var(--heading-color);
        }

        .vendor-global-card-item .stock-available {
            font-weight: 400;
            color: var(--light-color);
        }

        .vendor-global-card-item .btn-wrapper .btn-outline-two {
            border-width: 1px !important;
            padding-inline: 15px !important;
            line-height: 24px;
            font-weight: 400;
        }

        .price-btn-flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            gap: 15px 0;
        }

        .thumb-top-rated {
            position: absolute;
            top: 20px;
            left: 0px;
            z-index: 9;
            display: block;
        }

        .thumb-top-rated.right-side {
            left: auto;
            right: 0px;
        }

        .thumb-top-rated.right-side .thumb-top-rated-item {
            border-radius: 5px 0 0 5px;
        }

        .thumb-top-rated-item {
            display: block;
            font-weight: 500;
            font-size: 14px;
            line-height: 20px;
            padding: 5px 15px;
            color: #fff;
            background-color: var(--main-color-one);
            border-radius: 0 5px 5px 0;
        }

        .thumb-top-rated-item:not(:last-child) {
            margin-bottom: 10px;
        }

        .thumb-top-rated-item.bg-two {
            background-color: var(--main-color-two);
        }

        .thumb-top-rated-item.bg-three {
            background-color: var(--main-color-three);
        }

        .vendor-product-isotope {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            gap: 12px;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .vendor-product-isotope.isootope-button {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .vendor-product-isotope.isootope-button .list {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 7px;
        }

        .vendor-product-isotope.isootope-button .list:not(:last-child) {
            margin-right: 0;
        }

        .vendor-product-isotope.isootope-button .list.active {
            color: unset;
        }

        .vendor-product-isotope.isootope-button .list::before {
            background-color: unset;
        }

        .vendor-product-isotope .list {
            display: inline-block;
            padding: 5px 12px;
            font-size: 16px;
            line-height: 20px;
            font-weight: 300;
            color: var(--light-color);
            border: 1px solid var(--extra-light-color);
            border-radius: 10px;
            cursor: pointer;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .vendor-product-isotope .list::before {
            background-color: unset !important;
        }

        .vendor-product-isotope .list.active,
        .vendor-product-isotope .list:hover {
            background-color: var(--main-color-two);
            border-color: var(--main-color-two);
            color: #fff !important;
        }

        .vendor-product-isotope .list.active img,
        .vendor-product-isotope .list:hover img {
            -webkit-filter: invert(97%) sepia(93%) saturate(29%) hue-rotate(24deg) brightness(107%) contrast(107%);
            filter: invert(97%) sepia(93%) saturate(29%) hue-rotate(24deg) brightness(107%) contrast(107%);
        }

        .vendor-product-isotope .list img {
            margin: -4px 0 0;
        }

        .append-popularProduct {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            gap: 12px;
        }

        .append-popularProduct .prev-icon,
        .append-popularProduct .next-icon {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 40px;
            width: 40px;
            background-color: rgba(var(--main-color-two-rgb), 0.6);
            color: #fff;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        .append-popularProduct .prev-icon:hover,
        .append-popularProduct .next-icon:hover {
            background-color: var(--main-color-two);
        }

        .product-countdown {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            gap: 5px 12px;
        }

        .product-countdown-para {
            font-size: 16px;
            line-height: 24px;
            font-weight: 400;
            color: var(--heading-color);
        }
    </style>
@endsection

@section('content')
    <!-- Vendor Banner area Starts -->
    <div class="vendor-banner-area" style="{{ render_image($vendor->cover_photo, size: 'full', render_type: 'bg') }};">
        <div class="container">
            <div class="vendor-banner-contents center-text">
                <h2 class="vendor-banner-contents-title">{{ __('Welcome to') }} {{ $vendor->business_name }}</h2>
            </div>
        </div>
    </div>
    <!-- Vendor Banner area end -->

    <!-- Vendor supermarket area start -->
    <section class="vendor-superMarker-area padding-top-100 padding-bottom-50">
        <div class="vendor-superMarket-shape">
            {!! render_image($vendor->logo) !!}
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="vendor-superMarket">
                        <div class="vendor-superMarket-flex">
                            <div class="vendor-superMarket-contents">
                                <h4 class="vendor-superMarket-title">{{ $vendor->business_name }}</h4>
                                @if ($vendor->vendor_product_rating_count > 0)
                                    <div class="rating-wrap mt-2">
                                        <div class="ratings">
                                            <span class="hide-rating"></span>
                                            <span class="show-rating"
                                                style="width: {{ ($vendor->vendor_product_rating_avg_product_ratingsrating ?? 0) * 20 }}"></span>
                                        </div>
                                        <p> <span class="total-ratings">({{ $vendor->vendor_product_rating_count }}+
                                                Review)</span></p>
                                    </div>
                                @endif

                                <p class="vendor-superMarket-para mt-3">
                                    {{ $vendor->description }}
                                </p>
                            </div>
                            <div class="btn-wrapper">
                                <a href="{{ route('frontend.vendor.product', $vendor->username) }}"
                                    class="cmn-btn btn-bg-2 btn-small">{{ $vendor->product_count }}
                                    {{ __('Products are available') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vendor supermarket area end -->

    <!-- Vendor Popular Product area Starts -->
    <section class="vendor-popular-product-area padding-top-50 padding-bottom-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-left">
                        <h2 class="title">{{ __('Our Popular') }} <span class="title-color">{{ __('Product') }}</span>
                        </h2>
                        <div class="append-popularProduct"></div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="global-slick-init slider-inner-margin" data-appendArrows=".append-popularProduct"
                        data-infinite="true" data-arrows="true" data-dots="false" data-slidesToShow="4"
                        data-swipeToSlide="true" data-autoplay="false" data-autoplaySpeed="2500"
                        data-prevArrow='<div class="prev-icon"><i class="las la-angle-left"></i></div>'
                        data-nextArrow='<div class="next-icon"><i class="las la-angle-right"></i></div>'
                        data-responsive='[{"breakpoint": 1600,"settings": {"slidesToShow": 4}},{"breakpoint": 1200,"settings": {"slidesToShow": 3}},{"breakpoint": 992,"settings": {"slidesToShow": 2}},{"breakpoint": 576, "settings": {"slidesToShow": 1} }]'>

                        @foreach ($ourPopularProducts as $product)
                            <x-product::frontend.grid-style-04 :$product :$loop />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Vendor Popular Porduct area end -->

    <!-- Vendor All Product area Start -->
    <section class="vendor-all-product-area padding-top-50 padding-bottom-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h2 class="title">{{ __('Our All') }} <span class="title-color">{{ __('Product') }}</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-list mt-4">
                        <ul class="isootope-button vendor-product-isotope">
                            <li class="list active" data-filter="*">
                                {{ __("All Product") }}
                            </li>
                            @foreach($ourAllProducts as $category)
                                <li class="list" data-filter=".{{ $category->slug }}">
                                    {{ $category->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="imageloaded">
                <div class="row grid mt-4">
                    @foreach ($ourAllProducts as $category)
                        @foreach ($category->product as $product)
                            <div class="col-xxl-3 col-lg-4 col-md-6 mt-4 grid-item {{ $category->slug }} wow fadeInUp"
                                data-wow-delay=".{{ $loop->iteration }}s">
                                @php
                                    $campaign_product = $product->campaign_product ?? null;
                                    $campaignProductEndDate = $product->campaign->end_date ?? ($product->campaign->end_date->end_date ?? '');
                                    $sale_price = $campaign_product ? optional($campaign_product)->campaign_price : $product->sale_price;
                                    $deleted_price = !is_null($campaign_product) ? $product->sale_price : $product->price;
                                    $campaign_percentage = !is_null($campaign_product) ? getPercentage($product->sale_price, $sale_price) : false;
                                    $campaignSoldCount = $product?->campaign_sold_product;
                                    $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
                                    $stock_count = $stock_count > 0 ? $stock_count : 0;
                                    $rating_width = round(($product->ratings_avg_rating ?? 0) * 20);
                                @endphp

                                <div class="global-card-item vendor-global-card-item radius-10">
                                    <div class="global-card-thumb radius-10">
                                        <a href="#1">
                                            {!! render_image($product->image) !!}
                                        </a>
                                        <ul class="global-thumb-icons hover-color-two">
                                            <x-product::frontend.common.link-option :$product />
                                        </ul>
                                    </div>
                                    <div class="global-card-contents">
                                        <h4 class="common-title">
                                            <a href="{{ route('frontend.products.single', $product->slug) }}">
                                                {{ $product->name }}
                                            </a>
                                        </h4>

                                        <div class="d-flex flex-wrap justify-content-between">
                                            <div class="stock mt-2">
                                                <span
                                                    class="stock-available {{ $stock_count ? 'text-success' : 'text-danger' }}">
                                                    {{ $stock_count ? "In Stock ($stock_count)" : 'Out of stock' }} </span>
                                            </div>

                                            <div class="rating-wrap mt-2">
                                                <div class="rating-wrap">
                                                    <x-product::frontend.common.rating-markup :rating-count="$product->ratings_count"
                                                        :avg-rattings="$product->ratings_avg_rating ?? 0" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-btn-flex mt-2">
                                            <div class="price-update-through">
                                                <span class="fs-24 fw-500 flash-prices color-two">
                                                    {{ float_amount_with_currency_symbol($sale_price) }} </span>
                                                <span class="fs-18 flash-old-prices">
                                                    {{ float_amount_with_currency_symbol($deleted_price) }} </span>
                                            </div>
                                            <div class="btn-wrapper">
                                                @if ($product?->inventory_detail_count > 0)
                                                    <a href="javacript:void(0)"
                                                        data-action-route="{{ route('frontend.products.single-quick-view', $product->slug) }}"
                                                        data-attributes="{{ $product->attributes }}"
                                                        data-id="{{ $product->id }}"
                                                        class="cmn-btn btn-outline-two color-two btn-small product-quick-view-ajax">{{ __('Buy Now') }}</a>
                                                @else
                                                    <a href="javacript:void(0)" data-id="{{ $product->id }}"
                                                        class="cmn-btn btn-outline-two color-two btn-small add_to_buy_now_ajax">{{ __('Buy Now') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Vendor All Product area end -->

    @if (!$vendorCampaigns->isEmpty())
        <!-- Vendor Product Campaign area Start -->
        <section class="vendor-campaing-area padding-bottom-50 padding-top-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-countdown">
                            <div class="section-title text-left">
                                <h2 class="title">{{ __('Our') }} <span
                                        class="title-color">{{ __('Campaigns') }}</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    @foreach ($vendorCampaigns as $campaign)
                        <div class="col-xl-4 col-lg-4 col-sm-6 mt-4 campaign-counter"
                            data-date="{{ $campaign->end_date->format('Y-m-d h:i:s') }}">
                            <div class="global-card-item center-text radius-10">
                                <div class="global-card-thumb radius-10">
                                    <a href="{{ route('frontend.products.campaign', $campaign->slug) }}">
                                        {!! render_image($campaign->campaignImage) !!}
                                    </a>
                                </div>
                                <div class="global-card-contents">
                                    <div class="campaign-countdown">
                                        <div><span class="counter-days"></span> d</div>
                                        <div><span class="counter-hours"></span> h</div>
                                        <div><span class="counter-minutes"></span> m</div>
                                        <div><span class="counter-seconds"></span> s</div>
                                    </div>
                                    <h4 class="common-title-two mt-3"> <a href="#1"> {{ $campaign->title }} </a>
                                    </h4>
                                    <p class="common-para mt-1">
                                        {{ $campaign->subtitle }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Vendor Product Campaign area end -->
    @endif
    <!-- back to top area start -->
    <div class="back-to-top bg-color-two">
        <span class="back-top"> <i class="las la-angle-up"></i> </span>
    </div>
    <!-- back to top area end -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            loopcounter('campaign-counter');
        });
    </script>
@endsection
