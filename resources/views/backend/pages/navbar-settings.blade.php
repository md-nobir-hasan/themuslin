@extends('backend.admin-master')

@section('site-title')
    {{ __('Navbar Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                @include('backend/partials/message')
                @include('backend.partials.error')
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Navbar Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.navbar.settings') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label
                                    for="home_page_navbar_button_status"><strong>{{ __('Button Show/Hide') }}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="home_page_navbar_button_status"
                                        @if (!empty(get_static_option('home_page_navbar_button_status'))) checked @endif>
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="tab-content margin-top-20" id="nav-tabContent">

                                <div class="form-group">
                                    <label for="home_page_navbar_button_subtitle">{{ __('Button Subtitle') }}</label>
                                    <input type="text" name="home_page_navbar_button_subtitle" class="form-control"
                                        value="{{ get_static_option('home_page_navbar_button_subtitle') }}">
                                </div>
                                <div class="form-group">
                                    <label for="home_page_navbar_button_title">{{ __('Button Title') }}</label>
                                    <input type="text" name="home_page_navbar_button_title" class="form-control"
                                        value="{{ get_static_option('home_page_navbar_button_title') }}">
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="home_page_navbar_button_url">{{ __('Button URL') }}</label>
                                <input type="text" name="home_page_navbar_button_url" class="form-control"
                                    value="{{ get_static_option('home_page_navbar_button_url') }}">
                                <small
                                    class="text-danger">{{ __('** If you dont have any custom url then please leave this field blank or provide a valid URL **') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="home_page_navbar_button_icon" class="d-block">{{ __('Icon') }}</label>
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-primary iconpicker-component">
                                        <i class="{{ get_static_option('home_page_navbar_button_icon') }}"></i>
                                    </button>
                                    <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="{{ get_static_option('home_page_navbar_button_icon') }}"
                                        data-bs-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu"></div>
                                </div>
                                <input type="hidden" class="form-control"
                                    value="{{ get_static_option('home_page_navbar_button_icon') }}"
                                    name="home_page_navbar_button_icon">
                            </div>
                            <button id="update" type="submit"
                                class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - btn.update / >
                    $('.icp-dd').iconpicker();
                $('.icp-dd').on('iconpickerSelected', function(e) {
                    var selectedIcon = e.iconpickerValue;
                    $(this).parent().parent().children('input').val(selectedIcon);
                });
            });
        })(jQuery)
    </script>
@endsection
