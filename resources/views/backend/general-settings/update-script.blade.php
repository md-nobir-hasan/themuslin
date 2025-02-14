@extends('backend.admin-master')
@section('site-title')
    {{ __('Update Script') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Update Script') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <p class="info-text">
                            {{ __('you can check script update from here and able update the script from here.') }}</p>
                        <div class="progress-msg-show"></div>
                        <button type="submit" class="cmn_btn btn_bg_profile"
                            id="check_update_status">{{ __('Check Update') }}</button>
                        <button type="submit" class="cmn_btn btn_bg_profile"
                            id="run_the_update_now">{{ __('Run Update') }}</button>

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
                //check update status

                $(document).on('click', '#check_update_status', function(e) {
                    e.preventDefault();
                    alert('update checking');
                });

                $(document).on('click', '#run_the_update_now', function(e) {
                    e.preventDefault();
                    alert('run the update now');
                });
            });
        }(jQuery));
    </script>
@endsection
