@extends('backend.admin-master')

@section('site-title')
    {{ __('History Lists') }}
@endsection
@section('style')
    <x-media.css />
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
                        <h4 class="dashboard__card__title">{{ __('Customer Deposit History') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('User Details') }}</th>
                                        <th>{{ __('Payment Gateway') }}</th>
                                        <th>{{ __('Payment Status') }}</th>
                                        <th>{{ __('Deposit Amount') }}</th>
                                        <th>{{ __('Manual Payment Image') }}</th>
                                        <th>{{ __('Deposit Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wallet_history_lists as $history)
                                        <tr>
                                            <td>{{ $history->id }}</td>
                                            <td>
                                                <p><strong>{{ __('Name: ') }}</strong>{{ $history->user?->name }}</p>
                                                <p><strong>{{ __('Email: ') }}</strong>{{ $history->user?->email }}</p>
                                                <p><strong>{{ __('Phone: ') }}</strong>{{ $history->user?->phone }}</p>
                                                <p>
                                                    <strong>{{ __('Email Verified Status: ') }}</strong>
                                                    <x-status.table.verified-status :status="$history->user?->user_verified_status" />
                                                </p>
                                            </td>
                                            <td>
                                                @if ($history->payment_gateway == 'manual_payment')
                                                    {{ ucfirst(str_replace('_', ' ', $history->payment_gateway)) }}
                                                @else
                                                    {{ $history->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($history->payment_gateway) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($history->payment_status == '' || $history->payment_status == 'cancel')
                                                    <span class="btn btn-danger btn-sm">{{ __('Cancel') }}</span>
                                                @else
                                                    <span
                                                        class="btn btn-success btn-sm">{{ ucfirst($history->payment_status) }}</span>
                                                    @if ($history->payment_status == 'pending')
                                                        <x-status.table.status-change :title="__('Change Status')" :class="'btn btn-danger wallet_history_status_change'"
                                                            :url="route(
                                                                'admin.wallet.history.status',
                                                                $history->id,
                                                            )" />
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ float_amount_with_currency_symbol($history->amount) }}</td>
                                            <td>
                                                <span class="img_100">
                                                    @if (empty($history->manual_payment_image))
                                                        <img src="{{ asset('assets/static/img/no_image.png') }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ asset('assets/uploads/manual-payment/' . $history->manual_payment_image) }}"
                                                            alt="">
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $history->created_at }}</td>
                                            <td><a class="btn btn-sm btn-primary"
                                                    href="{{ route('admin.wallet.history.details', $history->id) }}">{{ __('View Details') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <x-pagination.laravel-paginate :allData="$wallet_history_lists" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-media.js />
    @can('wallet-history-records-status')
        <script>
            (function($) {
                "use strict";

                $(document).on('click', '.swal_status_change_button', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: '{{ __('Are you sure to change status?') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });
            })(jQuery)
        </script>
    @endcan
@endsection
