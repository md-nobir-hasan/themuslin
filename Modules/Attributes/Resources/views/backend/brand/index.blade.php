@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Brand Manage') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-media.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <x-msg.error />
                    <x-msg.flash />
                </div>
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Brand Manages') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('brand-manage-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan

                            @can('brand-manage-new')
                                <a href="#1" data-bs-toggle="modal" data-bs-target="#brand_manage_create_modal"
                                    class="cmn_btn btn_bg_profile">{{ __('Create Brand Manage') }}</a>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can('brand-manage-bulk-action')
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('SL NO:') }}</th>
                                    <th>{{ __('Logo') }}</th>
                                    <th>{{ __('Banner') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($brands as $item)
                                        <tr>
                                            @can('brand-manage-bulk-action')
                                                <x-bulk-action.td :id="$item->id" />
                                            @endcan
                                            <td width="80px">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="attachment-preview">
                                                    <div class="img-wrap">
                                                        {!! render_image($item->logo, size: 'full') !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="attachment-preview">
                                                    <div class="img-wrap">
                                                        {!! render_image($item->banner, size: 'full') !!}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td class="w-40">{{ $item->description }}</td>
                                            <td>
                                                @can('brand-manage-delete')
                                                    <x-table.btn.swal.delete class="margin-bottom-0" :route="route('admin.brand.manage.delete', $item->id)" />
                                                @endcan
                                                @can('brand-manage-update')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#brand_manage_edit_modal"
                                                        class="btn btn-info btn-sm brand_manage_edit_btn"
                                                        data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-slug="{{ $item->slug }}" data-title="{{ $item->title }}"
                                                        data-description="{{ $item->description }}"
                                                        data-logo-id="{{ $item->image_id }}"
                                                        data-logo="{{ \App\Http\Services\Media::render_image($item->logo, render_type: 'path') }}"
                                                        data-banner-id="{{ $item->banner_id }}"
                                                        data-banner="{{ \App\Http\Services\Media::render_image($item->banner, render_type: 'path') }}">
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
    @can('brand-manage-update')
        <div class="modal fade" id="brand_manage_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Brand') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.brand.manage.update') }}" method="post">
                            @csrf
                            <input type="hidden" value="" id="edit-brand-id" name="id">
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit-name" name="name"
                                    placeholder="{{ __('Brand Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Slug') }}</label>
                                <input type="text" class="form-control" id="edit-slug" name="slug"
                                    placeholder="{{ __('Brand Slug') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Title') }}</label>
                                <input type="text" class="form-control" id="edit-title" name="title"
                                    placeholder="{{ __('Title') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Description') }}</label>
                                <input type="text" class="form-control" id="edit-description" name="description"
                                    placeholder="{{ __('Brand Description Optional') }}">
                            </div>

                            <x-media-upload :title="__('Logo')" :name="'image_id'" :dimentions="'200x200'" />
                            <x-media-upload :title="__('Banner')" :name="'banner_id'" :dimentions="'200x200'" />

                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Brand') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('brand-manage-new')
        <div class="modal fade" id="brand_manage_create_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Delivery Manage') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.brand.manage.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="create-name" name="name"
                                    placeholder="{{ __('Brand Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Slug') }}</label>
                                <input type="text" class="form-control" id="create-slug" name="slug"
                                    placeholder="{{ __('Brand Slug') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Title') }}</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="{{ __('Title') }}">
                            </div>

                            <div class="form-group">
                                <label for="name">{{ __('Description') }}</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    placeholder="{{ __('Brand Description Optional') }}">
                            </div>

                            <x-media-upload :title="__('Logo')" :name="'image_id'" :dimentions="'200x200'" />
                            <x-media-upload :title="__('Banner')" :name="'banner_id'" :dimentions="'200x200'" />

                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Add New') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <x-media.markup />
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-backend.icon-picker-js />
    <x-media.js />

    @can('brand-manage-bulk-action')
        <x-bulk-action-js :url="route('admin.brand.manage.bulk.action')" />
    @endcan

    <script>
        $(document).ready(function() {
            $(document).on('click', '.brand_manage_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let slug = el.data('slug');
                let title = el.data('title');
                let description = el.data('description');
                let modal = $('#brand_manage_edit_modal');

                modal.find('#edit-brand-id').val(id);
                modal.find('#edit-title').val(title);
                modal.find('#edit-description').val(description);
                modal.find('#edit-name').val(name);
                modal.find('#edit-slug').val(slug);

                let logo = el.data('logo');
                let banner = el.data('banner');
                let logo_id = el.data('logo-id');
                let banner_id = el.data('banner-id');

                if (logo_id != '') {
                    modal.find('#image_id .media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        logo + '" > </div></div></div>');
                    modal.find('#image_id .media-upload-btn-wrapper input').val(logo_id);
                    modal.find('#image_id .media-upload-btn-wrapper .media_upload_form_btn').text(
                        'Change Image');
                }

                if (banner_id != '') {
                    modal.find('#banner_id .media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        banner + '" > </div></div></div>');
                    modal.find('#banner_id .media-upload-btn-wrapper input').val(banner_id);
                    modal.find('#banner_id .media-upload-btn-wrapper .media_upload_form_btn').text(
                        'Change Image');
                }

                modal.show();
            });
        });

        $('#create-name , #create-slug').on('keyup', function() {
            let title_text = $(this).val();
            $('#create-slug').val(convertToSlug(title_text))
        });

        $('#edit-name , #edit-slug').on('keyup', function() {
            let title_text = $(this).val();
            $('#edit-slug').val(convertToSlug(title_text))
        });
    </script>
@endsection
