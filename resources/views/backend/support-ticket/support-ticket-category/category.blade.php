@extends('backend.admin-master')
@section('site-title')
    {{ __('Support Department') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-7">
                <x-msg.flash />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title mb-4">{{ __('All Departments') }}</h4>
                        @can('support-ticket-department-delete')
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
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_category as $data)
                                        <tr>
                                            <x-bulk-action.td :id="$data->id" />
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                @if ('publish' == $data->status)
                                                    <span
                                                        class="btn btn-success btn-sm">{{ ucfirst(__($data->status)) }}</span>
                                                @else
                                                    <span
                                                        class="btn btn-warning btn-sm">{{ ucfirst(__($data->status)) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @can('support-ticket-department-delete')
                                                    <a tabindex="0" class="btn btn-lg btn-danger btn-sm mb-2 me-1"
                                                        role="button" data-bs-toggle="popover" data-trigger="focus"
                                                        data-html="true" title=""
                                                        data-content="
                                                    <h6>{{ __('Are you sure to delete this category item?') }}</h6>
                                                    <form method='post' action='{{ route('admin.support.ticket.department.delete', $data->id) }}'>
                                                    <input type='hidden' name='_token' value='{{ csrf_token() }}'>
                                                    <br>
                                                        <input type='submit' class='btn btn-danger btn-sm' value='{{ __('Yes, Please') }}'>
                                                        </form>
                                                ">
                                                        <i class="ti-trash"></i>
                                                    </a>
                                                @endcan
                                                @can('support-ticket-department-edit')
                                                    <a href="#1" data-bs-toggle="modal" data-bs-target="#category_edit_modal"
                                                        class="btn btn-lg btn-primary btn-sm mb-2 me-1 category_edit_btn"
                                                        data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                                                        data-status="{{ $data->status }}">
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
            @can('support-ticket-department-create')
                <div class="col-lg-5">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Add New Department') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.support.ticket.department') }}" method="post"
                                enctype="multipart/form-data">
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
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Add New') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="modal fade" id="category_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom__form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update Department') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{ route('admin.support.ticket.department.update') }}" method="post">
                    <input type="hidden" name="id" id="category_id">
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
                                <option value="draft">{{ __('Draft') }}</option>
                                <option value="publish">{{ __('Publish') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Change') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <x-datatable.js />
    <x-bulk-action.js :route="route('admin.support.ticket.department.bulk.action')" />

    <script>
        $(document).ready(function() {
            $('.table-wrap > table').DataTable({
                "order": [
                    [1, "desc"]
                ],
                'columnDefs': [{
                    'targets': 'no-sort',
                    'orderable': false
                }]
            });

            $('.all-checkbox').on('change', function(e) {
                e.preventDefault();
                var value = $('.all-checkbox').is(':checked');
                var allChek = $(this).parent().parent().parent().parent().parent().find('.bulk-checkbox');
                //have write code here fr
                if (value == true) {
                    allChek.prop('checked', true);
                } else {
                    allChek.prop('checked', false);
                }
            });

            $(document).on('click', '.category_edit_btn', function() {
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var status = el.data('status');
                var modal = $('#category_edit_modal');
                modal.find('#category_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
            });
        });
    </script>
@endsection
