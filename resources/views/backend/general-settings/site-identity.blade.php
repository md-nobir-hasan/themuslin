@extends('backend.admin-master')
@section('site-title')
    {{ __('Site Identity') }}
@endsection
@section('style')
    <x-media.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Site Identity Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.site.identity') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <x-media-upload :title="__('Site Logo')" name="site_logo" :oldimage="get_static_option('site_logo')"/>

                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png. Recommended image size 160x50') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <x-media-upload name="site_white_logo" :oldimage="get_static_option('site_white_logo')" :title="__('White Site Logo')" />

                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png. Recommended image size 160x50') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <x-media-upload :title="__('Favicon')" name="site_favicon" :oldimage="get_static_option('site_favicon')" />

                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png. Recommended image size 40x40') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        @php
                                            $site_breadcrumb_bg_btn_label = 'Upload Breadcrumb Image';
                                        @endphp

                                        <x-media-upload :oldimage="get_static_option('site_breadcrumb_bg')" :title="$site_breadcrumb_bg_btn_label" name="site_breadcrumb_bg"  />

                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png, Recommended image size 1920x600') }}</small>
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
    <x-media.js />
@endsection
