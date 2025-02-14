@extends('backend.admin-master')
@section('site-title')
    {{ __('Blog Page Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                @include('backend.partials.message')
                @include('backend.partials.error')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Blog Page Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.blog.page.settings') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="blog_page_read_more_btn_text">{{ __('Blog Read More Text') }}</label>
                                        <input type="text" class="form-control" id="blog_page_read_more_btn_text"
                                            name="blog_page_read_more_btn_text"
                                            value="{{ get_static_option('blog_page_read_more_btn_text') }}"
                                            placeholder="{{ __('Blog Read More Text') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="blog_page_item">{{ __('Post Items') }}</label>
                                        <input type="text" class="form-control" id="blog_page_item"
                                            value="{{ get_static_option('blog_page_item') }}" name="blog_page_item"
                                            placeholder="{{ __('Post Items') }}">
                                        <small
                                            class="text-danger">{{ __('Enter how many post you want to show in blog page') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button id="update" type="submit"
                                        class="cmn_btn btn_bg_profile">{{ __('Update Blog Page Settings') }}</button>
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
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - btn.update / >
            });
        })(jQuery)
    </script>
@endsection
