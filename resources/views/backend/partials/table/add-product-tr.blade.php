@if ($product)
<tr class="product_row">
    <x-table.td-image :image="$product->image" />
    <td class="product_name">
        <b>{{ $product->title }}</b>
        <input type="hidden" name="products[{{ $count }}][]" value="{{ $product->id }}">
        <input type="hidden" name="count_arr[{{ $count }}]" value="{{ $count }}">
        @if ($product->attributes)
            @php $attributes_arr = $product->attributes ? json_decode($product->attributes, true) : []; @endphp
            <ul class="ml-2">
                @if(count($attributes_arr))
                    @foreach ($attributes_arr as $attr_id => $attributes)
                        <b class="text-secondary">{{ $attributes[0]['type'] }}</b>
                        <ul class="product_attribute_list">
                            @foreach ($attributes as $attribute)
                            @php
                                $additional_price = isset($attribute['additional_price']) ? float_amount_with_currency_symbol($attribute['additional_price']) : 0;
                                $attribute_arr = [
                                    'id' => $product->id,
                                    $attribute['type'] => $attribute['name'],
                                    'price' => $attribute['additional_price']
                                ];
                            @endphp
                            <li class="ml-2">
                                <input type="radio" 
                                        name="product_attributes[{{ $count }}][{{ $product->id }}][{{ $attribute['type'] }}]" 
                                        id="product_attr[{{ $product->id }}]" 
                                        data-add-price="{{ $additional_price }}" 
                                        value="{{ json_encode($attribute_arr) }}"
                                        required
                                />
                                {{ $attribute['name'] }} 
                                @if($attribute['additional_price'] > 0) +{{ $additional_price }} @endif
                            </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif
            </ul>
        @else
            @php
                $additional_price = isset($attribute['additional_price']) ? float_amount_with_currency_symbol($attribute['additional_price']) : 0;
                $attribute_arr = [
                    'price' => $product->sale_price ?? 0
                ];
            @endphp
            <input type="hidden" 
                name="product_attributes[{{ $count }}][{{ $product->id }}]" 
                id="product_attr[{{ $product->id }}]" 
                value="{{ json_encode($attribute_arr) }}"
                required
            />
        @endif
    </td>
    <td><input class="form-control" type="number" name="products_count[{{ $count }}]" id="" required></td>
    <td>{{ float_amount_with_currency_symbol($product->sale_price) }}</td>
    <td>
        <x-table.btn.swal.delete :route="route('homepage')" :selector="'delete_order_product'" />
    </td>
</tr>
@endif
