@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap-tagsinput.css') }}">
    <x-summernote.css />
    <x-media.css />
@endsection
@section('site-title')
    {{ __('New Page') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Add New Page') }}</h4>
                        <a href="{{ route('admin.page') }}" class="cmn_btn btn_bg_profile">{{ __('All Pages') }}</a>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.page.new') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="title">{{ __('Title') }}</label>
                                                <input type="text" class="form-control" id="title" name="title"
                                                    placeholder="{{ __('Title') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="page_builder_status"><strong>{{ __('Page Builder Enable/Disable') }}</strong></label>
                                                <label class="switch">
                                                    <input type="checkbox" name="page_builder_status">
                                                    <span class="slider onff"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group classic-editor-wrapper">
                                                <label>{{ __('Content') }}</label>
                                                <textarea class="summernote" type="hidden" name="page_content"></textarea>
                                            </div>
                                        </div>
                                        <div class="row g-4 mt-1">
                                            <div class="col-lg-12">
                                                <div class="dashboard__card">
                                                    <div class="dashboard__card__header">
                                                        <h4 class="mb-3 lead dashboard__card__title">
                                                            {{ __('Navbar Variant') }}
                                                        </h4>
                                                    </div>
                                                    <div class="dashboard__card__body mt-4">
                                                        <div class="navbar_variants">
                                                            <div class="form-group">
                                                                <input type="hidden" class="form-control" id="navbar_variant" name="navbar_variant">
                                                            </div>

                                                            @for ($i = 1; $i <= 3; $i++)
                                                                <div class="img-select img-select-nav">
                                                                    <div class="img-wrap">
                                                                        <img src="{{ asset('assets/frontend/navbar-variant/0' . $i . '.jpg') }}"
                                                                             data-nav_id="{{ $i }}" alt="">
                                                                    </div>
                                                                </div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="btn-wrapper page-builder-btn-wrapper d-none">
                                                <a href="#1"
                                                    class="cmn_btn btn_bg_profile">{{ __('Open Page Builder') }}</a>
                                                <small
                                                    class="info-text">{{ __('Page builder option is available in page edit only') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><strong>{{ __('Breadcrumb Show/Hide') }}</strong></label>
                                                <label class="switch role">
                                                    <input type="checkbox" name="breadcrumb_status">
                                                    <span class="slider-yes-no"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><strong>{{ __('Full Page Width') }}</strong></label>
                                                <label class="switch role">
                                                    <input type="checkbox" name="page_container_option">
                                                    <span class="slider-yes-no"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><strong>{{ __('Navbar Category Dropdown Open') }}</strong></label>
                                                <label class="switch role">
                                                    <input type="checkbox" name="navbar_category_dropdown_open">
                                                    <span class="slider-yes-no"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="slug">{{ __('Slug') }}</label>
                                                <input type="text" class="form-control" id="slug" name="slug"
                                                    placeholder="{{ __('slug') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Status') }}</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="publish">{{ __('Publish') }}</option>
                                                    <option value="draft">{{ __('Draft') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>{{ __('Visibility') }}</label>
                                                <select name="visibility" class="form-control">
                                                    <option value="all">{{ __('All') }}</option>
                                                    <option value="user">{{ __('Only Logged In User') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="meta_tags">{{ __('Page Meta Tags') }}</label>
                                                <input type="text" name="meta_tags" class="form-control"
                                                    data-role="tagsinput" id="meta_tags">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="meta_description">{{ __('Page Meta Description') }}</label>
                                                <textarea name="meta_description" class="form-control" id="meta_description"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Add New Page') }}</button>
                                        </div>
                                    </div>
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
    <x-summernote.js />
    <x-media.js />
    <script src="{{ asset('assets/backend/js/bootstrap-tagsinput.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('change', 'input[name="page_builder_status"]', function() {
                if ($(this).is(':checked')) {
                    $('.classic-editor-wrapper').addClass('d-none');
                    $('.page-builder-btn-wrapper').removeClass('d-none');
                } else {
                    $('.classic-editor-wrapper').removeClass('d-none');
                    $('.page-builder-btn-wrapper').addClass('d-none');
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

            //For Navbar
            var imgSelect1 = $('.img-select-nav');
            var id = $('#navbar_variant').val();
            imgSelect1.removeClass('selected');
            $('img[data-nav_id="' + id + '"]').parent().parent().addClass('selected');
            $(document).on('click', '.img-select-nav img', function(e) {
                e.preventDefault();
                imgSelect1.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#navbar_variant').val($(this).data('nav_id'));
            });

            //For Footer
            var imgSelect2 = $('.img-select-footer');
            var id = $('#footer_variant').val();
            imgSelect2.removeClass('selected');
            $('img[data-foot_id="' + id + '"]').parent().parent().addClass('selected');
            $(document).on('click', '.img-select-footer img', function(e) {
                e.preventDefault();
                imgSelect2.removeClass('selected');
                $(this).parent().parent().addClass('selected').siblings();
                $('#footer_variant').val($(this).data('foot_id'));
            });
        });
    </script>
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>
    @include('backend.partials.media-upload.media-js')
@endsection
