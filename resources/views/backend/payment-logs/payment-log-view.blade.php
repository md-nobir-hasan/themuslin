@extends('backend.admin-master')
@section('site-title')
    {{ __('Payment Details') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Payment Details') }}</h4>
                        <a href="{{ route('admin.payment.logs') }}"
                            class="cmn_btn btn_bg_profile">{{ __('All Payments Log') }}</a>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="booking-details-info">
                            <ul>
                                <li><strong>{{ __('Order ID') }}</strong> : #{{ $payment_log->order_id }}</li>
                                <li><strong>{{ __('Name') }}</strong> : {{ $payment_log->name }}</li>
                                <li><strong>{{ __('Email') }}</strong> : {{ $payment_log->email }}</li>
                                <li><strong>{{ __('Package Name') }}</strong> :
                                    {{ purify_html($payment_log->package_name) }}</li>
                                <li><strong>{{ __('Package Price') }}</strong> : {{ $payment_log->package_price }}</li>
                                <li><strong>{{ __('Payment Gateway') }}</strong> :
                                    {{ str_replace('_', ' ', $payment_log->package_gateway) }}</li>
                                <li><strong>{{ __('Payment Status') }}</strong> : {{ $payment_log->status }}</li>
                                <li><strong>{{ __('Transaction ID') }}</strong> : {{ $payment_log->transaction_id }}</li>
                                <li><strong>{{ __('Date') }}</strong> : {{ $payment_log->status }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
