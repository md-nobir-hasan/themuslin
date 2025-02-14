@extends('backend.admin-master')
@section('title', __('History Details'))
@section('style')
    <style>
        .custom_table.style-04 table tbody tr td, .custom_table.style-04 table tbody tr th {
            border: 1px solid var(--border-color);
        }
    </style>
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="customMarkup__single__title">{{ __('History Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <!-- Table Start -->
                        <div class="col-md-6">
                            <table class="table table-responsive">
                                <tbody>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <td>{{ $history_details->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <td>{{ $history_details->user?->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $history_details->user?->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Phone') }}</th>
                                    <td>{{ $history_details->user?->phone }}</td>
                                </tr>

                                <tr>
                                    <th>{{ __('Email Verified Status') }}</th>
                                    <td> <x-status.table.verified-status :status="$history_details->user?->email_verified"/></td>
                                </tr>
                                <tr>
                                    <th>{{ __('Payment Gateway') }}</th>
                                    <td>
                                        @if($history_details->payment_gateway == 'manual_payment')
                                            {{ ucfirst(str_replace('_',' ',$history_details->payment_gateway)) }}
                                        @else
                                            {{ $history_details->payment_gateway == 'authorize_dot_net' ? __('Authorize.Net') : ucfirst($history_details->payment_gateway) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Payment Status') }}</th>
                                    <td>
                                        @if($history_details->payment_status == '' || $history_details->payment_status == 'cancel')
                                            <span class="btn btn-danger btn-sm">{{ __('Cancel') }}</span>
                                        @else
                                            <span class="btn btn-success btn-sm">{{ ucfirst($history_details->payment_status) }}</span>
                                            @if($history_details->payment_status == 'pending')
                                                <x-status.table.status-change :title="__('Change Status')" :class="'btn btn-danger wallet_history_status_change'" :url="route('admin.wallet.history.status',$history_details->id)"/>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Deposit Amount') }}</th>
                                    <td>{{ float_amount_with_currency_symbol($history_details->amount) }}</td>
                                </tr>
                                @if($history_details->payment_gateway == 'manual_payment')
                                <tr>
                                    <th>{{ __('Manual Payment Image') }}</th>
                                    <td>
                                        <span class="img_100">
                                            @if(empty($history_details->manual_payment_image))
                                                <img src="{{ asset('assets/static/img/no_image.png') }}" alt="">
                                            @else
                                                <img src="{{ asset('assets/uploads/manual-payment/'.$history_details->manual_payment_image) }}" alt="">
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th>{{ __('Deposit Date') }}</th>
                                    <td>{{ $history_details->created_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Table End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-sweet-alert.sweet-alert2-js />
    <script>


        $(document).on('click','.swal_status_change_button',function(e){
            e.preventDefault();

            Swal.fire({
                title: '{{__("Are you sure to change status?")}}',
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
    </script>
@endsection
