@extends('backend.admin-master')

@section('site-title')
    {{ __('Wallet settings') }}
@endsection

@section('style')
    <style>
        .payment_attachment {
            width: 100px;
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-error-msg />
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Wallet settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.wallet.settings') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Minimum withdraw amount') }}</label>
                                <input class="form-control" name="minimum_withdraw_amount"
                                    value="{{ get_static_option('minimum_withdraw_amount') ?? 1 }}" />
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="cmn_btn btn_bg_profile">{{ __('Update wallet settings') }}</button>
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

        })(jQuery)
    </script>
@endsection
