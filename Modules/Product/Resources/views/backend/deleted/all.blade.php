@extends('backend.admin-master')
@section('site-title')
    {{ __('All Deleted Product') }}
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
                        <h4 class="dashboard__card__title">{{ __('All Deleted Product') }}</h4>
                        @can('deleted-product-delete')
                            <x-bulk-action.dropdown />
                        @endcan
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
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_deleted_products as $product)
                                        <tr>
                                            <x-bulk-action.td :id="$product->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ optional($product->category)->title }}</td>
                                            <td>{{ amount_with_currency_symbol($product->price) }}</td>
                                            <x-table.td-image :image="$product->image" />
                                            <td>
                                                @can('deleted-product-restore')
                                                    <a class="btn btn-success btn-xs mb-2 me-1 swal-restore" title="Restore"
                                                        href="#1"
                                                        data-route="{{ route('admin.products.deleted.restore', $product->id) }}">
                                                        <i class="ti-back-right"></i>
                                                    </a>
                                                @endcan
                                                @can('deleted-product-delete')
                                                    <x-table.btn.swal.delete :route="route(
                                                        'admin.products.deleted.permanent.delete',
                                                        $product->id,
                                                    )" />
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
    <x-datatable.js />
    <x-table.btn.swal.js />
    @can('deleted-product-delete')
        <x-bulk-action.js :route="route('admin.products.deleted.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function() {
            $('.swal-restore').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "{{ __('Do you want to restore this item?') }}",
                    showCancelButton: true,
                    confirmButtonText: 'Restore',
                    confirmButtonColor: '#43A047',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let route = $(this).data('route');
                        $.post(route, {
                            _token: '{{ csrf_token() }}'
                        }).then(function(data) {
                            if (data) {
                                Swal.fire('Restored!', '', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
