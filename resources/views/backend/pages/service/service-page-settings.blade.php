@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/colorpicker.css') }}">
@endsection
@section('site-title')
    {{ __('Service Page Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Service Page Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.services.page.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="service_page_service_items">{{ __('Service Items') }}</label>
                                <input type="text" name="service_page_service_items" class="form-control"
                                    value="{{ get_static_option('service_page_service_items') }}"
                                    id="service_page_service_items">
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
