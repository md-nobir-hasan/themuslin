@extends('backend.admin-master')
@section('site-title')
    {{ __('Country') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-msg.error />
        <x-msg.flash />
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Countries') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('country-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('country-new')
                                <div class="btn-wrapper">
                                    <button class="cmn_btn btn_bg_profile" data-bs-toggle="modal"
                                        data-bs-target="#country_new_modal">{{ __('Add Country') }}</button>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('country-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_countries as $country)
                                        <tr>
                                            @can('country-bulk-action')
                                                <x-bulk-action.td :id="$country->id" />
                                            @endcan
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $country->name }}</td>
                                            <td><x-status-span :status="$country->status" /></td>
                                            <td>
                                                @can('country-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.country.delete', $country->id)" />
                                                @endcan
                                                @can('country-update')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#country_edit_modal"
                                                        class="btn btn-primary btn-sm btn-xs mb-2 me-1 country_edit_btn"
                                                        data-id="{{ $country->id }}" data-name="{{ $country->name }}"
                                                        data-status="{{ $country->status }}">
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
            {{--           --}}
        </div>
    </div>
    @can('country-update')
        <div class="modal fade" id="country_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update country') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.country.update') }}" method="post">
                        <input type="hidden" name="id" id="country_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="publish">{{ __('Publish') }}</option>
                                    <option value="draft">{{ __('Draft') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan


    @can('country-new')
        <div class="modal fade" id="country_new_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add new country') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="model-body p-4">
                        <form action="{{ route('admin.country.new') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="publish">{{ __('Publish') }}</option>
                                    <option value="draft">{{ __('Draft') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Add New') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    @can('country-bulk-action')
        <x-bulk-action.js :route="route('admin.country.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function() {
            $(document).on('click', '.country_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let status = el.data('status');
                let modal = $('#country_edit_modal');

                modal.find('#country_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
            });
        });
    </script>
@endsection
