@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/show-hint.css') }}">
@endsection
@section('site-title')
    {{ __('Custom Css') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__left">
                            <h4 class="dashboard__card__title">{{ __('Custom Css') }}</h4>
                            <p class="dashboard__card__para mt-2">
                                {{ __('you can only add css style here. no other code work here.') }}</p>
                        </div>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.custom.css') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <textarea name="custom_css_area" id="custom_css_area" cols="30" rows="10">{{ $custom_css }}</textarea>
                            </div>
                            <div class="form-group">
                                <button id="update" type="submit"
                                    class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/codemirror.js') }}"></script>
    <script src="{{ asset('assets/backend/js/css.js') }}"></script>
    <script src="{{ asset('assets/backend/js/show-hint.js') }}"></script>
    <script src="{{ asset('assets/backend/js/css-hint.js') }}"></script>
    <script>
        (function($) {
            <
            x - btn.update / >
                "use strict";

            var editor = CodeMirror.fromTextArea(document.getElementById("custom_css_area"), {
                lineNumbers: true,
                mode: "text/css",
                matchBrackets: true
            });
        })(jQuery);
    </script>
@endsection
