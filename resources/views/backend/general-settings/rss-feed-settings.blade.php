@extends('backend.admin-master')
@section('site-title')
    {{ __('RSS Feed Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('RSS Feed Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <form action="{{ route('admin.general.rss.feed.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="site_rss_feed_url">{{ __('RSS Feed URL') }}</label>
                                <input type="text" name="site_rss_feed_url" id="site_rss_feed_url" class="form-control"
                                    value="{{ get_static_option('site_rss_feed_url') }}">
                                <p class="info-text">{{ __('this url will be add after. www.youdomain.com/') }}</p>
                            </div>
                            <div class="form-group">
                                <label for="site_rss_feed_title">{{ __('RSS Feed Title') }}</label>
                                <input type="text" name="site_rss_feed_title" id="site_rss_feed_title"
                                    class="form-control" value="{{ get_static_option('site_rss_feed_title') }}">
                            </div>
                            <div class="form-group">
                                <label for="site_rss_feed_description">{{ __('RSS Feed Description') }}</label>
                                <textarea name="site_rss_feed_description" id="site_rss_feed_description" cols="30" rows="5"
                                    class="form-control">{{ get_static_option('site_rss_feed_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
