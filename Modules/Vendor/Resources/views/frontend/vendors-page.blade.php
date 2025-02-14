@extends('frontend.frontend-page-master')

@section('page-title', __('Vendor List Page'))
@section('title', __('Vendor List Page'))

@section('style')
@endsection

@section('content')

    <!-- Best Seller area Starts -->
    <section class="best-seller-area padding-top-100 padding-bottom-50">
        <div class="container container-one">
            <div class="row g-4">
                @foreach ($vendors as $vendor)
                    @php
                        $rating_width = round(($vendor->vendor_product_rating_avg_product_ratingsrating ?? 0) * 20);
                    @endphp
                    <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="best-seller-item center-text radius-10">
                            <div class="seller-thumb radius-10">
                                <a href="#1">
                                    {!! render_image($vendor->cover_photo) !!}
                                </a>
                            </div>
                            <a href="#1" class="thumb-brand radius-5 brand-thumb-border">
                                {!! render_image($vendor->logo) !!}
                            </a>
                            <div class="best-seller-contents mt-3">
                                <h5 class="common-title-two">
                                    <a href="{{ route('frontend.vendors.single', $vendor->username) }}">
                                        {{ $vendor->business_name }} </a>
                                </h5>
                                <div @class([
                                    'd-flex',
                                    'justify-content-center' => $vendor->vendor_product_rating_count < 1,
                                    'justify-content-between' => $vendor->vendor_product_rating_count > 0,
                                ])>
                                    <div>
                                        <i class="las la-shopping-bag"></i>
                                        ({{ $vendor->product_count }})
                                    </div>

                                    @if ($vendor->vendor_product_rating_count > 0)
                                        <div class="rating-wrap">
                                            <div class="ratings">
                                                <span class="hide-rating"></span>
                                                <span class="show-rating"
                                                    style="width: {{ $rating_width }}%!important"></span>
                                            </div>
                                            <div class="">
                                                <span class="total-ratings">
                                                    {{ $vendor->vendor_product_rating_count ? '(' . $vendor->vendor_product_rating_count . ')' : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('frontend.vendors.single', $vendor->username) }}" class="btn-product mt-2"
                                    tabindex="0"> {{ __('Visit Store') }} <i class="las la-arrow-right"></i> </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Best Seller area end -->

@endsection

@section('script')
@endsection
