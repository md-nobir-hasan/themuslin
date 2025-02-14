@extends('frontend.frontend-page-master')
@section('page-title')
    {{ __('All Campaign') }}
@endsection
@section('style')
@endsection
@section('content')
    <div class="campaign-page-area padding-top-bottom-100">
        <div class="container custom-container-1618">
            <div class="row position-relative">
                @foreach ($all_campaigns as $campaign)
                    <div class="col-lg-4">
                        <div class="campaign-contents-left">
                            <div class="bg-campaign-item"
                                {!! render_background_image_markup_by_attachment_id($campaign->image) !!}
                            ></div>
                            <div class="campaign-content">
                                <div class="campaign-content-inner">
                                    <a href="{{ route('frontend.products.campaign', $campaign->id) }}" class="campaign-tags">{{ Str::limit($campaign->title, 27) }}</p>
                                    <h3 class="campaign-offer-title"> <a href="{{ route('frontend.products.campaign', $campaign->id) }}">{{ Str::limit($campaign->subtitle, 27) }}</a> </h3>
                                    <div class="btn-wrapper">
                                        <a href="{{ route('frontend.products.campaign', $campaign->id) }}" class="default-btn"> {{ __('Shop Now') }} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
