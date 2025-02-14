<table class="table table-default data-table-style ">
    <thead>
        <tr>
            <th>Invoice Number</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Product Sale Price</th>
            <th>Total Order Amount</th>
            <!-- Hidden fields -->
            <th style="display: none;">Order ID</th>
            <th style="display: none;">User ID</th>
            <th style="display: none;">Product SKU</th>
            <th style="display: none;">Order Date</th>
            <th style="display: none;">Payment Method</th>
            <th style="display: none;">Shipping Address</th>
            <th style="display: none;">Order Status</th>
            <th style="display: none;">Discount Applied</th>
            <th style="display: none;">Coupon Applied</th>
            <th style="display: none;">Shipping Cost</th>
        </tr>
    </thead>
    <tbody>
        <?php if($orders): ?>
            @foreach ($orders as $order)
                @foreach ($order->orderItems as $item)
                    @php
                    $product = $item->product;
                    @endphp
                    <tr>
                        <td>{{ $order->invoice_number }}</td>
                        <td>{{ $order->address->name }}</td>
                        <td>{{ $order->address->email }}</td>
                        <td>{{ $product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $product->sale_price ?? 0 }}</td>
                        <td>{{ ($product->sale_price ?? 0) * $item->quantity}}</td>
                        <!-- Hidden fields -->
                        <td style="display: none;">{{ $order->id }}</td>
                        <td style="display: none;">{{ $order->user_id }}</td>
                        <td style="display: none;">{{ $product->inventory->sku ?? 'N/A' }}</td>
                        <td style="display: none;">{{ $order->created_at }}</td>
                        <td style="display: none;">{{ $order->payment_gateway }}</td>
                        <td style="display: none;">
                            {{ implode(', ', array_filter([$order->address->address ?? '', $order->address->city ?? '', $order->address->zip_code ?? ''])) }}
                        </td>
                        <td style="display: none;">{{ $order->order_status }}</td>
                        <td style="display: none;">{{ $order->coupon_amount ?? 0 }}</td>
                        <td style="display: none;">{{ $order->coupon ?? 'N/A' }}</td>
                        <td style="display: none;">{{ $order->subOrder->shipping_cost ?? 0 }}</td>
                        <td style="display: none;">{{ $order->payment_status ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            @endforeach
        <?php endif; ?>
    </tbody>
</table>
