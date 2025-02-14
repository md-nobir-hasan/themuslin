<table class="customs-tables pt-4 position-relative" id="myTable">
    <div class="load-ajax-data"></div>
    <thead class="head-bg">
        <tr>
            <th class="check-all-rows p-3">
                <div class="mark-all-checkbox text-center">
                    <input type="checkbox" class="all-checkbox">
                </div>
            </th>
            <th> {{ __('id') }} </th>
            <th> {{ __('Name') }} </th>
            <th> {{ __('Categories') }} </th>
            <th> {{ __('Stock Qty') }} </th>
            <th> {{ __('Status') }} </th>
            <th> {{ __('Sort Order') }} </th>
            <th> {{ __('Actions') }} </th>
        </tr>
    </thead>
    <tbody>
        @forelse($products["items"] as $product)
            <tr class="table-cart-row" data-product-id-row="{{ $product->id }}">
                <td data-label="Check All" class="text-center">
                    @can('product-bulk-destroy')
                        <x-product::table.bulk-delete-checkbox :id="$product->id" />
                    @endcan
                </td>
                <td data-label="Check All" class="text-center">
                    {{ $product->id }}
                </td>

                <td class="product-name-info">
                    <div class="d-flex gap-2">
                        <div class="logo-brand position-relative">
                            <div class="image-box">
                                {!! render_image($product->image) !!}
                            </div>

                            @if(false)
                                <button data-product-id="{{ $product->id }}" data-bs-target="#mediaUpdateModalId"
                                    data-bs-toggle="modal"
                                    class="product-image-change-action-button btn btn-sm btn-outline-primary position-absolute top-0 left-0 rounded-circle">
                                    <i class="las la-pen"></i>
                                </button>
                            @endif
                        </div>
                        <div class="product-summary">
                            <p class="font-weight-bold mb-1">{{ $product->name }}</p>
                            <p>{{ Str::words($product->summary, 5) }}</p>
                        </div>
                    </div>
                </td>

                
                <td class="price-td" data-label="Name">
                    <span class="category-field">
                        @if ($product?->category?->name)
                            <b> {{ __('Category') }}: </b>
                        @endif
                        {{ $product?->category?->name }}
                    </span> <br>
                    <span class="category-field">
                        @if ($product?->subCategory?->name)
                            <b> {{ __('Sub Category') }}: </b>
                        @endif
                        {{ $product?->subCategory?->name }}
                    </span><br>
                </td>

                <td class="price-td" data-label="Quantity">
                    <span class="quantity-number"> {{ $product?->inventory?->stock_count }}</span>
                </td>



                <td data-label="Status">
                    @can('product-status')
                        <x-product::table.status :statuses="$statuses" :statusId="$product?->status_id" :id="$product->id" />
                    @endcan
                </td>

                 <td>
                    <div class="d-flex gap-2">
                        <span class="category-field">
                       
                            {{ $product->sort_order }}
                        </span>
                    </div>
                </td>


                <td data-label="Actions">
                    <div class="action-icon">
                        <a href="{{  route('product.details', $product->slug) }}" target="_blank" class="icon eye">
                            <i class="las la-eye"></i>
                        </a>

                        @can('product-update')
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="icon edit">
                                <i class="las la-pen-alt"></i>
                            </a>
                        @endcan

                        @can('product-clone')
                            <a href="{{ route('admin.products.clone', $product->id) }}" class="icon clone">
                                <i class="las la-copy"></i>
                            </a>
                        @endcan

                        @can('product-destroy')
                            <a data-product-url="{{ route('admin.products.destroy', $product->id) }}" href="#1"
                                class="delete-row icon deleted">
                                <i class="las la-trash-alt"></i>
                            </a>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-warning text-center">{{ __('No Product Found') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="custom-pagination-wrapper">
    <div class="pagination-info d-flex gap-3">
        <p>
            <strong>{{ __('Per Page:') }}</strong>
            <span>{{ $products['per_page'] }}</span>
        </p>
        <p>
            <strong>{{ __('From:') }}</strong>
            <span>{{ $products['from'] }}</span>
            <strong> {{ __('To:') }}</strong>
            <span>{{ $products['to'] }}</span>
        </p>
        <p>
            <strong>{{ __('Total Page:') }}</strong>
            <span>{{ $products['total_page'] }}</span>
        </p>
        <p>
            <strong>{{ __('Total Products:') }}</strong>
            <span>{{ $products['total_items'] }}</span>
        </p>
    </div>

    <div class="pagination">
        <ul class="pagination-list">
            @foreach ($products['links'] as $link)
                <li><a href="{{ $link }}"
                        class="page-number {{ $loop->iteration == $products['current_page'] ? 'current' : '' }}">{{ $loop->iteration }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
