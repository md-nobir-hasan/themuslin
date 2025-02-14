@extends('backend.admin-master')
@section('site-title')
    {{ __('GDPR Compliant Cookie Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('GDPR Compliant Cookie Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.gdpr.settings') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_gdpr_cookie_title">{{ __('GDPR Title') }}</label>
                                        <input type="text" name="site_gdpr_cookie_title" class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_title') }}"
                                            id="site_gdpr_cookie_title">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_gdpr_cookie_message">{{ __('GDPR Message') }}</label>
                                        <textarea name="site_gdpr_cookie_message" class="form-control" rows="5" id="site_gdpr_cookie_message">{{ get_static_option('site_gdpr_cookie_message') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="site_gdpr_cookie_more_info_label">{{ __('GDPR More Info Link Label') }}</label>
                                        <input type="text" name="site_gdpr_cookie_more_info_label" class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_more_info_label') }}"
                                            id="site_gdpr_cookie_more_info_label">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_gdpr_cookie_more_info_link">{{ __('GDPR More Info Link') }}</label>
                                        <input type="text" name="site_gdpr_cookie_more_info_link" class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_more_info_link') }}"
                                            id="site_gdpr_cookie_more_info_link">
                                        <small
                                            class="form-text text-muted">{{ __('enter more info link user {url} to point the site address, example: {url}/about , it will be converted to www.yoursite.com/about') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="site_gdpr_cookie_accept_button_label">{{ __('GDPR Cookie Accept Button Label') }}</label>
                                        <input type="text" name="site_gdpr_cookie_accept_button_label"
                                            class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_accept_button_label') }}"
                                            id="site_gdpr_cookie_accept_button_label">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="site_gdpr_cookie_decline_button_label">{{ __('GDPR Cookie Decline Button Label') }}</label>
                                        <input type="text" name="site_gdpr_cookie_decline_button_label"
                                            class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_decline_button_label') }}"
                                            id="site_gdpr_cookie_decline_button_label">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="site_gdpr_cookie_enabled"><strong>{{ __('GDPR Cookie Enable/Disable') }}</strong></label>
                                        <label class="switch yes">
                                            <input type="checkbox" name="site_gdpr_cookie_enabled"
                                                @if (!empty(get_static_option('site_gdpr_cookie_enabled'))) checked @endif
                                                id="site_gdpr_cookie_enabled">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_gdpr_cookie_expire">{{ __('Cookie Expire') }}</label>
                                        <input type="text" name="site_gdpr_cookie_expire" class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_expire') }}"
                                            id="site_gdpr_cookie_expire">
                                        <small
                                            class="form-text text-muted">{{ __('set cookie expire time, eg: 30, means 30days') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_gdpr_cookie_delay">{{ __('Show Delay') }}</label>
                                        <input type="text" name="site_gdpr_cookie_delay" class="form-control"
                                            value="{{ get_static_option('site_gdpr_cookie_delay') }}"
                                            id="site_gdpr_cookie_delay">
                                        <small
                                            class="form-text text-muted">{{ __('set GDPR cookie delay time, it mean the notification will show after this time. number count as mili seconds. eg: 5000, means 5seconds') }}</small>
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
@endsection
