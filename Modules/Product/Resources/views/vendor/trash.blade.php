@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Trashed Products') }}
@endsection

@section('style')
    <x-product::variant-info.css/>
    <style>
        .float-left {
            float: left;
        }
    </style>
@endsection
@section('content')
    @php
        $allProduct = '';
            if (!$products->isEmpty())
                {
                    if (count($products) > 1)
                        {
                            $allProduct = $products->pluck('id')->toArray();
                            $allProduct = implode('|',$allProduct);
                        } else {
                            $allProduct = current(current($products))->id;
                        }
                }
    @endphp

    <div class="dashboard-recent-order">
        <div class="row">
            <x-flash-msg/>
            <div class="col-lg-12 mt-4">
                <div class="recent-order-wrapper dashboard-table bg-white">
                    <div class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3>{{__('Product Trash')}}</h3>
                        <x-product::table.bulk-action/>

                        <a href="#1" class="btn btn-danger btn-sm delete-all"
                           data-product-delete-all-url="{{ route("vendor.products.trash.empty") }}"> {{__('Empty Trash')}}</a>
                        <div class="btn-wrapper">
                            <a class="btn btn-primary btn-sm"
                               href="{{route('vendor.products.all')}}">{{__('Back')}}</a>
                        </div>
                    </div>
                    <div class="table-responsive table-responsive--md mt-4">
                        <table class="custom--table pt-4" id="myTable">
                            <thead class="head-bg">
                            <tr>
                                <th class="check-all-rows">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>

                                <th> {{__("Name")}} </th>
                                <th> {{__("Brand")}} </th>
                                <th> {{__("Categories")}} </th>
                                <th> {{__("Stock Qty")}} </th>
                                <th> {{__("Actions")}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($products as $product)
                                <tr class="table-cart-row">
                                    <td data-label="Check All">
                                        <x-product::table.bulk-delete-checkbox :id="$product->id"/>
                                    </td>

                                    <td class="product-name-info">
                                        <div class="d-flex gap-2">
                                            <div class="logo-brand">
                                                {!! render_image_markup_by_attachment_id($product->image_id) !!}
                                            </div>
                                            <b class="">{{ $product->name }}</b>
                                            <p>{{Str::words($product->summary, 10)}}</p>
                                        </div>
                                    </td>

                                    <td data-label="Image">
                                        <div class="d-flex gap-2">
                                            <div class="logo-brand product-brand">
                                                {!! render_image_markup_by_attachment_id($product?->brand->image_id) !!}
                                            </div>
                                            <b class="">{{ $product?->brand?->name }}</b>
                                        </div>
                                    </td>

                                    <td class="price-td" data-label="Name">
                                        @if($product?->category?->name)
                                            <b> {{__('Category')}}: </b>
                                        @endif{{ $product?->category?->name }} <br>
                                        @if($product?->subCategory?->name)
                                            <b> {{__('Sub Category')}}: </b>
                                        @endif{{ $product?->subCategory?->name }} <br>
                                    </td>

                                    <td class="price-td" data-label="Quantity">
                                        <span class="quantity-number"> {{ $product?->inventory?->stock_count }}</span>
                                    </td>

                                    <td data-label="Actions">
                                        <div class="action-icon">
                                            <a href="{{ route("vendor.products.trash.restore", $product->id) }}"
                                               class="product-restore btn btn-success btn-sm"> {{__('Restore')}} </a>
                                            <a data-product-delete-url="{{ route("vendor.products.trash.delete", $product->id) }}"
                                               href="#1"
                                               class="product-delete btn btn-danger btn-sm"> {{__('Delete')}} </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
                                        class="text-center text-warning"> {{__('No Trashed Product Available')}} </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-product::table.bulk-action-js :url="route('vendor.products.trash.bulk.destroy')"/>
    <script>
        $(document).on("click", ".delete-all", function (e) {
            e.preventDefault();
            let el = $(this);
            let delete_url = el.data('product-delete-all-url');
            let allIds = '{{$allProduct}}';

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
                        $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i>{{__("Deleting")}}');
                        $.ajax({
                            'type': "POST",
                            'url': delete_url,
                            'data': {
                                _token: "{{csrf_token()}}",
                                ids: allIds
                            },
                            success: function (data) {
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

        $(document).on("click", ".product-delete", function (e) {
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
