@extends('frontend.frontend-page-master')

@section('site-title')
    {{ __('Track Order') }}
@endsection

@section('page-title')
    {{ __('Track Order') }}
@endsection

@section('content')
    <div class="sign-in-area-wrapper padding-top-100 padding-bottom-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="sign-in register">
                        <h3 class="custom__form mt-4">{{ __('Order Tracking') }}</h3>
                        <div class="form-wrapper custom__form mt-4">
                            <x-msg.flash />
                            <x-msg.error />

                            @if (session()->has('info'))
                                <div class="alert alert-{{ session('type') }}">
                                    {!! session('info') !!}
                                </div>
                            @endif

                            <form method="get" action="{{ route('frontend.products.track.order') }}">
                                <div class="form-group mt-2">
                                    <label for="order_id">{{ __('Order ID') }}</label>
                                    <input type="text" name="order_id" class="form-control" id="order_id"
                                        placeholder="{{ __('Order Id') }}">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="{{ __('Billing Email') }}">
                                </div>

                                <div class="btn-wrapper mt-4">
                                    <button type="submit"
                                        class="cmn_btn btn_bg_1 btn_small">{{ __('Track your order') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-flex align-items-center pt-5">
                    <img src="{{ asset("assets/img/tracking/treaking-image.webp") }}" alt="">
                </div>
            </div>
        </div>
    </div>
@endsection
