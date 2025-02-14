@extends('backend.admin-master')
@section('site-title')
    {{ __('Shipping Methods') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-error-msg />
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Shipping Methods') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('shipping-method-delete')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('shipping-method-list')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.shipping.method.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Create Shipping Method') }}</a>
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
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Zone') }}</th>
                                    <th>{{ __('Tax Status') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Minimum Order') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_shipping_methods as $method)
                                        <tr>
                                            <x-bulk-action.td :id="$method->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($method->options)->title }}</td>
                                            <td>{{ optional($method->zone)->name }}</td>
                                            <td>{{ optional($method->options)->tax_status ? __('Taxable') : __('None') }}
                                            </td>
                                            <td>
                                                <x-status-span :status="optional($method->options)->status == 1
                                                    ? 'publish'
                                                    : 'draft'" />
                                            </td>
                                            <td>{{ amount_with_currency_symbol(optional($method->options)->cost) }}</td>
                                            <td>{{ amount_with_currency_symbol(optional($method->options)->minimum_order_amount) }}
                                            </td>
                                            <td>
                                                @can('shipping-method-delete')
                                                    @if (!$method->is_default)
                                                        <x-table.btn.swal.delete :route="route(
                                                            'admin.shipping.method.delete',
                                                            $method->id,
                                                        )" />
                                                    @endif
                                                @endcan
                                                @can('shipping-method-edit')
                                                    <a href="{{ route('admin.shipping.method.edit', $method->id) }}"
                                                        class="btn btn-primary btn-xs mb-2 me-1">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('shipping-method-make-default')
                                                    @if (!$method->is_default)
                                                        <form action="{{ route('admin.shipping.method.make.default') }}"
                                                            method="post" style="display: inline">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $method->id }}">
                                                            <button
                                                                class="btn btn-info btn-xs mb-2 me-1">{{ __('Make Default') }}</button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-success btn-xs px-4 mb-2 me-1"
                                                            disabled>{{ __('Default') }}</button>
                                                    @endif
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
    <x-bulk-action.js :route="route('admin.shipping.method.bulk.action')" />
@endsection
