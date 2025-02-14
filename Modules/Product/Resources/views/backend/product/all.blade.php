@extends('backend.admin-master')
@section('site-title')
    {{ __('All Product') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Products') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('product-delete')
                                <x-bulk-action.dropdown />
                                <div class="mb-3">
                                    <form method="GET">
                                        <div class="d-flex">
                                            <input type="text" name="title" value="{{ request()->title ?? '' }}"
                                                placeholder="Search product." class="form-control" />
                                            <button type="submit" class="btn btn-sm px-5 btn-info">{{ __('Search') }}</button>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                            @can('product-list')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.products.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New Product') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="8">
                                            {!! $all_products->links() !!}
                                        </td>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($all_products as $product)
                                        <tr>
                                            <x-bulk-action.td :id="$product->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ optional($product->category)->title }}</td>
                                            <td>{{ $product->price }}</td>
                                            <td>
                                                <x-status-span :status="$product->status" />
                                            </td>
                                            <x-table.td-image :image="$product->image" />
                                            <td>
                                                @can('product-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.products.delete', $product->id)" />
                                                @endcan
                                                @can('product-edit')
                                                    <x-table.btn.edit :route="route('admin.products.edit', $product->id)" />
                                                @endcan
                                                @can('product-clone')
                                                    <x-table.btn.clone :route="route('admin.products.clone', $product->id)" :id="$product->id" />
                                                @endcan
                                                @can('product-view')
                                                    <x-table.btn.view :route="route('frontend.products.single', $product->slug)" />
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
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
    {{--    <x-datatable.js /> --}}
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.products.bulk.action')" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.product_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let modal = $('#product_edit_modal');

                modal.find('#product_id').val(id);
                modal.find('#edit_name').val(name);

                modal.show();
            });
        });
    </script>
@endsection
