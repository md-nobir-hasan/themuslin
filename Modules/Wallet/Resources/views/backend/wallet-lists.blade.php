@extends('backend.admin-master')

@section('site-title')
    {{__('Wallet Lists')}}
@endsection

@section('style')
    <x-media.css />
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__left">
                            <h4 class="dashboard__card__title">{{__('Wallet Lists')}}</h4>
                            <p class="dashboard__card__para text-primary mt-2">{{ __('You can active inactive status from here. If status is inactive user will not be able to use his/her wallet balance.') }}</p>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default" id="all_jobs">
                                <thead>
                                    <th>{{__('#No')}}</th>
                                    @if($type == "vendor")
                                        <th>{{ __("Store Name") }}</th>
                                    @endif
                                    <th>{{__('User Details')}}</th>
                                    <th>{{__('Wallet Balance')}}</th>
                                    <th>{{__('Status')}}</th>
                                </thead>
                                <tbody>
                                @forelse($wallet_lists ?? [] as $data)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>

                                        @if($type == "vendor")
                                            <td>{{$data?->vendor?->business_name}}</td>
                                        @endif
                                        <td>
                                            <ul>
                                                @if($type == "vendor")
                                                    <li><strong>{{__('Name')}}: </strong>{{$data?->vendor?->owner_name }}</li>
                                                    <li><strong>{{__('Email')}}: </strong>{{ $data?->vendor?->vendor_shop_info?->email }}</li>
                                                    <li><strong>{{__('Phone')}}: </strong>{{ $data?->vendor?->vendor_shop_info?->number }}</li>
                                                @elseif($type == "delivery_man")
                                                    <li><strong>{{__('Name')}}: </strong>{{$data?->deliveryMan?->full_name ?? '' }}</li>
                                                    <li><strong>{{__('Email')}}: </strong>{{ $data?->deliveryMan?->email ?? '' }}</li>
                                                    <li><strong>{{__('Phone')}}: </strong>{{ $data?->deliveryMan?->phone ?? '' }}</li>
                                                @else
                                                    <li><strong>{{__('Name')}}: </strong>{{$data?->user?->name }}</li>
                                                    <li><strong>{{__('Email')}}: </strong>{{ $data?->user?->email }}</li>
                                                    <li><strong>{{__('Phone')}}: </strong>{{ $data?->user?->phone }}</li>
                                                @endif
                                            </ul>

                                        </td>
                                        <td>{{ float_amount_with_currency_symbol($data?->balance ?? 0) }}</td>
                                        <td>
                                            @php
                                                $status = ['text-danger', 'text-success'];
                                            @endphp
                                            <strong>{{__('Wallet Status')}}:</strong>
                                            <span class="{{$status[$data->status]}}">{{ $data->status == 0 ? __('Inactive') : __('Active') }}</span>
                                            <span class="mx-2">
                                                <x-status-change :url="route('admin.wallet.status',$data->id)"/>
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center py-4" colspan="4">{{__('No Data Available')}}</td>
                                    </tr>
                                @endforelse
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
    <x-media.js />
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                $(document).on('click','.swal_status_change',function(e){
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
            });
        })(jQuery)
    </script>
@endsection

