@extends('backend.admin-master')
@section('style')
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Compare Page Settings') }}
@endsection
@section('content')
    @can('page-settings-compare-page')
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-lg-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Compare Page Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.page.settings.compare') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="compare_title">{{ __('Compare Title') }}</label>
                                            <input type="text" class="form-control" id="compare_title" name="compare_title"
                                                value="{{ get_static_option('compare_title') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="compare_subtitle">{{ __('Compare Subtitle') }}</label>
                                            <input type="text" class="form-control" id="compare_subtitle"
                                                name="compare_subtitle" value="{{ get_static_option('compare_subtitle') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <x-media-upload :oldimage="get_static_option('compare_empty_image')" :name="'compare_empty_image'" :dimentions="'465X465'"
                                                :title="__('Empty Compare Image')" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="compare_empty_text">{{ __('Empty Compare Text') }}</label>
                                            <input type="text" class="form-control" id="compare_empty_text"
                                                name="compare_empty_text" value="{{ get_static_option('compare_empty_text') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="btn-wrapper">
                                            <button class="cmn_btn btn_bg_profile">{{ __('Save Settings') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup />
    @endcan
@endsection
@section('script')
    <x-media.js />
@endsection
