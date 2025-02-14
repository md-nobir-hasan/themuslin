@extends('backend.admin-master')

@section('site-title')
    {{ __('Section Manage') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                @include('backend/partials/message')
                @include('backend/partials/error')
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Section Manage') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.homeone.section.manage') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="home_page_key_feature_section_status"><strong>{{ __('Header Slider Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="home_page_header_slider_section_status"
                                                @if (!empty(get_static_option('home_page_header_slider_section_status'))) checked @endif
                                                id="home_page_key_feature_section_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="home_page_video_section_status"><strong>{{ __('Video Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="home_page_video_section_status"
                                                @if (!empty(get_static_option('home_page_video_section_status'))) checked @endif>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="home_page_video_section_status"><strong>{{ __('Recent Cause Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="home_page_recent_cause_section_status"
                                                @if (!empty(get_static_option('home_page_recent_cause_section_status'))) checked @endif>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="home_page_latest_news_section_status"><strong>{{ __('Latest Events Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="home_page_latest_events_section_status"
                                                @if (!empty(get_static_option('home_page_latest_events_section_status'))) checked @endif
                                                id="home_page_latest_news_section_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="home_page_latest_news_section_status"><strong>{{ __('Latest News Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="home_page_latest_blog_section_status"
                                                @if (!empty(get_static_option('home_page_latest_blog_section_status'))) checked @endif
                                                id="home_page_latest_news_section_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button id="update" type="submit"
                                class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Update Settings') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        < x - btn.update / >
    </script>
@endsection
