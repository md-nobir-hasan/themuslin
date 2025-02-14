@extends('backend.admin-master')
@section('site-title')
    {{ __('Cache Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Cache Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form">
                        <form action="{{ route('admin.general.cache.settings') }}" method="POST" id="cache_settings_form"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="cache_type" id="cache_type" class="form-control">
                            <button class="cmn_btn btn_bg_profile mt-4 clear-cache-submit-btn"
                                data-value="view">{{ __('Clear View Cache') }}</button><br>
                            <button class="cmn_btn btn_bg_profile mt-4 clear-cache-submit-btn"
                                data-value="route">{{ __('Clear Route Cache') }}</button><br>
                            <button class="cmn_btn btn_bg_profile mt-4 clear-cache-submit-btn"
                                data-value="config">{{ __('Clear Configure Cache') }}</button><br>
                            <button class="cmn_btn btn_bg_profile mt-4 clear-cache-submit-btn"
                                data-value="cache">{{ __('Clear Cache') }}</button>
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
                $(document).on('click', '.clear-cache-submit-btn', function(e) {
                    e.preventDefault();
                    $('#cache_type').val($(this).data('value'));
                    $('#cache_settings_form').trigger('submit');
                });
            });


        })(jQuery);
    </script>
@endsection
