@extends('backend.admin-master')
@section('site-title')
    {{ __('Media Images Settings') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
    <style>
        .media-image-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .media-image-header h2 {
            font-size: 26px;
            line-height: 30px;
        }

        .media-uploader-image-list.media-page {
            width: 100%;
            max-height: 100%;
        }

        .attachment-preview {
            position: relative;
            box-shadow: inset 0 0 15px rgb(0 0 0 / 10%), inset 0 0 0 1px rgb(0 0 0 / 5%);
            background: #eee;
            cursor: pointer;
            width: 100px;
            height: 100px;
        }

        .media-uploader-image-info {
            padding: 20px;
            display: inline-block;
            width: 100%;
        }

        .img-alt-wrap input {
            width: calc(100% - 60px);
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                @include('backend/partials/message')
                @include('backend/partials/error')
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Media Images') }}</h4>
                        <a href="#1" class="cmn_btn btn_bg_profile" data-bs-toggle="modal"
                            data-bs-target="#media_image_upload_modal">{{ __('Add New Image') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-4">
                <div class="dashboard__card">
                    <div class="dashboard__card__body">
                        <div class="row">
                            <div class="col-xxl-10 col-xl-9 col-lg-9">
                                <ul class="media-uploader-image-list media-page">
                                    @foreach ($all_media_images as $image)
                                        <li data-date="{{ $image->updated_at }}" data-imgid="{{ $image->id }}"
                                            data-imgsrc="{{ asset('assets/uploads/media-uploader/' . $image->path) }}"
                                            data-size="{{ $image->size }}" data-dimension="{{ $image->dimensions }}"
                                            data-title="{{ $image->title }}" data-alt="{{ $image->alt }}">
                                            <div class="attachment-preview">
                                                <div class="thumbnail">
                                                    <div class="centered">
                                                        {!! render_image_markup_by_attachment_id($image->id) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-xxl-2 col-xl-3 col-lg-3">
                                <div class="img-sticky-wrap">
                                    <div class="media-uploader-image-info" id="media-uploader-image-info">
                                        <div class="img-wrapper">
                                            <img src="" alt="">
                                        </div>
                                        <div class="img-info">
                                            <h5 class="img-title"></h5>
                                            <ul class="img-meta" style="display: none">
                                                <li class="date"></li>
                                                <li class="dimension"></li>
                                                <li class="size"></li>
                                                <li class="image_id" style="display:none;"></li>
                                                <li class="imgsrc"></li>
                                                <li class="imgalt">
                                                    <div class="img-alt-wrap">
                                                        <input type="text" name="img_alt_tag"
                                                            placeholder="{{ __('image alt tag') }}">
                                                        <button class="btn btn-success img_alt_submit_btn" style="margin-top: 20px;"><i class="ti-check"></i></button>
                                                    </div>
                                                </li>
                                            </ul>
                                            @can('media-upload-delete')
                                                <form method="post" action="{{ route('admin.upload.media.file.delete') }}"
                                                    class="delete_image_form" style="display: none">
                                                    @csrf
                                                    <input type="hidden" name="img_id" id="info_image_id_input">
                                                    <button type="submit" class=" btn btn-lg btn-danger btn-sm mb-2 me-1"><i
                                                            class="ti-trash"></i></button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="media_image_upload_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Upload Images') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">{{ __('Reload the page to see latest uploaded images') }}</div>
                    <div class="dropzone-form-wrapper">
                        <form action="{{ route('admin.upload.media.file') }}" method="post" class="dropzone"
                            enctype="multipart/form-data">
                            @csrf
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
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
                $(window).on('scroll', function(e) {
                    var scrolltop = $(window).scrollTop();
                    var mtop = scrolltop - 400;
                    if (scrolltop > 450) {
                        $('#media-uploader-image-info').css({
                            marginTop: mtop + 'px'
                        });
                    } else {
                        $('#media-uploader-image-info').css({
                            marginTop: '0px'
                        });
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
