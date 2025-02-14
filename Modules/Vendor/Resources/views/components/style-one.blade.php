<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
    <div class="allProduct__item radius-10 bg-white">
        <div class="allProduct__item__thumb">
            <a href="{{ route('frontend.vendors.single', $vendor->username) }}">
                {!! render_image($vendor->cover_photo) !!}
            </a>
        </div>
        <div class="allProduct__item__contents">
            <div class="allProduct__item__brand overflow-hidden">
                <a href="{{ route('frontend.vendors.single', $vendor->username) }}">
                    {!! render_image($vendor->logo) !!}
                </a>
            </div>
            <h4 class="allProduct__item__title mt-2">
                <a href="{{ route('frontend.vendors.single', $vendor->username) }}">
                    {{ $vendor->business_name }}
                </a>
            </h4>
            @if($vendor->vendor_product_rating_count > 0)
                <div class="product__card__review radius-5 mt-2">
                    <span class="product__card__review__icon"><i class="las la-star"></i></span>
                    <span class="product__card__review__rating">{{ toFixed($vendor->vendor_product_rating_avg_rating,1) }}</span>
                    <span class="product__card__review__times">({{ $vendor->vendor_product_rating_count }})</span>
                </div>
            @endif
        </div>
    </div>
</div>