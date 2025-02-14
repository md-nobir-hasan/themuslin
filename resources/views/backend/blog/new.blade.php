@extends('backend.admin-master')
@section('style')
    <x-summernote.css />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap-tagsinput.css') }}">
    <x-media.css />
@endsection
@section('site-title')
    {{ __('New Blog Post') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Add New Blog Post') }} </h4>
                        @can('blog')
                            <div class="btn-wrapper">
                                <a href="{{ route('admin.blog') }}" class="cmn_btn btn_bg_profile">{{ __('All Blog Post') }}</a>
                            </div>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.blog.new') }}" method="post" enctype="multipart/form-data">@csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="title" id="title"
                                            placeholder="{{ __('Title') }}" value="{{ old('title') }}">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="slug">{{ __('Slug') }}</label>
                                        <input id="slug" type="text" class="form-control" name="slug"
                                            value="{{ old('slug') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('Content') }}</label>
                                        <textarea name="blog_content" id="summernote" class="">{{ old('blog_content') }}</textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="author">{{ __('Author') }}</label>
                                                                <input type="text" class="form-control" name="author">
                                                            </div>
                                                        </div> -->

                                <!-- <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="title">{{ __('Blog Tags') }}</label>
                                                                <input type="text" class="form-control" name="tags" data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="meta_tags">{{ __('Meta Tags') }}</label>
                                                                <input type="text" class="form-control" name="meta_tags" data-role="tagsinput">
                                                            </div>
                                                        </div> -->
                                <div class="col-sm-12">
                                    <!--  <div class="form-group">
                                                                <label for="title">{{ __('Excerpt') }}</label>
                                                                <textarea name="excerpt" id="excerpt" class="form-control max-height-150" cols="30" rows="10"></textarea>
                                                            </div> -->
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="meta_title">{{ __('Meta Title') }}</label>
                                        <input type="text" class="form-control"
                                            name="meta_title"value="{{ old('meta_title') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="og_meta_title">{{ __('Og Meta Title') }}</label>
                                        <input type="text" class="form-control"
                                            name="og_meta_title"value="{{ old('og_meta_title') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="meta_description">{{ __('Meta Description') }}</label>
                                        <textarea type="text" class="form-control" name="meta_description" rows="5" cols="10">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="og_meta_description">{{ __('Og Meta Description') }}</label>
                                        <textarea type="text" class="form-control" name="og_meta_description" rows="5" cols="10">{{ old('og_meta_description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <x-media-upload :title="__('Blog Image')" name="image" id="image" />
                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                   <!--  <div class="form-group">
                                        <x-media-upload :title="__('Og Meta Image')" name="og_meta_image" id="og_meta_image" />

                                        <small
                                            class="form-text text-muted">{{ __('allowed image format: jpg,jpeg,png') }}</small>
                                    </div> -->
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="draft">{{ __('Draft') }}</option>
                                            <option value="publish">{{ __('Publish') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <!-- <div id="category_list" class="form-group">
                                                                <label for="category">{{ __('Category') }}</label>
                                                                <select name="category" class="form-control">
                                                                    <option value="">{{ __('Select Category') }}</option>
                                                                    @foreach ($all_category as $category)
    <option value="{{ $category->id }}">{{ purify_html($category->name) }}
                                                                        </option>
    @endforeach
                                                                </select>
                                                            </div> -->
                                </div>

                                <div class="col-sm-6">
                                    <button type="submit" id="submit"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New Post') }}</button>
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
    <script src="{{ asset('assets/backend/js/bootstrap-tagsinput.js') }}"></script>
    <x-summernote.js />
    <x-media.js />


    {{-- summernote --}}
    <script>
        $('#summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear', 'strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            styleTags: [
                'p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
            ]
        });

        function convertToSlug(slug) {
            let finalSlug = slug.replace(/[^a-zA-Z0-9\s]/g, '');
            finalSlug = finalSlug.replace(/\s+/g, '-')
                .toLowerCase();
            return finalSlug;
        }

        $('#title').on('keyup', function() {
            var slug = convertToSlug($(this).val());
            $('#slug').val(slug);
        });
    </script>
@endsection
