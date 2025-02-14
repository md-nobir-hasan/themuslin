@extends('backend.admin-master')
@section('style')
    <x-datatable.css />
    <style>
        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        .border-bottom-1 {
            border-bottom: 1px solid #ddd;
        }

        .badge {
            line-height: 15px;
        }

        body {
            background-color: #eeeeee;
            font-family: 'Open Sans', serif
        }

        .container {
            margin-top: 50px;
            margin-bottom: 50px
        }

        .card {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.10rem
        }

        .card-header:first-child {
            border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1)
        }

        .track-wrapper {
            height: 100%;
            display: block;
        }

        .track {
            position: relative;
            background-color: #ddd;
            height: 5px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px;
        }

        @media screen and (max-width: 480px) {
            .track {
                margin-bottom: 80px;
            }
        }

        .track .step {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 20%;
            margin-top: -13px;
            text-align: center;
            position: relative
        }

        .track .step.active:before {
            background: #FF5722
        }

        .track .step::before {
            height: 5px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 13px;
        }

        .track .step.active .icon {
            background: #ee5435;
            color: #fff
        }

        .track .icon {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }

        .track .step.active .text {
            font-weight: 400;
            color: #000
        }

        .track .text {
            display: block;
            margin-top: 7px
        }

        .itemside {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .itemside .aside {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px
        }

        ul.row,
        ul.row-sm {
            list-style: none;
            padding: 0
        }

        .itemside .info {
            padding-left: 15px;
            padding-right: 7px
        }

        .itemside .title {
            display: block;
            margin-bottom: 5px;
            color: #212529
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .btn-warning {
            color: #ffffff;
            background-color: #ee5435;
            border-color: #ee5435;
            border-radius: 1px
        }

        .btn-warning:hover {
            color: #ffffff;
            background-color: #ff2b00;
            border-color: #ff2b00;
            border-radius: 1px
        }
    </style>
@endsection
@section('site-title')
    {{ __('My Orders') }}
@endsection
@php
    $edit = $edit ?? false;
@endphp
@section('content')
    <div class="row g-4">
        <div class="col-md-12">
            <x-msg.error />
            <x-msg.success />
        </div>
        @if ($edit)
            <div class="col-md-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Update Order') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                {{-- Order status can be updated by the admin (When updating an order, two conditions must be met. Orders cannot be canceled or completed) --}}
                                {{-- If the payment status for an order is not complete, the admin can update it --}}
                                <div class="dashboard__card">
                                    <div class="dashboard__card__header">
                                        <h4 class="dashboard__card__title">{{ __('Order Status & Payment Status') }}</h4>
                                    </div>
                                    <div class="dashboard__card__body custom__form mt-4">
                                        <form id="updateOrder" method="post"
                                            action="{{ route('admin.orders.update.order-status') }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" value="{{ $order->id }}" name="order_id">

                                            <div class="form-group">
                                                <label for="">{{ __('Order Status') }}</label>
                                                <select
                                                    {{ $order->order_status == 'complete' || $order->order_status == 'rejected' ? 'disabled' : '' }}
                                                    name="order_status" class="form-control">
                                                    <option {{ $order->order_status == 'pending' ? 'selected' : '' }}
                                                        value="pending">{{ __('Pending') }}</option>
                                                    <option {{ $order->order_status == 'complete' ? 'selected' : '' }}
                                                        value="complete">{{ __('Complete') }}</option>
                                                    <option {{ $order->order_status == 'failed' ? 'selected' : '' }}
                                                        value="failed">{{ __('Failed') }}</option>
                                                    <option {{ $order->order_status == 'rejected' ? 'selected' : '' }}
                                                        value="rejected">{{ __('Rejected') }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="">{{ __('Payment Status') }}</label>
                                                <select
                                                    {{ $order->payment_status == 'complete' || $order->payment_status == 'rejected' ? 'disabled' : '' }}
                                                    name="payment_status" class="form-control" id="payment_status">
                                                    <option {{ $order->payment_status == 'pending' ? 'selected' : '' }}
                                                        value="pending">{{ __('Pending') }}</option>
                                                    <option {{ $order->payment_status == 'complete' ? 'selected' : '' }}
                                                        value="complete">{{ __('Complete') }}</option>
                                                    <option {{ $order->payment_status == 'failed' ? 'selected' : '' }}
                                                        value="failed">{{ __('Failed') }}</option>
                                                </select>

                                                <input type="hidden" id="payment_status_hidden"
                                                    name="payment_status_hidden" value="{{ $order->payment_status }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Note by Admin</label>
                                                <input type="text" class="form-control" name="admin_note"
                                                    value="{{ $order->admin_note }}" placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <button class="cmn_btn btn_bg_profile"
                                                    {{ ($order->order_status == 'complete' || $order->order_status == 'rejected') &&
                                                    ($order->order_status == 'complete' || $order->order_status == 'rejected')
                                                        ? 'disabled'
                                                        : '' }}>{{ __('Update') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Admin can update order track status --}}
                                <x-order::order-track :order="$order" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Orders Details') }}</h4>
                    <div class="d-flex justify-content-between">
                        <b>{{ __('Order ID') }}</b>
                        <h6>#{{ $order->id }}</h6>
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="request__item">
                        <span class="request__left">Invoice Number</span>
                        <span class="request__right">#{{ $order->invoice_number }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Transaction ID') }}</span>
                        <span class="request__right">{{ $order->transaction_id }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Payment Gateway') }}</span>
                        <span
                            class="request__right">{{ ucwords(str_replace(['_', '-'], ' ', $order->payment_gateway)) }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Payment Status') }}</span>
                        <span class="request__right">{{ str($order->order_status)->ucfirst() }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Total Product') }}</span>
                        <span class="request__right">{{ $order->order_items_count }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Items Total') }}</span>
                        <span
                            class="request__right">{{ float_amount_with_currency_symbol($order?->paymentMeta?->sub_total) }}
                        </span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Discount Amount') }}</span>
                        <span
                            class="request__right">{{ float_amount_with_currency_symbol($order?->paymentMeta?->coupon_amount) }}
                        </span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Shipping Cost') }}</span>
                        <span
                            class="request__right">{{ float_amount_with_currency_symbol($order?->paymentMeta?->shipping_cost) }}
                        </span>
                    </div>
                    <!-- <div class="request__item">
                            <span class="request__left">{{ __('Tax Amount') }}</span>
                            <span class="request__right">{{ float_amount_with_currency_symbol($order?->paymentMeta?->tax_amount) }}</span>
                        </div> -->
                    <div class="d-flex justify-content-between py-2">
                        <span class="request__left">{{ __('Total Amount') }}</span>
                        <span
                            class="request__right">{{ float_amount_with_currency_symbol($order?->paymentMeta?->total_amount) }}
                        </span>
                    </div>
                    @if ($order->coupon)
                        <div class="d-flex justify-content-between py-2">
                            <span class="request__left">Coupon Code</span>
                            <span class="request__right">{{ $order->coupon }}
                            </span>
                        </div>
                    @endif
                    @if ($order->note)
                        <div class="request__item">
                            <span class="request__left">Order Note</span>
                            <span
                                class="request__right">{{ $order->note ?? '' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @php
            $addr =
                $order?->address?->cityInfo?->name .
                ' , ' .
                $order?->address?->state?->name .
                ' , ' .
                $order?->address?->country?->name .
                ' , ' .
                $order?->address?->zipcode;
        @endphp

        <div class="col-md-6">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Billing Information') }}</h4>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="request__item">
                        <span class="request__left">{{ __('Name') }}</span>
                        <span class="request__right">{{ $order?->address?->name }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Email') }}</span>
                        <span class="request__right">{{ $order?->address?->email }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Mobile') }}</span>
                        <span class="request__right">{{ $order?->address?->phone }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('Country') }}</span>
                        <span class="request__right">{{ $order?->address?->country?->name }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('State') }}</span>
                        <span class="request__right">{{ $order?->address?->state?->name }}</span>
                    </div>
                    <div class="request__item">
                        <span class="request__left">{{ __('City') }}</span>
                        <span class="request__right">{{ $order?->address?->cityInfo?->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2">
                        <span class="request__left">{{ __('Zip Code') }}</span>
                        <span class="request__right">{{ $order?->address?->zipcode }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard__card mt-4">
        <div class="dashboard__card__header">
            <h4 class="dashboard__card__title">{{ __('Sub Order Information') }}</h4>
        </div>
        <div class="dashboard__card__body mt-4">
            <div class="row g-4">
                @foreach ($order->SubOrders as $subOrders)
                    @if (empty($subOrders->vendor))
                        @php
                            $adminShop = \App\AdminShopManage::with('logo')->first();
                            $adminProductCount = \Modules\Product\Entities\Product::query()
                                ->whereNotNull('admin_id')
                                ->count();
                            $adminTotalOrderCount = \Modules\Order\Entities\SubOrder::query()
                                ->whereNull('vendor_id')
                                ->count();
                            $adminCompleteOrderCount = \Modules\Order\Entities\SubOrder::query()
                                ->whereRelation('order', 'order_status', '=', 'complete')
                                ->whereNull('vendor_id')
                                ->count();
                            $adminPendingOrderCount = \Modules\Order\Entities\SubOrder::query()
                                ->whereRelation('order', 'order_status', '=', 'pending')
                                ->whereNull('vendor_id')
                                ->count();
                            $adminCompleteOrderIncome = \Modules\Order\Entities\SubOrder::query()
                                ->whereRelation('order', 'order_status', '=', 'complete')
                                ->whereNull('vendor_id')
                                ->sum('total_amount');
                        @endphp
                        <div class="col-md-12">
                            <div class="dashboard__card">
                                <div class="dashboard__card__header">
                                    <h5 class="dashboard__card__title"><b>{{ __('Sub Order ID') }} </b>
                                        <b class="request__right">#{{ $subOrders->id }}</b>
                                    </h5>
                                    <div class="dashboard__card__header__right">
                                        <div class="d-flex justify-content-between gap-1">

                                        </div>
                                        <a href="{{ route('admin.orders.details', $subOrders->id) }}"
                                            class="cmn_btn btn_bg_profile">
                                            <i class="las la-eye"></i> <small>{{ __('View Sub Order') }}</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="dashboard__card__body mt-4">
                                    <div class="row g-4">

                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Total Product') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $adminProductCount }}
                                                    </h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Total Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $adminTotalOrderCount }}</h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Pending Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $adminPendingOrderCount }}
                                                    </h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Complete Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $adminCompleteOrderCount }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__item">
                                                    <span class="subOrder__single__item__left">
                                                        <h6>{{ __('Order Information') }}</h6>
                                                    </span>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Order Product Count') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->order_item_count }}
                                                    </h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Payment Status') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ ucwords($subOrders->payment_status) }}</h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Order Amount') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ float_amount_with_currency_symbol($subOrders->total_amount) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <div class="dashboard__card">
                                <div class="dashboard__card__header">
                                    <h5 class="title">{{ $subOrders->vendor?->business_name }}</h5>
                                    <div class="dashboard__card__header__right">

                                        <div class="d-flex justify-content-between gap-1">
                                            <b>{{ __('This order status') }} </b>
                                            <b
                                                class="badge {{ $subOrders->order_status === 'order_cancelled' ? 'bg-danger' : 'bg-dark' }}">
                                                {{ ucfirst(str_replace(['_', '-'], ' ', $subOrders->order_status)) }}</b>
                                        </div>

                                        <div class="d-flex justify-content-between gap-1">
                                            <b>{{ __('Sub Order ID') }} </b>
                                            <b class="request__right">#{{ $subOrders->id }}</b>
                                        </div>
                                        <a href="{{ route('admin.orders.details', $subOrders->id) }}"
                                            class="cmn_btn btn_bg_profile">
                                            <i class="las la-eye"></i> <small>{{ __('View Sub Order') }}</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="dashboard__card__body mt-4">
                                    <div class="row g-4">
                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__thumb">
                                                    {!! render_image($subOrders->vendor->logo, class: 'w-50') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ $subOrders->vendor->owner_name }}</span>
                                                    <div class="subOrder__single__item__right">
                                                        <h6>({{ $subOrders->vendor->username }})</h6>
                                                    </div>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ strip_tags($subOrders->vendor->description) }}</span>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Total Income') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ float_amount_with_currency_symbol($subOrders->vendor->total_earning) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Total Product') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->vendor->product_count }}</h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Total Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->vendor->pending_order }}</h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Pending Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->vendor->pending_order }}</h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Complete Orders') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->vendor->complete_order }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-xl-4 col-sm-6">
                                            <div class="subOrder__single">
                                                <div class="subOrder__single__item">
                                                    <span class="subOrder__single__item__left">
                                                        <h6>{{ __('Order Information') }}</h6>
                                                    </span>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Order Product Count') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ $subOrders->order_item_count }}
                                                    </h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Payment Status') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ ucwords($subOrders->payment_status) }}
                                                    </h6>
                                                </div>
                                                <div class="subOrder__single__item">
                                                    <span
                                                        class="subOrder__single__item__left">{{ __('Order Amount') }}</span>
                                                    <h6 class="badge badge-sm bg-success subOrder__single__item__right">
                                                        {{ float_amount_with_currency_symbol($subOrders->total_amount) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ get_static_option('map_api_key_client') }}&libraries=drawing,places&v=3.45.8">
    </script>
    <script src="{{ asset('assets/backend/js/sweetalert2.js') }}"></script>
    <script>
        function geocodeAddress(address) {
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();
                } else {

                }
            });
        }

        // Call the function with your address
        var address = "Dhaka, Dhaka, Bangladesh, 24727";

        $(document).ready(function() {
            console.log(geocodeAddress(address));
        });


        (function($) {
            "use strict";
            $(document).on("change", ".order-track-input", function() {
                let el = $(this);
                $(".order-track-input").removeAttr("checked");
                $(".order-track-input").each(function() {
                    $(this).prop("checked", true);
                    if (el.val() == $(this).val()) {
                        return false;
                    }
                })
            });
            $(document).ready(function() {
                $(document).on('click', '.bodyUser_overlay', function() {
                    $('.user-dashboard-wrapper, .bodyUser_overlay').removeClass('show');
                });
                $(document).on('click', '.mobile_nav', function() {
                    $('.user-dashboard-wrapper, .bodyUser_overlay').addClass('show');
                });
                $(document).on('click', '.swal_delete_button', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: '{{ __('You would not be able to revert this item!') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });
            });


            document.getElementById('payment_status').addEventListener('change', function() {

                console.log('dadsd');
                document.getElementById('payment_status_hidden').value = this.value;
            });

        })(jQuery)
    </script>
    <x-datatable.js />
@endsection
