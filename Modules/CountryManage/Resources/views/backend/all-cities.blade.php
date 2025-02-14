@extends('backend.admin-master')
@section('site-title')
    {{ __('State') }}
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
                        <h4 class="dashboard__card__title">{{ __('All States') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('state-delete')
                                <x-bulk-action.dropdown />
                            @endcan
                            <div class="btn-wrapper">
                                <button class="cmn_btn btn_bg_profile" data-bs-target="#state_create_modal"
                                    data-bs-toggle="modal">New State</button>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_states as $state)
                                        <tr>
                                            <x-bulk-action.td :id="$state->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $state->name }}</td>
                                            <td>{{ optional($state->country)->name }}</td>
                                            <td><x-status-span :status="$state->status" /></td>
                                            <td>
                                                @can('state-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.state.delete', $state->id)" />
                                                @endcan
                                                @can('state-edit')
                                                    <a href="#1" data-bs-toggle="modal" data-bs-target="#state_edit_modal"
                                                        class="btn btn-primary btn-sm btn-xs mb-2 me-1 state_edit_btn"
                                                        data-id="{{ $state->id }}" data-name="{{ $state->name }}"
                                                        data-country_id="{{ $state->country_id }}"
                                                        data-status="{{ $state->status }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                            {!! $all_states->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('state-edit')
        <div class="modal fade" id="state_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update state') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.state.update') }}" method="post">
                        <input type="hidden" name="id" id="state_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="edit_country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
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
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('state-create')
        <div class="modal fade" id="state_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update state') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.state.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="country_id">{{ __('Country') }}</label>
                                <select name="country_id" class="form-control" id="country_id">
                                    @foreach ($all_countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="publish">{{ __('Publish') }}</option>
                                    <option value="draft">{{ __('Draft') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.state.bulk.action')" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.state_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let country_id = el.data('country_id');
                let status = el.data('status');
                let modal = $('#state_edit_modal');

                modal.find('#state_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
                modal.find('#edit_country_id').val(country_id);
            });
        });
    </script>
@endsection
