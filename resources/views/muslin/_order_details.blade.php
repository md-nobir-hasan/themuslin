<div class="invoice__top">
    <div class="invoice__top__number">
        <h3>#{{ $payment_details->invoice_number }}</h3>
        <button class="status-btn">
            @if ($payment_details->order_status == 'complete')
                <span>{{ __('Complete') }}</span>
            @elseif ($payment_details->order_status == 'pending')
                <span>{{ __('Pending') }}</span>
            @elseif ($payment_details->order_status == 'canceled')
                <spa>{{ __('Canceled') }}</span>
            @elseif ($payment_details->order_status == 'processing')
                <span>{{ __('Processing') }}</span>
            @elseif ($payment_details->order_status == 'canceled')
                <span>{{ __('Canceled') }}</span>
            @elseif ($payment_details->order_status == 'rejected')
                <span>{{ __('Rejected') }}</span>
            @endif  
        </button>
    </div>
    <div class="invoice__top__date">
        <span>Date</span>
        <p>{{ $payment_details->created_at->format('d.m.Y') }}</p>
    </div>
    <button class="close-invoice">
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L11 11" stroke="#fff" stroke-linecap="round" />
            <path d="M1 11L11 1" stroke="#fff" stroke-linecap="round" />
        </svg>
    </button>
</div>

<div class="invoice__details">
    <div class="invoice__details__left">
        <h6 style="font-weight: 500;">User Information</h6>
        <p>{{ $payment_details->address->name }}</p>
        <p>{{ $payment_details->address->phone }}</p>
        <p>{{ $payment_details->address->email }}</p>
    </div>
    <div class="invoice__details__right">
        <h6 style="font-weight: 500;">Address</h6>
        <p> {{ $payment_details->address->address }}</p>
        <p>City: {{ $payment_details->address->city }}</p>
        <p>Zipcode: {{ $payment_details->address->zip_code }}</p>
        <p>State: {{ $payment_details->address->state->name }}</p>
        <p>Country: {{ $payment_details->address->country->name }}</p>
    </div>
    <div class="invoice__details__right">
        <h6 style="font-weight: 500;">Payment Method</h6>
        <p>{{ $payment_details->payment_gateway == 'cash_on_delivery' ? 'Cash on Delivery' : 'Online Payment' }}</p>
        <h6 style="font-weight: 500;">Payment Status</h6>

        <p>{{ $payment_details->payment_status == 'complete' ? 'Paid' : 'Pending' }}</p>
        
    </div>
</div>


<div class="product-list">
    <h6 style="font-weight: 500;">Product List</h6>

    @php 

        $order = !empty($orders[0]) ? $orders[0] : [];

    @endphp
    
    <!-- @foreach ($orders as $order) -->
        
        @foreach ($order?->orderItem as $orderItem)
            @php
                $prd_image = !empty($orderItem->product->image) ? $orderItem->product->image : null;

                if (!empty($orderItem->variant?->attr_image)) {
                    $prd_image = $orderItem->variant->attr_image;
                }
            @endphp

            <!-- product one -->
            <div class="product-list__item">
                <div class="product-list__item__left">
                    {!! render_image($prd_image) !!}

                    <div class="product-list__item__left__desc">
                        <p>{{ !empty($orderItem->product->name) ? $orderItem->product->name : '' }}</p>
                        <ul>
                            @if($orderItem?->variant?->productColor)
                                <li>
                                    Color: 
                                    <span>
                                        {{ $orderItem->variant->productColor->name }}
                                    </span>
                                </li>
                            @endif

                            @if($orderItem?->variant?->productSize)
                                <li>
                                    Size: 
                                    <span>
                                        {{ $orderItem->variant->productSize->name }}
                                    </span>
                                </li>
                            @endif

                        </ul>
                        <h6>Tk {{ number_format($orderItem->price, 2) }}</h6>
                        <p>SKU {{ $orderItem->product->inventory->sku }}</p>
                    </div>
                </div>
                <div class="product-list__item__right">
                    <p>x {{ $orderItem->quantity ?? '0' }}</p>
                </div>
            </div>
        @endforeach

    <!-- @endforeach -->

    <div class="product-list__pricing">
        <ul>
            <li>
                <p>Product Price</p>
                <p>Tk {{ number_format($payment_details->paymentMeta?->sub_total, 2) }}</p>
            </li>
            <li>
                <p>Discount Amount</p>
                <p>Tk {{ number_format($payment_details->paymentMeta?->coupon_amount, 2) }}</p>
            </li>
            <li>
                <p>Delivery Fee</p>
                <p>Tk {{ number_format($payment_details->paymentMeta?->shipping_cost, 2) }}</p>
            </li>
            <li>
                <p>Grand Total</p>
                <p>Tk {{ number_format($payment_details->paymentMeta?->total_amount , 2)}}</p>
            </li>
        </ul>
    </div>
</div>


