@extends(route_prefix().'admin.admin-master')
@section('site-title')   {{__('Edit Blog Post')}} @endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/landlord/admin/css/bootstrap-tagsinput.css')}}">
    <x-summernote.css/>
    <x-media-upload.css/>
    <style>
        .nav-pills .nav-link {
            margin: 8px 0px !important;
        }
        .col-lg-4.right-side-card {
            background: aliceblue;
        }
        #show-autocomplete{
            margin-top: 10px;
            padding: 10px;
            background: #0d6efd91;
            border-radius: 5px;
            box-shadow: 0 5px 10px 5px #198ae32b;
        }
        ul.autocomplete-warp{
            margin: 0;
            list-style-type: none;
            padding-left: 0;
        }
        li.tag_option{
            background-color: #fff;
            margin-bottom: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 15px;
            border-radius: 2px;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

<?php 

exit();

?>
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">  {{__('Edit Blog Post')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <p></p>
                        <x-link-with-popover url="{{route(route_prefix().'admin.blog')}}" extraclass="ml-3">
                            {{__('All Blog Post')}}
                        </x-link-with-popover>

                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.blog.update',$blog_post->id)}}">
                    @csrf
                <div class="row">

                <div class="col-md-8">
                    <x-fields.input type="text" name="title" label="{{__('Title')}}" class="title" value="{!! purify_html($blog_post->title ) !!}" id="title"/>

                    <div class="form-group permalink_label">
                        <label class="text-dark">{{__('Permalink * : ')}}
                            <span id="slug_show" class="display-inline"></span>
                            <span id="slug_edit" class="display-inline">
                                         <button class="btn btn-info btn-sm slug_edit_button"> <i class="las la-edit"></i> </button>
                                          <input type="text" name="slug" value="{{$blog_post->slug}}" class="form-control blog_slug mt-2" style="display: none">
                                          <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none">{{__('Update')}}</button>
                                    </span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label>{{__('Blog Content')}}</label>
                        <input type="hidden" name="blog_content" value="{{$blog_post->blog_content}}">
                        <div class="summernote" data-content="{{$blog_post->blog_content}}"></div>
                    </div>

                    <x-fields.textarea name="excerpt" label="{{__('Excerpt')}}" value="{{$blog_post->excerpt}}"/>
                    @include('components.landlord-others.blog-edit-meta-markup')

                 </div>
                    <div class="col-lg-4 right-side-card">
                        <div class="row ">
                            <div class="col-lg-12">
                                <div class="card my-3">
                                    <div class="card-body">
                                        @php
                                            $check = $blog_post->video_urll ? 'checked' : ''
                                        @endphp
                                        <x-landlord-others.blog-post-type/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="video_section" style="display: none">
                                    <div class="card my-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-body">
                                                    <x-fields.input type="text" name="video_url" label="{{__('Video Url')}}" value="{{$blog_post->video_url}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <x-fields.select name="category_id" class="form-control" title="{{__('Category')}}">
                                                    @foreach($all_category as $cat)
                                                        <option value="{{$cat->id}}" @if($cat->id == $blog_post->category_id) selected @endif>{{$cat->title}}</option>
                                                    @endforeach
                                                </x-fields.select>

                                                <div class="form-group " id="blog_tag_list">
                                                    <label for="title">{{__('Product Tag')}}</label>
                                                    <input type="text" class="form-control tags_filed"
                                                           name="tags" id="datetimepicker1" value="{{$blog_post->tags}}">
                                                    <div id="show-autocomplete" style="display: none;">
                                                        <ul class="autocomplete-warp"></ul>
                                                    </div>
                                                </div>

                                                <x-fields.switcher type="checkbox" name="featured" label="{{__('Featured')}}" value="{{$blog_post->featured}}"/>

                                                <x-fields.select name="visibility" class="form-control" id="visibility" title="{{__('Visibility')}}">
                                                    <option value="public" @if( $blog_post->visibility == 'public') selected @endif>{{__('Public')}}</option>
                                                    <option value="logged_user"@if( $blog_post->visibility == 'logged_user') selected @endif>{{__('Logged User')}}</option>
                                                </x-fields.select>


                                                <x-fields.select name="status" class="form-control" id="status" title="{{__('Status')}}">
                                                    <option value="{{\App\Enums\StatusEnums::DRAFT}}"@if( $blog_post->status == \App\Enums\StatusEnums::DRAFT) selected @endif>{{__("Draft")}}</option>
                                                    <option value="{{\App\Enums\StatusEnums::PUBLISH}}"@if( $blog_post->status ==\App\Enums\StatusEnums::PUBLISH) selected @endif>{{__("Publish")}}</option>
                                                </x-fields.select>

                                                  <x-landlord-others.edit-media-upload-image :label="'Blog Image'" :name="'image'" :value="$blog_post->image"/>
                                                  <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'image_gallery'" :value="$blog_post->image_gallery"/>

                                                <div class="submit_btn mt-5">
                                                    <button type="submit" class="btn btn-gradient-primary pull-right">{{__('Update Post ')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>

                     </div>
                </form>
            </div>
        </div>
    </div>
    <x-media-upload.markup/>
@endsection

@section('script')
    <x-summernote.js/>
    <x-media-upload.js/>
    <script src="{{asset('assets/landlord/admin/js/bootstrap-tagsinput.js')}}"></script>

    <script>
        //Date Picker
        flatpickr('#tag_data', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today"
        });

        var blogTagInput = $('#blog_tag_list .tags_filed');
        var oldTag = '';
        blogTagInput.tagsinput();
        //For Tags
        $(document).on('keyup', '#blog_tag_list .bootstrap-tagsinput input[type="text"]', function (e) {
            e.preventDefault();
            var el = $(this);
            var inputValue = $(this).val();
            $.ajax({
                type: 'get',
                url: "{{ route('admin.blog.get.tags.by.ajax') }}",
                async: false,
                data: {
                    query: inputValue
                },

                success: function (data) {
                    oldTag = inputValue;
                    let html = '';
                    var showAutocomplete = '';
                    $('#show-autocomplete').html('<ul class="autocomplete-warp"></ul>');
                    if (el.val() != '' && data.markup != '') {
                        data.result.map(function (tag, key) {
                            html += '<li class="tag_option" data-id="' + key + '"  data-val="' + tag + '">' + tag + '</li>'
                        })

                        $('#show-autocomplete ul').html(html);
                        $('#show-autocomplete').show();
                    } else {
                        $('#show-autocomplete').hide();
                        oldTag = '';
                    }

                },
                error: function (res) {

                }
            });
        });

        $(document).on('click', '.tag_option', function (e) {
            e.preventDefault();

            let id = $(this).data('id');
            let tag = $(this).data('val');
            blogTagInput.tagsinput('add', tag);
            $(this).parent().remove();
            blogTagInput.tagsinput('remove', oldTag);
        });
    </script>

    <script>
            $(document).ready(function () {
                //Status Code
                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Permalink Code
                var sl =  $('.blog_slug').val();
                var url = `{{url('/blog/')}}/` + sl;
                var data = $('#slug_show').text(url).css('color', 'blue');

                var form = $('#blog_new_form');

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.blog_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.blog_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/blog/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });


                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });
                $(document).on('change', '.post_type', function () {
                    var val = $(this).val();
                    if (val === 'option2') {
                        $('.video_section').show();
                    } else {
                        $('.video_section').hide();
                    }
                })

            });

            if('{{$check}}'){
                $('.video_section').show();
            }


            $('.summernote').summernote({
                height: 400,   //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function (contents, $editable) {
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
                $('.summernote').each(function (index, value) {
                    $(this).summernote('code', $(this).data('content'));
                });
            }



    </script>
@endsection
