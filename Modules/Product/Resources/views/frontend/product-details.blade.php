@extends('frontend.frontend-page-master')
@section('page-title')
    {{ $product->title }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/common/css/font-awesome.min.css') }}">
@endsection

@section('content')
    @php
        $product_img_url = null;
        $product_image = get_attachment_image_by_id($product->image->id, 'full', false);
        $product_img_url = !empty($product_image) ? $product_image['img_url'] : '';
        $sidebar_status = get_static_option('sidebar_status');
        $sidebar_position = get_static_option('sidebar_position');

        $campaign_product = getCampaignProductById($product->id);
        $sale_price = $campaign_product ? $campaign_product->campaign_price : $product->sale_price;
        $deleted_price = $campaign_product ? $product->sale_price : $product->price;
        $campaign_percentage = $campaign_product ? getPercentage($product->sale_price, $sale_price) : false;
        $campaignSoldCount = \Modules\Campaign\Entities\CampaignSoldProduct::where('product_id', $product->id)->first();

        //todo remove it if manage it from inventory from listener
        $stock_count = $campaign_product ? $campaign_product->units_for_sale - optional($campaignSoldCount)->sold_count ?? 0 : optional($product->inventory)->stock_count;
        $stock_count = $stock_count > 0 ? $stock_count : 0;
        if ($campaign_product) {
            $campaign_title = \Modules\Campaign\Entities\Campaign::select('id', 'title')
                ->where('id', $campaign_product->campaign_id)
                ->first();
        }
    @endphp
    <div class="shop-details-area-wrapper">
        <div class="container">
            <div class="row g-4 @if (is_null($sidebar_status) && $sidebar_position == 'right') flex-row-reverse @endif">
                @if (!empty($sidebar_status))
                    <div class="col-md-4 col-lg-3">
                        {{--                        @include('frontend.partials.product.product-filter-sidebar') --}}
                    </div>
                @endif

                <div class="@if (is_null($sidebar_status)) col-md-12 @else col-md-8 col-lg-9 @endif">
                    <div class="product_details">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="product-view-wrap">
                                    <div class="shop-details-gallery-slider" id="shop_details_gallery_slider">
                                        <div class="single-main-image">
                                            <div class="product-view-tags">
                                                @if (!empty($product->badge))
                                                    <span class="badge-tag">{{ $product->badge }}</span>
                                                @endif
                                                @if (!empty($campaign_percentage))
                                                    <span class="discount-tag">{{ round($campaign_percentage, 0) }}%</span>
                                                @endif
                                            </div>
                                            <a href="#1" class="long-img">
                                                <figure class="zoom zoom-js-handle"
                                                    data-src="{{ render_image($product->image, render_type: 'url') }}">
                                                    <div class="product-view-tags">
                                                        @if (!empty($product->badge))
                                                            <span class="badge-tag">{{ $product->badge }}</span>
                                                        @endif
                                                        @if (!empty($campaign_percentage))
                                                            <span
                                                                class="discount-tag">{{ round($campaign_percentage, 0) }}%</span>
                                                        @endif
                                                    </div>
                                                    {!! render_image($product->image, 'full') !!}
                                                </figure>
                                            </a>
                                        </div>
                                        @php
                                            $product_image_gallery = $product->product_image_gallery && $product->product_image_gallery != 'null' ? $product->product_image_gallery : [];
                                        @endphp
                                        @if ($product_image_gallery && count($product_image_gallery))
                                            @foreach ($product_image_gallery as $gallery_image)
                                                <div class="single-main-image">

                                                    <div class="product-view-tags">
                                                        @if (!empty($product->badge))
                                                            <span class="badge-tag">{{ $product->badge }}</span>
                                                        @endif
                                                        @if (!empty($campaign_percentage))
                                                            <span
                                                                class="discount-tag">{{ round($campaign_percentage, 0) }}%</span>
                                                        @endif
                                                    </div>
                                                    <a href="#1" class="long-img">
                                                        <figure class="zoom zoom-js-handle"
                                                            data-src="{{ render_image($gallery_image, 'full', render_type: 'url') }}">
                                                            {!! render_image($gallery_image, 'full') !!}
                                                        </figure>
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="thumb-wrap">
                                        <ul class="shop-details-gallery-nav" id="shop_details_gallery_nav">
                                            <li class="single-thumb">
                                                <a class="thumb-link individual_thumb_image_wrap" data-bs-toggle="tab"
                                                    href="#image-01">
                                                    {!! render_image($product->image, 'full') !!}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 pl-4">
                                @if (!empty($campaign_product))
                                    <div class="flash-countdown-wrapper">
                                        <div class="flash-countdown-title-single">
                                            <h6 class="flash-countdown-title">{{ $campaign_title->title }}</h6>
                                        </div>
                                        <div class="flash-countdown-product"
                                            data-date="{{ optional($campaign_product)->end_date }}">
                                            <div class="single-box">
                                                <span class="counter-days item"></span>
                                                <span class="label item">{{ __('D') }}</span>
                                            </div>
                                            <div class="single-box">
                                                <span class="counter-hours item"></span>
                                                <span class="label item">{{ __('H') }}</span>
                                            </div>
                                            <div class="single-box">
                                                <span class="counter-minutes item"></span>
                                                <span class="label item">{{ __('M') }}</span>
                                            </div>
                                            <div class="single-box">
                                                <span class="counter-seconds item"></span>
                                                <span class="label item">{{ __('S') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="product-summery">

                                    <span class="product-meta">
                                        {{ $product->uom?->quantity }} {{ $product->uom?->unit?->name }}
                                    </span>

                                    <h3 class="product-title">{{ $product->title }}</h3>

                                    @if ($stock_count > 0)
                                        <span
                                            class="availability">{{ filter_static_option_value('product_in_stock_text', $setting_text, __('In stock')) }}
                                            ({{ $stock_count }})</span>
                                    @else
                                        <span
                                            class="availability text-danger">{{ filter_static_option_value('product_out_of_stock_text', $setting_text, __('Out of stock')) }}</span>
                                    @endif

                                    @if ($product->ratingCount() > 0)
                                        <div class="rating-wrap">
                                            <div class="ratings">
                                                {!! ratingMarkup($product->ratingAvg(), $product->ratingCount(), false) !!}
                                            </div>
                                            <p class="total-ratings">({{ $product->ratingCount() }})</p>
                                        </div>
                                    @endif

                                    <div class="short-description mt-3">
                                        <p class="info">{!! $product->summary !!}</p>
                                    </div>

                                    @if ($product->attributes && $product->attributes != 'null')
                                        @php $product_attributes = decodeProductAttributes($product->attributes); @endphp
                                        @foreach ($product_attributes as $attribute)
                                            <div class="size section attribute_row">
                                                <span class="name">{{ $attribute['name'] }}</span>
                                                <div class="checkbox-color ">
                                                    @foreach ($attribute['terms'] as $term)
                                                        <div class="single-checkbox-wrap attribute">
                                                            <label>
                                                                <input type="radio" name="attr_{{ $attribute['name'] }}"
                                                                    data-attr="{{ json_encode($term) }}" class="checkbox">
                                                                <span data-name="{{ $attribute['name'] }}"
                                                                    data-extra="{{ $term['additional_price'] }}"
                                                                    class="size-code">
                                                                    {{ $term['name'] }} @if (isset($term['additional_price']) && $term['additional_price'] > 0)
                                                                        (+{{ float_amount_with_currency_symbol($term['additional_price']) }})
                                                                    @endif
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="price-wrap">
                                        <span class="price" data-main-price="{{ $sale_price }}"
                                            data-currency-symbol="{{ site_currency_symbol() }}" id="price">
                                            {{ float_amount_with_currency_symbol($sale_price) }}
                                        </span>
                                        <del
                                            class="del-price">{{ float_amount_with_currency_symbol($deleted_price) }}</del>
                                    </div>

                                    {{--  @if (!empty($available_attributes)) --}}
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
                                        <div class="value-input-area margin-top-15 color_list">
                                            <span class="input-list">
                                                <strong class="color-light">{{ __('Color:') }}</strong>
                                                <input class="form--input value-size" name="color" type="text"
                                                    value="">
                                                <input type="hidden" id="selected_color">
                                            </span>
                                            <ul class="size-lists color-list" data-type="Color">
                                                @foreach ($productColors as $product_color)
                                                    @if (!empty($product_color))
                                                        <li class="radius-percent-50 {{-- @if ($loop->first) active @endif --}} "
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
                                                        data-display-value="{{ $option }}"> {{ $option }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                    {{--                                    @endif --}}

                                    <div class="user-select-option">
                                        <div class="cart-control">
                                            <div class="value-button minus decrease"><i class="las la-minus"></i></div>
                                            <input type="text" name="quantity" id="quantity" class="qty_"
                                                value="1">
                                            <div class="value-button plus increase"><i class="las la-plus"></i></div>
                                        </div>
                                        <div class="btn-and-fav">
                                            @if ($stock_count)
                                                <div class="btn-wrapper">
                                                    <a href="#1"
                                                        class="btn-default rounded-btn add_to_cart_single_page"
                                                        data-id="{{ $product->id }}">
                                                        {{ filter_static_option_value('add_to_cart_text', $setting_text, __('add to cart')) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($stock_count)
                                        <div class="btn-wrapper btn-and-fav d-flex flex-wrap mb-4">
                                            <a href="#1" data-id="{{ $product->id }}"
                                                class="cart buy_now btn-default rounded-btn">{{ __('Buy Now') }}</a>

                                            <div class="favorite add_to_wishlist" data-id="{{ $product->id }}">
                                                <a href="#1">
                                                    <i class="lar la-heart icon"></i>
                                                </a>
                                            </div>
                                            <div class="favorite add_to_compare_ajax" data-id="{{ $product->id }}">
                                                <a href="#1">
                                                    <i class="las la-retweet icon"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($product->category && $product->category->id)
                                        <div class="category">
                                            <a
                                                href="{{ route('frontend.products.category', [
                                                    'id' => optional($product->category)->id,
                                                    'slug' => \Str::slug(optional($product->category)->title ?? ''),
                                                ]) }}">
                                                {{ optional($product->category)->title }}
                                            </a>
                                        </div>
                                    @endif

                                    <div class="product-details-tag-and-social-link">
                                        @if ($product->tags && $product->tags->count())
                                            <div class="tag">
                                                <p class="name">{{ __('tags') }}:</p>
                                                @foreach ($product->tags as $tag)
                                                    <a href="{{ route('frontend.products.all', ['t' => $tag->tag]) }}"
                                                        class="tag-btn">{{ $tag->tag }}</a>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="social-link-wrap">
                                            <p class="name">{{ __('share') }}:</p>
                                            <div class="social-link">
                                                <ul class="social-link-list">
                                                    {!! single_post_share(
                                                        route('frontend.products.single', purify_html($product->slug)),
                                                        purify_html($product->title),
                                                        $product_img_url,
                                                    ) !!}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-details-tab">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                                href="#nav-home" role="tab" aria-controls="nav-home"
                                                aria-selected="true">
                                                {{ filter_static_option_value('description_tab_text', $setting_text, __('Description')) }}
                                            </a>
                                            @if ($product->additionalInfo && $product->additionalInfo->count())
                                                <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                                    href="#nav-profile" role="tab" aria-controls="nav-profile"
                                                    aria-selected="false">
                                                    {{ filter_static_option_value('additional_information_text', $setting_text, __('Additional information')) }}
                                                </a>
                                            @endif
                                            <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                                href="#nav-contact" role="tab" aria-controls="nav-contact"
                                                aria-selected="false">
                                                {{ filter_static_option_value('reviews_text', $setting_text, __('Reviews')) }}
                                            </a>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                            aria-labelledby="nav-home-tab">
                                            <div class="description">
                                                <p class="info">{!! $product->description !!}</p>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                            aria-labelledby="nav-profile-tab">
                                            <div class="aditional-info">
                                                <div class="product-info">
                                                    <ul class="product-info-list">
                                                        @if ($product->additionalInfo && $product->additionalInfo->count())
                                                            @foreach ($product->additionalInfo as $additionalInfo)
                                                                <li>
                                                                    <span class="spn-1">
                                                                        {{ optional($additionalInfo)->title }} :
                                                                    </span>
                                                                    <span class="spn-2">
                                                                        {{ optional($additionalInfo)->text }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                            aria-labelledby="nav-contact-tab">
                                            <div class="feedback-section">
                                                <div class="feedback">
                                                    @if (auth()->check())
                                                        @if ($user_has_item && $user_rated_already)
                                                            <div class="ratings select-ratings">
                                                                <p>{{ __('Your rating') }} <span class="required">*</span>
                                                                </p>
                                                                <a href="#1">
                                                                    <i data-rating="1" class="lar la-star icon"></i>
                                                                    <i data-rating="2" class="lar la-star icon"></i>
                                                                    <i data-rating="3" class="lar la-star icon"></i>
                                                                    <i data-rating="4" class="lar la-star icon"></i>
                                                                    <i data-rating="5" class="lar la-star icon"></i>
                                                                </a>
                                                            </div>
                                                            <div class="feedback-form">
                                                                <form method="POST"
                                                                    action="{{ route('frontend.products.ratings.store') }}">
                                                                    @csrf
                                                                    <input name="id" value="{{ $product->id }}"
                                                                        type="hidden">
                                                                    <input value="" name="rating"
                                                                        id="rating-number" type="hidden" />

                                                                    <div class="form-group">
                                                                        <label for="comment">
                                                                            {{ filter_static_option_value('your_reviews_text', $setting_text, __('Your review')) }}
                                                                            &nbsp;
                                                                            <span class="required">*</span>
                                                                        </label>
                                                                        <textarea class="form-control" id="comment" name="comment" required=""
                                                                            placeholder="{{ filter_static_option_value('write_your_feedback_text', $setting_text, __('Write your feedback here')) }}"></textarea>
                                                                    </div>
                                                                    <div class="btn-wrapper">
                                                                        <button type="submit"
                                                                            class="btn-default rounded-btn">
                                                                            {{ filter_static_option_value('post_your_feedback_text', $setting_text, __('Post your feedback')) }}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="row">
                                                            <div class="col-sm-6 ">
                                                                <form action="{{ route('user.login') }}" method="post"
                                                                    class="register-form" id="login_form_order_page">
                                                                    @csrf
                                                                    <div class="error-wrap"></div>

                                                                    <div class="row">
                                                                        <div class="form-group col-12">
                                                                            <label
                                                                                for="login_email">{{ __('Email or User Name') }}
                                                                                <span class="ex">*</span></label>
                                                                            <input class="form-control" type="text"
                                                                                name="username" id="login_email"
                                                                                required />
                                                                        </div>
                                                                        <div class="form-group col-12">
                                                                            <label
                                                                                for="login_password">{{ __('Password') }}
                                                                                <span class="ex">*</span></label>
                                                                            <input class="form-control" type="password"
                                                                                name="password" id="login_password"
                                                                                required />
                                                                        </div>
                                                                        <div class="form-group form-check col-12">
                                                                            <input type="checkbox" name="remember"
                                                                                class="form-check-input"
                                                                                id="login_remember">
                                                                            <label class="form-check-label"
                                                                                for="remember">{{ __('Remember me') }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="btn-pair">
                                                                        <div class="btn-wrapper">
                                                                            <button type="button" class="default-btn"
                                                                                id="login_btn">{{ __('SIGN IN') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="client-feedback">
                                                        <ul class="comment-list">
                                                            @forelse ($ratings as $rating)
                                                                <li>
                                                                    <div class="single-comment-wrap">
                                                                        @if (strlen(optional($rating->user)->image))
                                                                            <div class="thumb">
                                                                                {!! render_image_markup_by_attachment_id(optional($rating->user)->image, '', 'grid') !!}
                                                                            </div>
                                                                        @endif
                                                                        <div class="content">
                                                                            <div class="content-top">
                                                                                <div class="left">
                                                                                    <h4 class="title">
                                                                                        {{ optional($rating->user)->name }}
                                                                                    </h4>
                                                                                    @for ($i = 0; $i < $rating->rating; $i++)
                                                                                        <i class="las la-star icon"></i>
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                            <p class="comment">{{ $rating->review_msg }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @empty
                                                                <div class="text-center">
                                                                    <h4 class="text-secondary">
                                                                        {{ filter_static_option_value('no_rating_text', $setting_text, __('No rating to show yet')) }}
                                                                    </h4>
                                                                </div>
                                                            @endforelse
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="related_products">
                        <div class="row margin-top-90">
                            <div class="col-lg-12">
                                <div class="section-title-wrapper">

                                    <h2 class="section-title">
                                        {{ filter_static_option_value('related_product_text', $setting_text, __('Related products')) }}
                                    </h2>
                                    <div class="img-box">
                                        {!! render_image_markup_by_attachment_id(get_static_option('related_product_image')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row rel-prodc col-control">
                            @forelse ($related_products as $related_product)
                                <div class="col-6 col-sm-6 col-md-6 col-lg-4">
                                    <x-frontend.product.product-card-03 :product="$related_product" :model="false" />
                                </div>
                            @empty
                                <h4 class="text-center py-4">No product found!</h4>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.partials.product.product-filter-form')
@endsection
@section('script')
    <script src="{{ asset('assets/frontend/js/rating.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/bootstrap4-rating-input.js') }}"></script>
    @include('frontend.partials.product.product-filter-script')
    <script>
        let attribute_store = JSON.parse('{!! json_encode($product_inventory_set) !!}');
        let additional_info_store = JSON.parse('{!! json_encode($additional_info_store) !!}');
        let available_options = $('.value-input-area');

        $(document).on("mouseover", ".select-ratings a i", function() {
            rating_icon.call(this)
        });

        $(document).on("click", ".select-ratings a i", function() {
            rating_icon.call(this)
        });

        function getAttributesForCart() {
            let selected_options = get_selected_options();
            let cart_selected_options = selected_options;
            let hashed_key = getSelectionHash(selected_options);

            // if selected attribute set is available
            if (additional_info_store[hashed_key]) {
                return additional_info_store[hashed_key]['pid_id'];
            }

            // if selected attribute set is not available
            if (Object.keys(selected_options).length) {
                toastr.error('Attribute not available')
            }

            return '';
        }

        function get_selected_options() {
            let selected_options = {};
            var available_options = $('.value-input-area');
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

            return ordered_data;
        }

        function getSelectionHash(selected_options) {
            return MD5(JSON.stringify(selected_options));
        }

        function validateSelectedAttributes() {
            let selected_options = get_selected_options();
            let hashed_key = getSelectionHash(selected_options);

            // validate if product has any attribute
            if (quick_view_attribute_store.length) {
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

        function rating_icon() {
            let rating = $(this).data('rating');
            let icon = document.querySelectorAll(".select-ratings a i");

            // icon[i].classList.remove("las");
            $(".select-ratings a i").each(function() {
                $(this).removeClass("las").addClass("lar");
            });

            for (let i = 0; i < rating; i++) {
                icon[i].classList.replace("lar", "las");
            }

            $("#rating-number").val(rating);
        }

        (function($) {
            'use script'
            let site_currency_symbol = '{{ site_currency_symbol() }}';

            $(document).ready(function() {
                $('.add_to_cart_single_page').on('click', function(e) {
                    e.preventDefault();

                    let selected_size = $('#selected_size').val();
                    let selected_color = $('#selected_color').val();

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
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                toastr.success(data.msg);
                                if (data.quantity_msg) {
                                    toastr.warning(data.quantity_msg)
                                }
                                refreshShippingDropdown();
                            },
                            erorr: function(err) {
                                toastr.error('{{ __('An error occurred') }}');
                            }
                        });
                    } else {
                        toastr.error('{{ __('Select all attribute to proceed') }}');
                    }
                });

                $('.add_to_wishlist').on('click', function(e) {
                    e.preventDefault();
                    let product_id = $(this).data('id');
                    let quantity = Number($('#quantity').val().trim());
                    let pid_id = getAttributesForCart();

                    // if selected attribute is a valid product item
                    if (validateSelectedAttributes()) {
                        $.ajax({
                            url: '{{ route('frontend.products.add.to.wishlist.ajax') }}',
                            type: 'POST',
                            data: {
                                product_id: product_id,
                                quantity: quantity,
                                pid_id: pid_id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                toastr.success(data.msg);
                                refreshWishlistDropdown();
                            },
                            erorr: function(err) {
                                toastr.error('{{ __('An error occurred') }}');
                            }
                        });
                    } else {
                        toastr.error('{{ __('Select an attribute to proceed') }}');
                    }
                });

                $('.buy_now').on('click', function(e) {
                    e.preventDefault();

                    let product_id = $(this).data('id');
                    let quantity = Number($('#quantity').val().trim());
                    let pid_id = getAttributesForCart();

                    if (validateSelectedAttributes()) {
                        $.ajax({
                            url: '{{ route('frontend.products.add.to.cart.ajax') }}',
                            type: 'POST',
                            data: {
                                product_id: product_id,
                                quantity: quantity,
                                pid_id: pid_id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                toastr.success(data.msg);
                                if (data.quantity_msg) {
                                    toastr.warning(data.quantity_msg)
                                }
                                setTimeout(function() {
                                    location.href =
                                        '{{ route('frontend.checkout') }}';
                                }, 1000);
                                // refreshShippingDropdown();
                            },
                            erorr: function(err) {
                                toastr.error('{{ __('Something went wrong') }}');
                            }
                        });
                    } else {
                        toastr.error('{{ __('Select all attribute to proceed') }}');
                    }
                });

                $(document).on('click', '#login_btn', function(e) {
                    let formContainer = $('#login_form_order_page');
                    let el = $(this);
                    let username = $('#login_form_order_page #login_email').val();
                    let password = $('#login_form_order_page #login_password').val();
                    let remember = $('#login_form_order_page #login_remember').val();

                    el.text('{{ __('Please Wait') }}');

                    $.ajax({
                        type: 'post',
                        url: "{{ route('user.ajax.login') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            username: username,
                            password: password,
                            remember: remember,
                        },
                        success: function(data) {
                            if (data.status === 'invalid') {
                                el.text('{{ __('Login') }}')
                                formContainer.find('.error-wrap').html(
                                    '<div class="alert alert-danger">' + data.msg +
                                    '</div>');
                            } else {
                                formContainer.find('.error-wrap').html('');
                                el.text('{{ __('Login Success.. Redirecting ..') }}');
                                location.reload();
                            }
                        },
                        error: function(data) {
                            var response = data.responseJSON.errors;
                            formContainer.find('.error-wrap').html(
                                '<ul class="alert alert-danger"></ul>');
                            $.each(response, function(value, index) {
                                formContainer.find('.error-wrap ul').append('<li>' +
                                    index + '</li>');
                            });
                            el.text('{{ __('Login') }}');
                        }
                    });
                });

                $(document).on('click', '#shop_details_gallery_nav .individual_thumb_image_wrap', function(
                    event) {
                    let el = $(this);
                    //let lgImage = $('#shop_details_gallery_slider .single-main-image[data-slick-index="0"]');
                });

                $(document).on('click', '.size-lists li', function(event) {
                    let el = $(this);
                    let value = el.data('displayValue');
                    let parentWrap = el.parent().parent();
                    el.addClass('active');
                    el.siblings().removeClass('active');
                    parentWrap.find('input[type=text]').val(value);
                    parentWrap.find('input[type=hidden]').val(el.data('value'));

                    // selected attributes
                    selectedAttributeSearch(this);
                });

                // $(document).on('click', '.value-input-area', function () {
                //     selectedAttributeSearch();
                // });
            });

            function refreshShippingDropdown() {
                $.ajax({
                    url: '{{ route('frontend.products.cart.info.ajax') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#cart_badge').text(data.item_total);
                        $('#top_minicart_container').html(data.cart);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('An error occurred') }}');
                    }
                });
            }

            function refreshWishlistDropdown() {
                $.ajax({
                    url: '{{ route('frontend.products.wishlist.info.ajax') }}',
                    type: 'GET',
                    success: function(data) {
                        $('#wishlist_badge').text(data['item_total']);
                        $('#top_wishlist_container').html(data['wishlist']);
                    },
                    erorr: function(err) {
                        toastr.error('{{ __('Something went wrong') }}');
                    }
                });
            }
        })(jQuery)

        function selectedAttributeSearch(selected_item) {
            /*
             * search based on all selected attributes
             *
             * 1. get all selected attributes in {key:value} format
             * 2. search in attribute_store for all available matches
             * 3. display available matches (keep available matches selectable, and rest as disabled)
             * */

            let available_variant_types = [];
            let selected_options = {};

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

            syncImage(get_selected_options());
            syncPrice(get_selected_options());

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

            // selected item not contain product then de-select all selected option hare
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

                selectedAttributeSearch();
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
                })
            });
            //  check is empty object
            // selected_attributes_by_type
        }

        function getSelectedOptions() {
            let selected_options = {};

            // get all selected attributes in {key:value} format
            available_options.map(function(k, option) {
                let selected_option = $(option).find('li.active');
                let type = selected_option.closest('.size-lists').data('type');
                let value = selected_option.data('displayValue');

                if (type && value) {
                    selected_options[type] = value;
                }
            });

            return selected_options;
        }

        function syncImage(selected_options) {
            //todo fire when attribute changed
            let hashed_key = getSelectionHash(selected_options);
            //single-main-image slick-slide slick-current slick-active
            let product_image_el = $('#shop_details_gallery_slider .slick-current.slick-active .long-img img');

            let img_original_src = product_image_el.parent().data('src');
            // if selection has any image to it
            if (additional_info_store[hashed_key]) {
                let attribute_image = additional_info_store[hashed_key].image;
                if (attribute_image) {
                    product_image_el.attr('src', attribute_image);
                    product_image_el.parent().attr('data-src', attribute_image);
                    //chagne zoom image also
                } else {
                    product_image_el.attr('src', img_original_src);
                    product_image_el.parent().attr('data-src', img_original_src);
                    //chagne zoom image also
                }
            } else {
                product_image_el.attr('src', img_original_src);
                product_image_el.parent().attr('data-src', img_original_src);
                //chagne zoom image also
            }
        }

        function syncPrice(selected_options) {
            let hashed_key = getSelectionHash(selected_options);

            let product_price_el = $('#price');
            let product_main_price = Number(String(product_price_el.data('mainPrice'))).toFixed(0);
            let site_currency_symbol = product_price_el.data('currencySymbol');

            // if selection has any additional price to it
            if (additional_info_store[hashed_key]) {
                let attribute_price = additional_info_store[hashed_key]['additional_price'];
                if (attribute_price) {
                    let price = Number(product_main_price) + Number(attribute_price);
                    product_price_el.text(site_currency_symbol + Number(price).toFixed(0));
                } else {
                    product_price_el.text(site_currency_symbol + product_main_price);
                }
            } else {
                product_price_el.text(site_currency_symbol + product_main_price);
            }
        }

        function validateSelectedAttributes() {
            let selected_options = get_selected_options();
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
    </script>
@endsection
