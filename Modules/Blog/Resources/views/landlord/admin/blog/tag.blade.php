@extends('backend.admin-master')
@section('site-title')
    {{ __('All Blog Tags') }}
@endsection

@section('style')
    <x-media-upload.css />
    <x-datatable.css />
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{ __('All Blog Tags') }}</h4>
                        <x-bulk-action permissions="blog-tag-delete" />
                    </x-slot>
                    <x-slot name="right" class="d-flex ">
                        <div class="right-button ml-2">
                            <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal"
                                data-bs-target="#new_tag">{{ __('Add New Tag') }}</button>
                        </div>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg />
                <x-flash-msg />
                <x-datatable.table>
                    <x-slot name="th">
                        <th class="no-sort">
                            <div class="mark-all-checkbox">
                                <input type="checkbox" class="all-checkbox">
                            </div>
                        </th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach ($all_tag as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id" />
                                </td>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->title }}</td>
                                <td>{{ $data->slug }}</td>
                                <td>
                                    @can('blog-tag-edit')
                                        <a href="#1" data-bs-toggle="modal"
                                            data-bs-target="#tag_item_edit_modal"
                                            class="btn btn-primary btn-xs mb-2 me-1 tag_edit_btn" data-bs-placement="top"
                                            title="{{ __('Edit') }}" data-id="{{ $data->id }}"
                                            data-action="{{ route(route_prefix() . 'admin.blog.tag.update') }}"
                                            data-title="{{ $data->title }}" data-slug="{{ $data->slug }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endcan
                                    <x-delete-popover permissions="blog-tag-delete"
                                        url="{{ route(route_prefix() . 'admin.blog.tag.delete', $data->id) }}" />
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('blog-tag-create')
        <div class="modal fade" id="new_tag" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('New Blog Tag') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route(route_prefix() . 'admin.blog.tag.store') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <x-fields.input name="title" label="{{ __('Title') }}" />

                            <x-fields.input name="slug" label="{{ __('Slug') }}" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('blog-category-edit')
        <div class="modal fade" id="tag_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('Edit Blog Tag Item') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" class="category_edit_modal_form" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" class="category_id" value="">
                            <x-fields.input name="title" label="{{ __('Title') }}" class="edit_title" />

                            <x-fields.input name="slug" label="{{ __('Slug') }}" class="edit_slug" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-media-upload.markup />
@endsection

@section('script')
    <x-media-upload.js />
    <x-datatable.js />
    <script>
        $(document).ready(function($) {
            "use strict";

            <
            x - bulk - action - js: url = "route(route_prefix().'admin.blog.tag.bulk.action')" / >

                $(document).on('click', '.tag_edit_btn', function() {
                    let el = $(this);
                    let id = el.data('id');

                    let title = el.data('title');
                    let slug = el.data('slug');
                    let action = el.data('action');

                    let form = $('.category_edit_modal_form');
                    form.attr('action', action);
                    form.find('.category_id').val(id);
                    form.find('.edit_title').val(title);
                    form.find('.edit_slug').val(slug);
                });

        });
    </script>
@endsection
