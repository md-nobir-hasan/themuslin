<table class="table">
    <thead>
        <tr>
            <th>{{ __('Order') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Order Status') }}</th>
            <th>{{ __('Payment Status') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($all_orders as $order)
            <tr class="completed">
                <td class="order-numb">
                    #{{ $order->id }}
                </td>
                <td class="date">
                    {{ $order->created_at->format('F d, Y') }}
                </td>
                <td class="status">
                   
                    @if ($order->order_status == 'complete')
                        <span class="badge bg-success px-2 py-1">{{ __('Complete') }}</span>
                    @elseif ($order->order_status == 'canceled')
                        <span class="badge bg-danger px-2 py-1">{{ __('Canceled') }}</span>
                    @elseif ($order->order_status == 'failed')
                        <span class="badge bg-danger px-2 py-1 text-capitalize">{{ __('Failed') }}</span>
                    @elseif ($order->order_status == 'rejected')
                        <span class="badge bg-danger px-2 py-1 text-capitalize">{{ __('Rejected') }}</span>
                    @elseif ($order->order_status == 'pending')
                        <span class="badge bg-warning px-2 py-1 text-capitalize">{{ __('Pending') }}</span>
                    @endif


                </td>
                <td class="status">
                    @if ($order->payment_status == 'complete')
                        <span class="badge bg-success px-2 py-1">{{ __('Complete') }}</span>
                    @elseif ($order->payment_status == 'pending')
                        <span class="badge bg-warning px-2 py-1">{{ __('Pending') }}</span>
                    @elseif ($order->payment_status == 'canceled')
                        <span class="badge bg-danger px-2 py-1">{{ __('Canceled') }}</span>
                    @endif
                </td>
                <td class="amount">
                    {{ float_amount_with_currency_symbol($order->paymentMeta->total_amount ?? 0) }}
                </td>
                <td class="table-btn">
                    <div class="btn-wrapper d-flex flex-wrap gap-2">
                        @can('orders-generate-invoice')
                            <a href="{{ route('admin.orders.generate.invoice', $order->id) }}"
                                class="btn btn-warning rounded-btn">
                                <i class="las la-file-invoice"></i>
                            </a>
                        @endcan
                        @can('orders-download-invoice')
                            <!-- <a href="{{ route('admin.orders.download.invoice', $order->id) }}"
                                class="btn btn-warning rounded-btn">
                                <i class="las la-download"></i>
                            </a> -->
                        @endcan
                        @can('orders-update')
                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                class="btn btn-primary rounded-btn">
                                <i class="las la-pen-nib"></i>
                            </a>
                        @endcan
                        @can('orders-details')
                            <a href="{{ route('admin.orders.order.details', $order->id) }}"
                                class="btn btn-secondary rounded-btn">
                                {{ __('view details') }}
                            </a>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>