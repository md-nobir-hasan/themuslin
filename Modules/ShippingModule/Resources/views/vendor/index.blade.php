@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Shipping Method List') }}
@endsection

@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Shipping Methods List') }}</h4>
                        <a href="{{ route('vendor.shipping-method.create') }}" class="cmn_btn btn_bg_profile">Create Shipping
                            Method</a>
                    </div>
                    <div class="dashboard__card__body mt-4 dashboard-recent-order">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Zone') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_shipping_methods as $method)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($method)->title }}</td>
                                            <td>{{ optional($method->zone)->name }}</td>
                                            <td>
                                                <x-status-span :status="optional($method)->status->name" />
                                            </td>
                                            <td>{{ amount_with_currency_symbol(optional($method)->cost) }}</td>

                                            <td>
                                                @if (!$method->is_default)
                                                    <a href="{{ route('vendor.shipping-method.destroy', $method->id) }}"
                                                        class="btn btn-danger btn-xs mb-2 me-1">
                                                        <i class="las la-trash"></i> </a>
                                                @endif
                                                <a href="{{ route('vendor.shipping-method.edit', $method->id) }}"
                                                    class="btn btn-primary btn-xs mb-2 me-1">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>

                                                @if (!$method->is_default)
                                                    <form action="{{ route('vendor.shipping-method.make-default') }}"
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
@endsection
