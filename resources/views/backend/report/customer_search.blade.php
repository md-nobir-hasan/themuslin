<table class="table table-default data-table-style">
    <thead>
        <tr>
            <th>Customer Id</th>
            <th style="display: none">First Name</th>
            <th style="display: none">Last Name</th>
            <th>Full Name</th>
            <th>Email Address</th>
            <th>Phone Number</th>
            <th style="display: none">Registration Date</th>
            <th>Total Orders</th>
            <th>Total Spent</th>
            <th style="display: none">Last Purchase Date</th>
            <th style="display: none">Shipping Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $customer)
            @php
                $total_spent = 0;
                $lastDate = null;
                $shippingAddress = $customer->shippingAddress;
            @endphp

            @foreach ($customer->orders as $order)
                @foreach ($order->subOrders as $subOrder)
                    @php
                        $total_spent += $subOrder->total_amount + $subOrder->shipping_cost;
                    @endphp
                @endforeach

                @php
                    if (is_null($lastDate) || $order->created_at > $lastDate) {
                        $lastDate = $order->created_at;
                    }
                @endphp
            @endforeach

            <tr>
                <td>{{ $customer->id }}</td>
                <td style="display: none">{{ $customer->first_name }}</td>
                <td style="display: none">{{ $customer->last_name }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td style="display: none">{{ $customer->created_at }}</td>
                <td>{{ $customer->orders->count() }}</td>
                <td>{{ $total_spent }}</td>
                <td style="display: none">{{ $lastDate }}</td>
                <td style="display: none">
                    {{ implode(', ', array_filter([
                        $shippingAddress->address ?? '',
                        $shippingAddress->city ?? '',
                        $shippingAddress->zip_code ?? ''
                    ])) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
