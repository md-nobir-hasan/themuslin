@extends('backend.admin-master')
@section('site-title')
    {{ __('Database Upgrade') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-msg.flash />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Database Upgrade') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.database.upgrade') }}" method="POST" id="cache_settings_form"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="btn-wrapper mt-4">
                                <button class="cmn_btn btn_bg_profile clear-cache-submit-btn"
                                    data-value="cache">{{ __('Database Upgrade') }}</button>
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
                $(document).on('click', '.clear-cache-submit-btn', function(e) {
                    $(this).html('<i class="fas fa-spinner fa-spin"></i> {{ __('Proccesing') }}')
                });
            });

        })(jQuery);
    </script>
@endsection
