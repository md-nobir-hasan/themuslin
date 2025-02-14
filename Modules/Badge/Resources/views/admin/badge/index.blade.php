@extends('backend.admin-master')

@section('site-title')
    {{ __('Badges') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-media.css />

    <style>
        .badge_image {
            width: 50px;
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    <x-error-msg />
                    <x-flash-msg />
                </div>
            </div>
            <div class="col-lg-8">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Badges') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('badge-bulk-action')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('badge-trash')
                                <a class="cmn_btn btn_bg_danger" href="{{ route('admin.badge.trash') }}">{{ __('Trash') }}</a>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($badges as $badge)
                                        <tr>
                                            <x-bulk-action.td :id="$badge->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $badge->name }}</td>
                                            <td>
                                                {!! render_image_markup_by_attachment_id($badge->image, 'badge_image') !!}
                                            </td>
                                            <td>{{ $badge->status }}</td>
                                            <td>
                                                @can('badge-delete')
                                                    <x-delete-popover permissions="badge-delete"
                                                        url="{{ route('admin.badge.delete', $badge->id) }}" />
                                                @endcan
                                                @can('badge-update')
                                                    @php
                                                        $img = get_attachment_image_by_id($badge->image);
                                                        $img_url = !empty($img) ? $img['img_url'] : '';
                                                    @endphp
                                                    <a href="#1" data-bs-toggle="modal" data-bs-target="#badge_edit_modal"
                                                        class="btn btn-primary btn-sm btn-xs mb-2 me-1 badge_edit_btn"
                                                        data-id="{{ $badge->id }}" data-name="{{ $badge->name }}"
                                                        data-status="{{ $badge->status }}" data-image_id="{{ $badge->image }}"
                                                        data-image_url="{{ $img_url }}"
                                                        data-route="{{ route('admin.badge.update', $badge->id) }}">
                                                        <i class="mdi mdi-pencil"></i>
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
            @can('badge-new')
                <div class="col-lg-4">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Add New Badge') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.badge.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="{{ __('Name') }}">
                                </div>

                                <div class="form-group">
                                    <label for="status">{{ __('Status') }}</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="" selected disabled>Select a badge type</option>
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="in_active">{{ __('In Active') }}</option>
                                    </select>
                                </div>

                                <x-media.media-upload :name="'image'" :title="'Badge Image'" />

                                <div class="btn-wrapper mt-3">
                                    <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Add New') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('badge-update')
        <div class="modal fade" id="badge_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Unit') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="" method="post" id="badge_edit_modal_form">
                        @csrf
                        <input type="hidden" name="id" id="badge_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="edit_name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="edit_name" name="name"
                                    placeholder="{{ __('Name') }}">
                            </div>

                            <div class="form-group">
                                <label for="status">{{ __('Status') }}</label>
                                <select class="form-control" name="status" id="edit-status">
                                    <option value="" selected disabled>{{ __('Select a badge type') }}</option>
                                    <option value="active">{{ __('Active') }}</option>
                                    <option value="in_active">{{ __('In Active') }}</option>
                                </select>
                            </div>

                            <x-media.media-upload :name="'image'" :title="'Badge Image'" />
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

    <x-media.markup />
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />

    <x-media.js />

    @can('badge-bulk-action')
        <x-bulk-action.js :route="route('admin.badge.bulk.action.delete')" />
    @endcan

    <script>
        $(function() {
            $(document).on('click', '.badge_edit_btn', function() {
                var el = $(this);
                var id = el.data('id');
                var name = el.data('name');
                var image = el.data('image_url');
                var image_id = el.data('image_id');
                var status = el.data('status');
                var action = el.data('route');

                var form = $('#badge_edit_modal_form');
                form.attr('action', action);
                form.find('#badge_id').val(id);
                form.find('#edit_name').val(name);
                form.find('#edit-status option[value="' + status + '"]').attr('selected', true);
                if (image_id != '') {
                    form.find('.media-upload-btn-wrapper .img-wrap').html(
                        '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                        image + '" > </div></div></div>');
                    form.find('.media-upload-btn-wrapper input').val(image_id);
                    form.find('.media-upload-btn-wrapper .media_upload_form_btn').text('Change Image');
                }
            });
        });
    </script>
@endsection
