@extends('backend.admin-master')
@section('site-title')
    {{ __('Mobile app settings page') }}
@endsection
@section('style')
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Mobile app settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.mobile.settings.buyer-app-settings') }}" method="post">
                            @csrf
                            <div class="form-group" id="product-list">
                                <label for="products">{{ __("App secret key") }}</label>
                                <input class="form-control" name="app_secret_key" value="{{ get_static_option("app_secret_key") }}" placeholder="{{ __("Enter your app secret key") }}" />
                            </div>

                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __("Update Settings") }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
