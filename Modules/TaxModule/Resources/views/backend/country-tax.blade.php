@extends('backend.admin-master')
@section('site-title')
    {{ __('Country Tax') }}
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
                        <h4 class="dashboard__card__title">{{ __('All Country Tax') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('tax-country-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('tax-country-new')
                                <div class="btn-wrapper">
                                    <a href="#1" data-bs-toggle="modal" data-bs-target="#country_tax_new_modal"
                                        class="cmn_btn btn_bg_profile">{{ __('Add new country tax') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('tax-country-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_country_tax as $tax)
                                        <tr>
                                            @can('tax-country-bulk-action')
                                                <x-bulk-action.td :id="$tax->id" />
                                            @endcan
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($tax->country)->name }}</td>
                                            <td>{{ $tax->tax_percentage }}</td>
                                            <td>
                                                @can('tax-country-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.tax.country.delete', $tax->id)" />
                                                @endcan
                                                @can('tax-country-update')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#country_tax_edit_modal"
                                                        class="btn btn-sm btn-primary btn-xs mb-2 me-1 country_tax_edit_btn"
                                                        data-id="{{ $tax->id }}" data-country_id="{{ $tax->country_id }}"
                                                        data-tax_percentage="{{ $tax->tax_percentage }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
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
    @can('tax-country-update')
        <div class="modal fade" id="country_tax_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Country Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.country.update') }}" method="post">
                        <input type="hidden" name="id" id="country_tax_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="edit_country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_tax_percentage">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="edit_tax_percentage" name="tax_percentage"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('tax-country-new')
        <div class="modal fade" id="country_tax_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Country Tax') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.tax.country.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tax_percentage">{{ __('Tax Percentage') }}</label>
                                <input type="number" class="form-control" id="tax_percentage" name="tax_percentage"
                                    placeholder="{{ __('Tax Percentage') }}">
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.tax.country.bulk.action')" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.country_tax_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let country_id = el.data('country_id');
                let tax_percentage = el.data('tax_percentage');
                let modal = $('#country_tax_edit_modal');

                // make select option
                $("#country_tax_edit_modal select option[value=" + country_id + "]").attr("selected",
                    "true");
                $("#country_tax_edit_modal .list li[data-value=" + country_id + "]").trigger("click");
                $("#country_tax_edit_modal .modal-footer").trigger("click");
                modal.find('#country_tax_id').val(id);
                modal.find('#edit_country_id').val(country_id);
                modal.find('#edit_tax_percentage').val(tax_percentage);
            });
        });
    </script>
@endsection
