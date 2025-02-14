@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Sub-Category') }}
@endsection
@section('style')
    <x-media.css />
    <x-datatable.css />
    <x-bulk-action.css />
@endsection

@php
    $statuses = \App\Status::all();
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-msg.error />
        <x-msg.flash />
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Products Sub-Categories') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('product-subcategory-delete')
                                <x-bulk-action.dropdown />
                            @endcan

                            @can('product-subcategory-create')
                                <a href="#1" data-bs-toggle="modal" data-bs-target="#subcategory_create_modal"
                                    class="cmn_btn btn-sm btn_bg_profile">New Sub Category</a>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Category Name') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_subcategory as $subcategory)
                                        <tr>
                                            <x-bulk-action.td :id="$subcategory->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subcategory->category?->name }}</td>
                                            <td>{{ $subcategory->name }}</td>
                                            <td>
                                                <x-status-span :status="$subcategory->status_name?->name" />
                                            </td>
                                            <td>
                                                <div class="attachment-preview">
                                                    <div class="img-wrap">
                                                        {!! \App\Http\Services\Media::render_image($subcategory->media, 'thumb', class: 'w-100') !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @can('product-subcategory-delete')
                                                    <x-table.btn.swal.delete :route="route(
                                                        'admin.products.subcategory.delete',
                                                        $subcategory->id,
                                                    )" />
                                                @endcan
                                                @can('product-subcategory-edit')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#subcategory_edit_modal"
                                                        class="btn btn-sm btn-primary btn-xs mb-2 me-1 subcategory_edit_btn"
                                                        data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}"
                                                        data-slug="{{ $subcategory->slug }}"
                                                        data-status="{{ $subcategory->status }}"
                                                        data-imageid="{{ $subcategory->image }}"
                                                        data-image="{{ \App\Http\Services\Media::render_image($subcategory->media, render_type: 'path') }}"
                                                        data-category-id="{{ $subcategory->category_id }}">
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

    @can('product-subcategory-edit')
        <div class="modal fade" id="subcategory_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Sub-Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.products.subcategory.update') }}" method="post">
                        <input type="hidden" name="id" id="subcategory_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_slug">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_slug" name="slug"
                                    placeholder="{{ __('Slug') }}">
                            </div>
                            <div class="form-group edit-category-wrapper">
                                <label for="category">{{ __('Category') }}</label>
                                <select class="form-control" id="edit_category" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($all_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-media-upload :title="__('Image')" :name="'image'" :dimentions="'200x200'" />
                            <div class="form-group edit-status-wrapper">
                                <label for="edit_status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
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
    @endcan

    @can('product-subcategory-create')
        <div class="modal fade" id="subcategory_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add Sub-Category') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.products.subcategory.new') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="create-name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="create-name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>
                            <div class="form-group">
                                <label for="create-slug">{{ __('Slug') }}</label>
                                <input type="text" class="form-control" id="create-slug" name="slug"
                                    placeholder="{{ __('Slug') }}">
                            </div>
                            <div class="form-group category-wrapper">
                                <label for="category_id">{{ __('Category') }}</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option>{{ __('Select Category') }}</option>
                                    @foreach ($all_category as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-media-upload :title="__('Image')" :name="'image'" :dimentions="'200x200'" />
                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{ __('Add New') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <x-media.markup />
@endsection
@section('script')
    <x-datatable.js />
    <x-media.js />
    <x-table.btn.swal.js />
    @can('product-subcategory-delete')
        <x-bulk-action.js :route="route('admin.products.subcategory.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function() {
            $(document).on('click', '.subcategory_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let status = el.data('status');
                let category_id = el.data('category-id');
                let modal = $('#subcategory_edit_modal');

                modal.find('#subcategory_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_name').val(name);
                modal.find('#edit_slug').val(slug);
                modal.find('#edit_category').val(category_id);
                modal.find(".edit-category-wrapper .list li").removeClass("selected");
                modal.find(".edit-category-wrapper .list li[data-value='" + category_id + "']").addClass(
                    "selected");
                modal.find(".edit-category-wrapper .list li[data-value='" + category_id + "']").trigger("click");
                modal.find(".edit-status-wrapper .list li[data-value='" + status + "']").trigger("click");
                modal.find(".modal-footer").trigger("click");

                let image = el.data('image');
                let imageid = el.data('imageid');

                if (imageid != '') {
                    modal.find('.media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        image + '" > </div></div></div>');
                    modal.find('.media-upload-btn-wrapper input').val(imageid);
                    modal.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });

            $('#create-name , #create-slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#create-slug').val(convertToSlug(title_text))
            });

            $('#edit_name , #edit_slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#edit_slug').val(convertToSlug(title_text))
            });
        });
    </script>
@endsection
