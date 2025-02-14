<div class="table-wrap table-responsive all-user-campaign-table">
    <div class="order-history-inner text-center">
        <table class="table">
            <thead>
            <tr>
                <th>
                    {{ __('Order') }}
                </th>
                <th>
                    {{ __('Date') }}
                </th>
                <th>
                    {{ __('Status') }}
                </th>
                <th>
                    {{ __('Amount') }}
                </th>
                <th>
                    {{ __('Action') }}
                </th>
            </tr>
            </thead>
            <tbody>
                @foreach ($allOrders as $order)
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
                            @elseif ($order->order_status == 'pending')
                                <span class="badge bg-warning px-2 py-1">{{ __('Pending') }}</span>
                            @elseif ($order->order_status == 'canceled')
                                <span class="badge bg-danger px-2 py-1">{{ __('Canceled') }}</span>
                            @endif
                        </td>
                        <td class="amount">
                            {{ float_amount_with_currency_symbol($order->paymentMeta?->total_amount) }}
                        </td>
                        <td class="table-btn">
                            <div class="btn-wrapper">
                                @if ($order->is_delivered_count > 0)
                                    <a href="{{ route('user.product.order.refund', $order->id) }}"
                                       class="btn btn-danger btn-sm rounded-btn">
                                        {{ __('Request refund') }}</a>
                                @endif

                                <a href="{{ route('user.product.order.details', $order->id) }}"
                                   class="btn btn-secondary btn-sm rounded-btn"> {{ __('view details') }}</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>