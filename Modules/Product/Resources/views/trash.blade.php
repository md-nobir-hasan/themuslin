@extends('backend.admin-master')
@section('site-title')
    {{ __('Trashed Products') }}
@endsection

@section('style')
    <x-product::variant-info.css />
    <style>
        .float-left {
            float: left;
        }
    </style>
@endsection
@section('content')
    @php
        $allProduct = '';
        if (!$products->isEmpty()) {
            if (count($products) > 1) {
                $allProduct = $products->pluck('id')->toArray();
                $allProduct = implode('|', $allProduct);
            } else {
                $allProduct = current(current($products))->id;
            }
        }
    @endphp

    <div class="dashboard-recent-order">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h3 class="dashboard__card__title">{{ __('Product Trash') }}</h3>
                        <div class="dashboard__card__header__right">
                            <div class="dashboard__card__header__right__item">
                                @can('product-trash-bulk-action')
                                    <x-product::table.bulk-action />
                                @endcan
                            </div>
                            <div class="dashboard__card__header__right__item">
                                @can('product-trash-empty')
                                    <a href="#1" class="cmn_btn btn_bg_danger delete-all"
                                        data-product-delete-all-url="{{ route('admin.products.trash.empty') }}">
                                        {{ __('Empty Trash') }}
                                    </a>
                                @endcan
                            </div>
                            <div class="dashboard__card__header__right__item">
                                <div class="btn-wrapper">
                                    @can('product-all')
                                        <a class="cmn_btn btn_bg_profile"
                                            href="{{ route('admin.products.all') }}">{{ __('Back') }}</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-responsive table-wrap table-responsive--md">
                            <table class="table custom--table" id="myTable">
                                <thead class="head-bg">
                                    <tr>
                                        @can('product-trash-bulk-action')
                                            <th class="check-all-rows">
                                                <div class="mark-all-checkbox">
                                                    <input type="checkbox" class="all-checkbox">
                                                </div>
                                            </th>
                                        @endcan

                                        <th class="max-width-300"> {{ __('Name') }} </th>
                                        <th> {{ __('Brand') }} </th>
                                        <th> {{ __('Categories') }} </th>
                                        <th> {{ __('Stock Qty') }} </th>
                                        <th> {{ __('Actions') }} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr class="table-cart-row">
                                            @can('product-trash-bulk-action')
                                                <td data-label="Check All">
                                                    <x-product::table.bulk-delete-checkbox :id="$product->id" />
                                                </td>
                                            @endcan

                                            <td class="product-name-info max-width-300">
                                                <div class="logo-brand-wrapper">
                                                    <div class="logo-brand">
                                                        {!! render_image($product->image) !!}
                                                    </div>
                                                    <div class="logo-brand-contents">
                                                        <b class="">{{ $product->name }}</b>
                                                        <p>{{ Str::words($product->summary, 10) }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td data-label="Image" class="max-width-300">
                                                <div class="logo-brand-wrapper">
                                                    <div class="logo-brand product-brand">
                                                        {!! render_image($product?->brand?->logo) !!}
                                                    </div>
                                                    <div class="logo-brand-contents">
                                                        <b class="">{{ $product?->brand?->name }}</b>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="price-td" data-label="Name">
                                                @if ($product?->category?->name)
                                                    <b> {{ __('Category') }}: </b>
                                                @endif{{ $product?->category?->name }} <br>
                                                @if ($product?->subCategory?->name)
                                                    <b> {{ __('Sub Category') }}: </b>
                                                @endif{{ $product?->subCategory?->name }} <br>
                                            </td>

                                            <td class="price-td" data-label="Quantity">
                                                <span class="quantity-number">
                                                    {{ $product?->inventory?->stock_count }}</span>
                                            </td>

                                            <td data-label="Actions">
                                                <div class="action-icon">
                                                    @can('product-trash-restore')
                                                        <a href="{{ route('admin.products.trash.restore', $product->id) }}"
                                                            class="product-restore btn btn-success btn-sm"> {{ __('Restore') }}
                                                        </a>
                                                    @endcan
                                                    @can('product-trash-delete')
                                                        <a data-product-delete-url="{{ route('admin.products.trash.delete', $product->id) }}"
                                                            href="#1" class="product-delete btn btn-danger btn-sm">
                                                            {{ __('Delete') }}
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-warning">
                                                {{ __('No Trashed Product Available') }} </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @can('product-trash-bulk-action')
        <x-product::table.bulk-action-js :url="route('admin.products.trash.bulk.destroy')" />
    @endcan
    <script>
        $(document).on("click", ".delete-all", function(e) {
            e.preventDefault();
            let el = $(this);
            let delete_url = el.data('product-delete-all-url');
            let allIds = '{{ $allProduct }}';

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (allIds != '') {
                        $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i>{{ __('Deleting') }}');
                        $.ajax({
                            'type': "POST",
                            'url': delete_url,
                            'data': {
                                _token: "{{ csrf_token() }}",
                                ids: allIds
                            },
                            success: function(data) {
                                toastr.success('Trash in Empty');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000)
                            }
                        });
                    }
                }
            });
        });

        $(document).on("click", ".product-delete", function(e) {
            e.preventDefault();
            let delete_url = $(this).data('product-delete-url');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.replace(delete_url);
                }
            });
        });
    </script>
@endsection
