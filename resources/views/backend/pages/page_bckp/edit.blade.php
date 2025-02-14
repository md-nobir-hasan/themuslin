@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap-tagsinput.css') }}">
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Edit Page') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.success />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Edit Page') }} </h4>
                        <div class="btn-wrapper">
                            <a href="{{ route('admin.page') }}" class="cmn_btn btn_bg_profile">{{ __('All Pages') }}</a>
                        </div>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.page.update', $page_post->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ $page_post->title }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="slug">{{ __('Slug') }}</label>
                                        <input type="text" class="form-control" name="slug"
                                            value="{{ $page_post->slug }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Content') }}</label>
                                        <input type="hidden" name="page_content" value="{{ $page_post->page_content }}">
                                        <div class="summernote" data-content="{{ $page_post->page_content }}"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="meta_title">{{ __('Meta Title') }}</label>
                                        <input type="text" name="meta_title" value="{{ $page_post->meta_title }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="og_meta_title">{{ __('OG Meta Title') }}</label>
                                        <input type="text" name="og_meta_title" value="{{ $page_post->og_meta_title }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="meta_description">{{ __('Meta Description') }}</label>
                                        <textarea name="meta_description" class="form-control" rows="5" id="meta_description">{{ $page_post->meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="og_meta_description">{{ __('OG Meta Description') }}</label>
                                        <textarea name="og_meta_description" class="form-control" rows="5" id="og_meta_description">{{ $page_post->og_meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="meta_tags">{{ __('Meta Tags') }}</label>
                                        <input type="text" name="meta_tags" class="form-control" data-role="tagsinput"
                                            id="meta_tags" value="{{ $page_post->meta_tags }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('OG Meta Image') }}</label>
                                        <div class="media-upload-btn-wrapper">
                                            <div class="img-wrap">
                                                @php
                                                    $image = get_attachment_image_by_id($page_post->og_meta_image, null, true);
                                                    $image_btn_label = 'Upload Image';
                                                @endphp
                                                @if (!empty($image))
                                                    <div class="attachment-preview">
                                                        <div class="thumbnail">
                                                            <div class="centered">
                                                                <img class="avatar user-thumb"
                                                                    src="{{ $image['img_url'] }}" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php  $image_btn_label = 'Change Image'; @endphp
                                                @endif
                                            </div>
                                            <input type="hidden" id="og_meta_image" name="og_meta_image"
                                                value="{{ $page_post->og_meta_image }}">
                                            <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{ __('Select Image') }}"
                                                data-modaltitle="{{ __('Upload Image') }}" data-bs-toggle="modal"
                                                data-bs-target="#media_upload_modal">
                                                {{ __($image_btn_label) }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-fields.status :name="'status'" :title="__('Status')" :value="$page_post->status" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-fields.page-show-type :name="'visibility'" :title="__('Visibility')" :value="$page_post->visibility" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" id="update"
                                        class="cmn_btn btn_bg_profile">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <x-media.js />
    <script src="{{ asset('assets/backend/js/summernote-bs4.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <x-btn.update />
                $('.summernote').summernote({
                    height: 400, //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        },
                        onPaste: function (e) {
                            let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/plain');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function(index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });

        })(jQuery);
    </script>
@endsection
