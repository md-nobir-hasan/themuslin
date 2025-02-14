@extends('backend.admin-master')
@section('site-title')
    {{ __('Header Slider') }}
@endsection
@section('style')
    <x-media.css />
    @include('backend.partials.datatable.style-enqueue')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-7">
                @include('backend/partials/message')
                @include('backend/partials/error')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Header Slider') }}</h4>
                        <div class="bulk-delete-wrapper">
                            <x-bulk-action />
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_header_slider as $data)
                                        @php $img_url =''; @endphp
                                        <tr>
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id" />
                                            </td>
                                            <td>{{ $data->id }}</td>
                                            <td>
                                                {!! render_attachment_preview_for_admin($data->image) !!}
                                            </td>
                                            <td>{{ $data->title }}</td>
                                            <td>{{ $data->description }}</td>
                                            <td>
                                                <x-delete-popover :url="route('admin.header.delete', $data->id)" />
                                                <a href="#1" data-bs-toggle="modal"
                                                    data-bs-target="#header_slider_item_edit_modal"
                                                    class="btn btn-lg btn-primary btn-sm mb-2 me-1 header_slider_edit_btn"
                                                    data-id="{{ $data->id }}" data-title="{{ $data->title }}"
                                                    data-subtitle="{{ $data->subtitle }}"
                                                    data-imageid="{{ $data->image }}" {!! render_img_url_data_attr($data->image, 'image') !!}
                                                    data-lang="{{ $data->lang }}"
                                                    data-description="{{ $data->description }}"
                                                    data-btn_01_status="{{ $data->btn_01_status }}"
                                                    data-btn_01_text="{{ $data->btn_01_text }}"
                                                    data-btn_01_url="{{ $data->btn_01_url }}">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('New Header Slider') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.header') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="subtitle">{{ __('Subtitle') }}</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle"
                                    placeholder="{{ __('Subtitle') }}">
                            </div>
                            <div class="form-group">
                                <label for="title">{{ __('Title') }}</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="{{ __('Title') }}">
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea class="form-control max-height-150" id="description" name="description" placeholder="{{ __('Description') }}"
                                    cols="30" rows="10"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="btn_01_status">{{ __('Button Show/Hide') }}</label>
                                <label class="switch">
                                    <input type="checkbox" name="btn_01_status" id="btn_01_status">
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="btn_01_text">{{ __('Button Text') }}</label>
                                <input type="text" class="form-control" id="btn_01_text" name="btn_01_text"
                                    placeholder="{{ __('Button Text') }}">
                            </div>
                            <div class="form-group">
                                <label for="btn_01_url">{{ __('Button URL') }}</label>
                                <input type="text" class="form-control" id="btn_01_url" name="btn_01_url"
                                    placeholder="{{ __('Button URL') }}">
                            </div>
                            <div class="form-group">
                                <div class="media-upload-btn-wrapper">
                                    <div class="img-wrap"></div>
                                    <input type="hidden" name="image">
                                    <button type="button" class="btn btn-info media_upload_form_btn"
                                        data-btntitle="Select Slider Background" data-modaltitle="Upload Slider Background"
                                        data-bs-toggle="modal" data-bs-target="#media_upload_modal">
                                        {{ __('Upload Image') }}
                                    </button>
                                </div>
                                <small>{{ __('recommended image size is 1920x900 pixel') }}</small>
                            </div>
                            <button id="submit" type="submit"
                                class="cmn_btn btn_bg_profile">{{ __('Add  New Slider') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="header_slider_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content custom__form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Header Slider Item') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{ route('admin.header.update') }}" id="header_slider_edit_modal_form" method="post"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="header_slider_id" value="">
                        <div class="form-group">
                            <label for="edit_subtitle">{{ __('Subtitle') }}</label>
                            <input type="text" class="form-control" id="edit_subtitle" name="subtitle"
                                placeholder="{{ __('Subtitle') }}">
                        </div>
                        <div class="form-group">
                            <label for="edit_title">{{ __('Title') }}</label>
                            <input type="text" class="form-control" id="edit_title" name="title"
                                placeholder="{{ __('Title') }}">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">{{ __('Description') }}</label>
                            <textarea class="form-control max-height-150" id="edit_description" name="description"
                                placeholder="{{ __('Description') }}" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_btn_01_status">{{ __('Button Show/Hide') }}</label>
                            <label class="switch">
                                <input type="checkbox" name="btn_01_status" id="edit_btn_01_status">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label for="edit_btn_01_text">{{ __('Button Text') }}</label>
                            <input type="text" class="form-control" id="edit_btn_01_text" name="btn_01_text"
                                placeholder="{{ __('Button Text') }}">
                        </div>
                        <div class="form-group">
                            <label for="edit_btn_01_url">{{ __('Button URL') }}</label>
                            <input type="text" class="form-control" id="edit_btn_01_url" name="btn_01_url"
                                placeholder="{{ __('Button URL') }}">
                        </div>
                        <div class="form-group">
                            <div class="media-upload-btn-wrapper">
                                <div class="img-wrap"></div>
                                <input type="hidden" id="edit_image" name="image" value="">
                                <button type="button" class="btn btn-info media_upload_form_btn"
                                    data-btntitle="Select Slider Background" data-modaltitle="Upload Slider Background"
                                    data-imgid="{{ auth()->user()->image }}" data-bs-toggle="modal"
                                    data-bs-target="#media_upload_modal">
                                    {{ __('Upload Image') }}
                                </button>
                            </div>
                            <small>{{ __('recommended image size is 1920x900 pixel') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button id="update" type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>
    @include('backend.partials.media-upload.media-js')

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - bulk - action - js: url = "route('admin.header.bulk.action')" / >
                    <
                    x - btn.submit / >
                    <
                    x - btn.update / >

                    $(document).on('click', '.header_slider_edit_btn', function() {
                        var el = $(this);
                        var id = el.data('id');
                        var action = el.data('action');
                        var image = el.data('image');
                        var imageid = el.data('imageid');
                        var form = $('#header_slider_edit_modal_form');

                        form.attr('action', action);
                        form.find('#header_slider_id').val(id);
                        form.find('#edit_subtitle').val(el.data('subtitle'));
                        form.find('#edit_title').val(el.data('title'));
                        form.find('#edit_description').val(el.data('description'));
                        form.find('#edit_btn_01_text').val(el.data('btn_01_text'));
                        form.find('#edit_btn_01_url').val(el.data('btn_01_url'));

                        if (imageid != '') {
                            form.find('.media-upload-btn-wrapper .img-wrap').html(
                                '<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="' +
                                image + '" > </div></div></div>');
                            form.find('.media-upload-btn-wrapper input').val(imageid);
                            form.find('.media-upload-btn-wrapper .media_upload_form_btn').text(
                                'Change Image');
                        }

                        if (el.data('btn_01_status') == 'on') {
                            $('#edit_btn_01_status').prop('checked', true);
                        } else {
                            $('#edit_btn_01_status').prop('checked', false);
                        }
                    });
            });
        })(jQuery);
    </script>
    @include('backend.partials.datatable.script-enqueue')
@endsection
