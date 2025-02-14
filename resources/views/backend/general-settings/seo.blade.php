@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap-tagsinput.css') }}">
@endsection
@section('site-title')
    {{ __('SEO Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('SEO Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.seo.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_meta_tags">{{ __('Site Meta Tags') }}</label>
                                        <input type="text" name="site_meta_tags" class="form-control"
                                            data-role="tagsinput" value="{{ get_static_option('site_meta_tags') }}"
                                            id="site_meta_tags">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_meta_description">{{ __('Site Meta Description') }}</label>
                                        <textarea name="site_meta_description" class="form-control" id="site_meta_description">{{ get_static_option('site_meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="btn-wrapper">
                                        <button type="submit"
                                            class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/bootstrap-tagsinput.js') }}"></script>
@endsection
