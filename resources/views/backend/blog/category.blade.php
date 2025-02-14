@extends('backend.admin-master')
@section('site-title')
    {{ __('Category Page') }}
@endsection
@section('style')
    @include('backend.partials.datatable.style-enqueue')
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.success />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Categories') }}</h4>
                        <div class="dashboard__card__header__right">
                            <div class="bulk-delete-wrapper">
                                @can('blog-category-delete')
                                    <x-bulk-action />
                                @endcan
                            </div>
                            @can('blog-category-create')
                                <div class="btn-wrapper">
                                    <a data-bs-toggle="modal" data-bs-target="#new_category_modal"
                                        class="cmn_btn btn_bg_profile pull-right">{{ __('New Category') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_category as $data)
                                        <tr>
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id" />
                                            </td>

                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                <x-status-span :status="$data->status" />
                                            </td>
                                            <td>
                                                @can('blog-category-delete')
                                                    <x-delete-popover :url="route('admin.blog.category.delete', $data->id)" />
                                                @endcan
                                                @can('blog-category-edit')
                                                    <a href="#1" data-bs-toggle="modal" data-bs-target="#category_edit_modal"
                                                        class="btn btn-primary btn-xs mb-2 me-1 category_edit_btn"
                                                        data-id="{{ $data->id }}" data-name="{{ $data->name }}"
                                                        data-lang="{{ $data->lang }}" data-status="{{ $data->status }}">
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
    @can('blog-category-create')
        <div class="modal fade" id="new_category_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.blog.category') }}" method="post">
                        <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button id="submit" type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('blog-category-edit')
        <div class="modal fade" id="category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.blog.category.update') }}" method="post">
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
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button id="update" type="submit" class="btn btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - bulk - action - js: url = "route('admin.blog.category.bulk.action')" / >
                    <
                    x - btn.submit / >
                    <
                    x - btn.update / >
                    $(document).on('click', '.category_edit_btn', function() {
                        var el = $(this);
                        var id = el.data('id');
                        var name = el.data('name');
                        var status = el.data('status');
                        var modal = $('#category_edit_modal');
                        modal.find('#category_id').val(id);
                        modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                        modal.find('#edit_name').val(name);
                        modal.find('#edit_language option[value="' + el.data('lang') + '"]').attr(
                            'selected', true);
                    });
            });
        })(jQuery);
    </script>
    @include('backend.partials.datatable.script-enqueue')
@endsection
