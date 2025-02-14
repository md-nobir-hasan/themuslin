@extends('backend.admin-master')
@section('site-title')
    {{ __('Blog Single Page Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                @include('backend.partials.message')
                @include('backend.partials.error')
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Blog Single Page Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.blog.single.settings') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="blog_single_pag_related_post_title">{{ __('Related Post Title') }}</label>
                                        <input type="text" class="form-control" id="blog_single_page_related_post_title"
                                            value="{{ get_static_option('blog_single_page_related_post_title') }}"
                                            name="blog_single_page_related_post_title"
                                            placeholder="{{ __('Related Post Title') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="blog_single_page_tags_title">{{ __('Tags Title') }}</label>
                                        <input type="text" class="form-control" id="blog_single_page_tags_title"
                                            value="{{ get_static_option('blog_single_page_tags_title') }}"
                                            name="blog_single_page_tags_title" placeholder="{{ __('Tags Title') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="blog_single_page_share_title">{{ __('Share Title') }}</label>
                                        <input type="text" class="form-control" id="blog_single_page_share_title"
                                            value="{{ get_static_option('blog_single_page_share_title') }}"
                                            name="blog_single_page_share_title" placeholder="{{ __('Share Title') }}">
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
