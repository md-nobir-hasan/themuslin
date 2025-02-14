@extends('vendor.vendor-master')

@section('style')
    <x-datatable.css />
    <style>
        .card img{
            height: 110px;
        }

        .font-size-14{
            font-size: 14px;
        }

        .d-flex.gap-2{
            justify-content: unset;
        }
    </style>
@endsection

@section('site-title', __('Order Details'))

@section('content')
    <div class="row g-4">
        @if($subOrders->vendor)
            <div class="col-md-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __("Vendor Information") }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="row g-4">
                            <div class="col-md-2 col-sm-2">
                                {!! render_image($subOrders?->vendor?->logo) !!}
                            </div>
                            <div class="col-md-5 col-sm-5">
                                <div class="px-0">
                                    <h5 class="dashboard__card__title">{{ $subOrders?->vendor?->business_name }}</h5>
                                    <p class="dashboard__card__para card-text mt-2">
                                        <b>{{ $subOrders?->vendor?->owner_name }}</b> ({{ $subOrders?->vendor?->username }})
                                    </p>
                                    <p class="dashboard__card__para card-text mt-2">
                                        {{ strip_tags($subOrders?->vendor?->description) }}
                                    </p>

                                    <div class="d-flex mt-2">
                                        <b>{{ __("Total Income") }} </b>
                                        <h6 style="margin-left: 10px">{{ float_amount_with_currency_symbol($subOrders?->vendor?->total_earning) }}</h6>
                                    </div>
                                </div>

                                @if(auth('vendor')->check())
                                    @if($subOrders->order_status !== 'order_cancelled')
                                        @if($subOrders->order_status == 'pending')
                                            <div class="d-flex gap-2 mt-2">
                                                <button class="btn btn-sm btn-primary approve-order-for-delivery">{{ __("Approve order for delivery") }}</button>
                                                <button class="btn btn-sm btn-danger cancel-order">{{ __("Cancel Order") }}</button>
                                            </div>
                                        @endif

                                        @if($subOrders->order_status !== 'pending' && $subOrders->order_status !== 'product_sent_to_admin')
                                            <div class="d-flex gap-2 mt-2">
                                                <button class="btn btn-sm btn-primary product-sent-to-admin">{{ __("Product sent to admin") }}</button>
                                            </div>
                                        @endif
                                    @else
                                        <div class="d-flex gap-2 mt-2">
                                            <button disabled class="btn btn-sm btn-danger">{{ __("You've cancelled this order") }}</button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-4 col-sm-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <b>{{ __("Total Product") }}</b>
                                    <h6>{{ $subOrders?->vendor?->product_count }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <b>{{ __("Total Orders") }}</b>
                                    <h6>{{ $subOrders?->vendor?->pending_order }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <b>{{ __("Pending Orders") }}</b>
                                    <h6>{{ $subOrders?->vendor?->pending_order }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <b>{{ __("Complete Orders") }}</b>
                                    <h6>{{ $subOrders?->vendor?->complete_order }}</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <h4 class="my-3 dashboard__card__title">{{ __("Order Status") }}</h4>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <b>{{ __("Your Last order status") }}</b>
                                    <h6 @class([
                                            $subOrders->order_status === 'order_cancelled' => 'text-danger',
                                        ])>
                                        {{ ucfirst(str_replace(["_","-"]," ",$subOrders->order_status)) }}
                                    </h6>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <p><b>{{__("Note:")}}</b> {{ __("You can approve order or cancel order if you do this then your order status will be changed once you approved then you can change status for order item sent to admin") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __("Order Information") }}</h4>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Sub Order ID") }}</b>
                        <h6>#{{ $subOrders->id }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Transaction ID") }}</b>
                        <h6>{{ $subOrders->order?->transaction_id }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Payment Gateway") }}</b>
                        <h6>{{ ucwords(str_replace(["_", "-"]," ",$subOrders->order?->payment_gateway)) }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Payment Status") }}</b>
                        <h6>{{ str($subOrders->order?->order_status)->ucfirst() }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Total Product") }}</b>
                        <h6>{{ $subOrders->order_item_count }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Total Cost") }}</b>
                        <h6>{{ float_amount_with_currency_symbol($subOrders->total_amount) }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Shipping Cost") }}</b>
                        <h6>{{ float_amount_with_currency_symbol($subOrders->shipping_cost) }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <b>{{ __("Tax Amount") }}</b>
                        <h6>{{ float_amount_with_currency_symbol($subOrders->tax_amount) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __("Billing Information") }}</h4>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("Name") }}</b>
                        <h6>{{ $subOrders->order?->address?->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("Email") }}</b>
                        <h6>{{ $subOrders->order?->address?->email }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("Mobile") }}</b>
                        <h6>{{ $subOrders->order?->address?->phone }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("Country") }}</b>
                        <h6>{{ $subOrders->order?->address?->country?->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("State") }}</b>
                        <h6>{{ $subOrders->order?->address?->state?->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <b>{{ __("City") }}</b>
                        <h6>{{ $subOrders->order?->address?->cityInfo?->name }}</h6>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <b>{{ __("Zip Code") }}</b>
                        <h6>{{ $subOrders->order?->address?->zipcode }}</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __("Order Items") }}</h4>
                </div>
                <div class="dashboard__card__body mt-4">
                    <div class="table-wrapper table-wrap">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>{{ __("SL NO:") }}</th>
                                    <th style="width: 60px">{{ __("Image") }}</th>
                                    <th>{{ __("Info") }}</th>
                                    <th>{{ __("QTY") }}</th>
                                    <th>{{ __("Price") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subOrders->orderItem as $item)
                                    @php
                                        $product = $subOrders->product->find($item->product_id);
                                        $variant = $subOrders->productVariant->find($item->variant_id);
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{!! render_image($product->image, class: 'w-100 h-100') !!}</td>
                                        <td>
                                            <h6>{{ $product->name }}</h6>
                                            @if($variant)
                                                <p>
                                                    @if($variant->productColor)
                                                        {{ $variant->productColor->name }},
                                                    @endif
                                                    @if($variant->productSize)
                                                        {{ $variant->productSize->name }}
                                                    @endif

                                                    @foreach($variant->attribute as $attr)
                                                        , {{ $attr->attribute_name }}: {{ $attr->attribute_value }}
                                                    @endforeach
                                                </p>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->quantity }}
                                        </td>
                                        <td>{{ float_amount_with_currency_symbol($item->price) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on("click", ".approve-order-for-delivery", function (){
            let el = $(this);

            let formData = new FormData();
            formData.append('order_status', "approved_order_status");
            formData.append('_token', "{{ csrf_token() }}");

            el.attr('disabled', true)

            Swal.fire({
                position: 'center',
                icon: "warning",
                title: "Are you sure to change this status?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Update",
            }).then(function (result){
                if (result.isConfirmed) {
                    send_ajax_request("POST",formData, "{{ route("vendor.orders.update-order-status", $subOrders->id) }}", () => {}, (response) => {
                        el.removeAttr('disabled')

                        ajax_toastr_success_message(response)

                        setTimeout(function (){
                            window.location.reload()
                        }, 1500)
                    }, (errors) => {
                        el.removeAttr('disabled')

                        prepare_errors(errors)
                    })
                }else{
                    el.removeAttr('disabled')
                }
            })
        })
        $(document).on("click", ".product-sent-to-admin", function (){
            let el = $(this);

            let formData = new FormData();
            formData.append('order_status', "product_sent_to_admin");
            formData.append('_token', "{{ csrf_token() }}");

            el.attr('disabled', true)

            Swal.fire({
                position: 'center',
                icon: "warning",
                title: "Are you sure to change this status?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Update",
            }).then(function (result) {
                if (result.isConfirmed) {
                    send_ajax_request("POST",formData, "{{ route("vendor.orders.update-order-status", $subOrders->id) }}", () => {}, (response) => {
                        el.removeAttr('disabled')

                        ajax_toastr_success_message(response)

                        setTimeout(function (){
                            window.location.reload()
                        }, 1500)
                    }, (errors) => {
                        el.removeAttr('disabled')

                        prepare_errors(errors)
                    })
                }else{
                    el.removeAttr('disabled')
                }
            });
        })
        $(document).on("click", ".cancel-order", function (){
            let el = $(this);

            let formData = new FormData();
            formData.append('order_status', "order_cancelled");
            formData.append('_token', "{{ csrf_token() }}");

            el.attr('disabled', true)

            Swal.fire({
                position: 'center',
                icon: "warning",
                title: "Are you sure to change this status?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Update",
            }).then(function (result) {
                if (result.isConfirmed) {
                    send_ajax_request("POST", formData, "{{ route("vendor.orders.update-order-status", $subOrders->id) }}", () => {
                    }, (response) => {
                        el.removeAttr('disabled')

                        ajax_toastr_success_message(response)

                        setTimeout(function () {
                            window.location.reload()
                        }, 1500)
                    }, (errors) => {
                        el.removeAttr('disabled')

                        prepare_errors(errors)
                    })
                }else{
                    el.removeAttr('disabled')
                }
            });
        })
    </script>
@endsection