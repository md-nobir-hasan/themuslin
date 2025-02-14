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
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Wallet history') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table-responsive table">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL NO') }}</th>
                                        <th>{{ __('Sub Order ID') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Date Time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($histories as $history)
                                        <tr>
                                            <td>#{{ $history->id ?? '' }}</td>
                                            <td>#{{ $history->sub_order_id ?? '' }}</td>
                                            <td>{{ $history->amount ? float_amount_with_currency_symbol($history->amount) : '' }}
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $history->type ? 'success' : 'warning' }}">
                                                    {{ $history->type ? __('Incoming') : __('Outgoing') }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $history->created_at->format('H:i:s d-m-Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! $histories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
