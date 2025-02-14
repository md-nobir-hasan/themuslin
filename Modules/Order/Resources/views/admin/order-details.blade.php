@extends('backend.admin-master')

@section('style')
    <x-datatable.css />
    <style>
        .card img {
            height: 110px;
        }

        .font-size-14 {
            font-size: 14px;
        }
    </style>
@endsection

@section('site-title', __('Order Details'))

@section('content')
    <div class="row g-4">
        @if ($subOrders->vendor)
            <div class="col-md-12">
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Vendor Information') }}</h4>

                        <div class="d-flex justify-content-between gap-2">
                            <b>{{ __("This order status") }}</b>
                            <span class="badge {{ $subOrders->order_status === 'order_cancelled' ? 'bg-danger' : 'bg-dark' }}">
                                {{ ucfirst(str_replace(["_","-"]," ",$subOrders->order_status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="dashboard__card__body">
                        <div class="row g-4 justify-content-between">
                            <div class="col-xxl-6 col-md-6">
                                <div class="subOrder__single">
                                    <div class="subOrder__single__flex">
                                        <div class="subOrder__single__thumb">
                                            {!! render_image($subOrders->vendor->logo) !!}
                                        </div>
                                        <div class="subOrder__single__contents">
                                            <h5 class="dashboard__card__title">{{ $subOrders->vendor->business_name }}</h5>
                                            <p class="subOrder__single__title mt-2">
                                                <strong>{{ $subOrders->vendor->owner_name }}</strong>
                                                ({{ $subOrders->vendor->username }})
                                            </p>
                                            <p class="subOrder__single__para">
                                                {{ strip_tags($subOrders->vendor->description) }}
                                            </p>

                                            <div class="subOrder__single__item no__between">
                                                <span class="subOrder__single__item__left">{{ __('Total Income') }} </span>
                                                <h6 class="subOrder__single__item__right">
                                                    {{ float_amount_with_currency_symbol($subOrders->vendor->total_earning) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-md-6">
                                <div class="subOrder__single__item">
                                    <span class="subOrder__single__item__left">{{ __('Total Product') }}</span>
                                    <h6 class="subOrder__single__item__right">{{ $subOrders->vendor->product_count }}</h6>
                                </div>
                                <div class="subOrder__single__item">
                                    <span class="subOrder__single__item__left">{{ __('Total Orders') }}</span>
                                    <h6 class="subOrder__single__item__right">{{ $subOrders->vendor->pending_order }}</h6>
                                </div>
                                <div class="subOrder__single__item">
                                    <span class="subOrder__single__item__left">{{ __('Pending Orders') }}</span>
                                    <h6 class="subOrder__single__item__right">{{ $subOrders->vendor->pending_order }}</h6>
                                </div>
                                <div class="subOrder__single__item">
                                    <span class="subOrder__single__item__left">{{ __('Complete Orders') }}</span>
                                    <h6 class="subOrder__single__item__right">{{ $subOrders->vendor->complete_order }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <div class="dashboard__card card__two">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Order Information') }}</h4>
                </div>
                <div class="dashboard__card__body">
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Sub Order ID') }}</span>
                        <span class="subOrder__single__item__right">#{{ $subOrders->id }}</span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Transaction ID') }}</span>
                        <span class="subOrder__single__item__right">{{ $subOrders->order->transaction_id }}</span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Payment Gateway') }}</span>
                        <span
                            class="subOrder__single__item__right">{{ ucwords(str_replace(['_', '-'], ' ', $subOrders->order->payment_gateway)) }}</span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Payment Status') }}</span>
                        <span
                            class="subOrder__single__item__right">{{ str($subOrders->order->order_status)->ucfirst() }}</span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Total Product') }}</span>
                        <span class="subOrder__single__item__right">{{ $subOrders->order_item_count }}</span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Total Cost') }}</span>
                        <span
                            class="subOrder__single__item__right">{{ float_amount_with_currency_symbol($subOrders->total_amount + $subOrders->shipping_cost + $subOrders->tax_amount) }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Shipping Cost') }}</span>
                        <span
                            class="subOrder__single__item__right">{{ float_amount_with_currency_symbol($subOrders->shipping_cost) }}</span>
                    </div>
                    <!-- <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Tax Amount') }}</span>
                        @if ($subOrders->tax_type == 'inclusive_price')
                            <span class="subOrder__single__item__left">{{ __('Inclusive Tax') }}</span>
                        @else
                            <span
                                class="subOrder__single__item__right">{{ float_amount_with_currency_symbol($subOrders->tax_amount) }}</span>
                        @endif
                    </div> -->
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard__card card__two">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Billing Information') }}</h4>
                </div>
                <div class="dashboard__card__body">
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Name') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->name }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Email') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->email }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Mobile') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->phone }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Country') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->country?->name }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('State') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->state?->name }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('City') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->city }}
                        </span>
                    </div>
                    <div class="subOrder__single__item">
                        <span class="subOrder__single__item__left">{{ __('Zip Code') }}</span>
                        <span class="subOrder__single__item__right">
                            {{ $subOrders->order?->address?->zipcode }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="dashboard__card card__two">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('Order Items') }}</h4>
                </div>
                <div class="dashboard__card__body">
                    <div class="table-wrapper table-wrap">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>{{ __('SL NO:') }}</th>
                                    <th style="width: 60px">{{ __('Image') }}</th>
                                    <th>{{ __('Info') }}</th>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('QTY') }}</th>
                                    <th>{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subOrders->orderItem as $item)
                                    @php
                                        $product = $subOrders->product->find($item->product_id);
                                        $variant = $subOrders->productVariant->find($item->variant_id);
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="table-image">{!! render_image($product->image) !!}</div>
                                        </td>
                                        <td>
                                            <p>{{ $product->name }}</p>
                                            @if ($variant)
                                                <p>
                                                    @if ($variant->productColor)
                                                        {{ $variant->productColor->name }},
                                                    @endif
                                                    @if ($variant->productSize)
                                                        {{ $variant->productSize->name }}
                                                    @endif

                                                    @foreach ($variant->attribute as $attr)
                                                        , {{ $attr->attribute_name }}: {{ $attr->attribute_value }}
                                                    @endforeach
                                                </p>
                                            @endif
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{ route('product.details',$product->slug) }}">{{ $product->inventory->sku }}</a>
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
@endsection
