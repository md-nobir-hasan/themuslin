@extends('vendor.vendor-master')

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
            </div>

            <div class="col-lg-12">
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All withdraw request page') }}</h4>
                    </div>
                    <div class="dashboard__card__body">
                        <div class="table-wrap">
                            <table class="table-responsive table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Gateway Name') }}</th>
                                        <th style="width: 30%">{{ __('Gateway Fields') }}</th>
                                        <th>{{ __('Note') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($withdrawRequests as $request)
                                        @php
                                            $fields = '';
                                        @endphp
                                        @foreach (json_decode($request->gateway_fields) as $key => $value)
                                            @php
                                                $fields .= ucwords(str_replace('_', ' ', $key)) . ' => ' . $value;
                                                if (!$loop->last) {
                                                    $fields .= ' , ';
                                                }
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td>
                                                <strong>{{ $request->amount ?? '' }}</strong>
                                            </td>
                                            <td>
                                                <div class="table-paymentGateway">
                                                    {{ $request->gateway->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="table-fields">{{ $fields }}</div>
                                            </td>
                                            <td>
                                                <div class="table-notes">{{ $request->note ?? '' }}</div>
                                            </td>
                                            <td>
                                                @if (!empty($request->image))
                                                    <div class="table-image">
                                                        <img src="{{ asset('assets/uploads/wallet-withdraw-request/' . $request->image) }}"
                                                            alt="{{ $request->gateway?->name }}" />
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <x-status-span :status="$request->request_status" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
