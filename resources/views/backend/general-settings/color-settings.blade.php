@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/colorpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
@endsection
@section('site-title')
    {{ __('Color Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Color Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.color.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="tab-content margin-top-30" id="nav-tabContent"></div>
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_color">{{ __('Site Main Color Settings') }}</label>
                                        <input type="text" name="site_color"
                                            style="background-color: {{ get_static_option('site_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_color') }}"
                                            id="site_color">
                                        <small>{{ __('you change site main color from here, it will replace website main color') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_secondary_color">{{ __('Site Secondary Color Settings') }}</label>
                                        <input type="text" name="site_secondary_color"
                                            style="background-color: {{ get_static_option('site_secondary_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_secondary_color') }}"
                                            id="site_secondary_color">
                                        <small>{{ __('you change site secondary color from here, it will replace website secondary color') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_heading_color">{{ __('Site Heading Color') }}</label>
                                        <input type="text" name="site_heading_color"
                                            style="background-color: {{ get_static_option('site_heading_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_heading_color') }}"
                                            id="site_heading_color">
                                        <small>{{ __('you can change site heading color from there , when you chnage this color it will reflect the color in all the heading like (h1,h2,h3,h4.h5.h6)') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_special_color">{{ __('Customer profile Color Settings') }}</label>
                                        <input type="text" name="site_special_color"
                                            style="background-color: {{ get_static_option('site_special_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_special_color') }}"
                                            id="site_special_color">
                                        <small>{{ __('You change customer profile Color Settings color from here, it will replace website special color') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_paragraph_color">{{ __('Site Paragraph Color') }}</label>
                                        <input type="text" name="site_paragraph_color"
                                            style="background-color: {{ get_static_option('site_paragraph_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_paragraph_color') }}"
                                            id="site_paragraph_color">
                                        <small>{{ __('you can change site paragraph color from there') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_form_bg_color">{{ __('Site Form Background Color') }}</label>
                                        <input type="text" name="site_form_bg_color"
                                            style="background-color: {{ get_static_option('site_form_bg_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_form_bg_color') }}"
                                            id="site_form_bg_color">
                                        <small>{{ __('you can change site form background color from there') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_footer_bg_color">{{ __('Site Footer Background Color') }}</label>
                                        <input type="text" name="site_footer_bg_color"
                                            style="background-color: {{ get_static_option('site_footer_bg_color') }};color: #fff;"
                                            class="form-control" value="{{ get_static_option('site_footer_bg_color') }}"
                                            id="site_footer_bg_color">
                                        <small>{{ __('you can change site paragraph color from there') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit"
                                        class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/colorpicker.js') }}"></script>
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>
    <x-media.js />
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                initColorPicker('#site_color');
                initColorPicker('#site_secondary_color');
                initColorPicker('#site_main_color_two');
                initColorPicker('#site_heading_color');
                initColorPicker('#site_paragraph_color');
                initColorPicker('input[name="portfolio_home_color"');
                initColorPicker('input[name="logistics_home_color"');

                function initColorPicker(selector) {
                    $(selector).ColorPicker({
                        color: '#852aff',
                        onShow: function(colpkr) {
                            $(colpkr).fadeIn(500);
                            return false;
                        },
                        onHide: function(colpkr) {
                            $(colpkr).fadeOut(500);
                            return false;
                        },
                        onChange: function(hsb, hex, rgb) {
                            $(selector).css('background-color', '#' + hex);
                            $(selector).val('#' + hex);
                        }
                    });
                }
            });
        }(jQuery));
    </script>
@endsection
