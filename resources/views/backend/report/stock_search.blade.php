<table class="table table-default data-table-style ">
    <thead>
        <th>{{ __('ID') }}</th>
        <th>{{ __('Name') }}</th>
        <th>{{ __('SKU') }}</th>
        <th>Stock</th>
        <th>Sold</th>
        <th>Category</th>
        <th>Sale Price</th>
    </thead>
    <tbody>
        <?php
            if($products)  { 
        ?>
        @foreach ($products as $product)
            @if ($product->inventoryDetail->isNotEmpty())
                @foreach ($product->inventoryDetail as $variant)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->inventory->sku }}</td>
                        <td>{{ $variant->stock_count ?? 0 }}</td>
                        <td>{{ $variant->sold_count ?? 0 }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ float_amount_with_currency_symbol($product->sale_price ?? 0) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->inventory->sku }}</td>
                    <td>{{ $product->inventory->stock_count ?? 0 }}</td>
                    <td>{{ $product->inventory->sold_count ?? 0 }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{  float_amount_with_currency_symbol($product->sale_price ?? 0) }}</td>
                </tr>
            @endif
        @endforeach
        <?php
            }
        ?>
    </tbody>
</table>