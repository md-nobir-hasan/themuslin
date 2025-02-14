@extends('tenant.frontend.frontend-page-master')
@php
    $post_img = null;
    $user_lang = get_user_lang();

@endphp

@section('page-title')
    {{ purify_html($blog_post->title) }}
@endsection

@section('site-title')
    {{ purify_html($blog_post->title) }}
@endsection

@section('meta-data')
    {!! render_page_meta_data($blog_post) !!}
@endsection


@section('content')
    <section class="shop-area padding-top-100 padding-bottom-50">
        <div class="container container-one">
            <div class="shop-contents-wrapper">

                <!-- Blog Sidebar area starts -->
                @include('blog::tenant.frontend.partial.blog.blog-sidebar')
                <!-- Blog Sidebar area ends -->

                <div class="shop-grid-contents">
                    <div class="blog-details-wrapper">
                        <div class="single-blog-details">
                            <div class="single-blog-details-content pt-0">
                                <h2 class="single-blog-details-content-title fw-500"> {{ purify_html($blog_post->title) }}
                                </h2>
                                <div class="single-blog-details-content-tags mt-3">
                                    <span class="single-blog-details-content-tags-item">
                                        <a href="#1"> {{ $blog_post->category?->title }} </a>
                                    </span>
                                    <span class="single-blog-details-content-tags-item">
                                        {{ $blog_post->created_at?->format('d M Y') }} </span>
                                </div>
                                <div class="single-blog-details-thumb mt-4">
                                    {!! render_image_markup_by_attachment_id($blog_post->image, 'radius-5', 'full', false) !!}
                                </div>
                                <p class="single-blog-details-content-para mt-4"> {!! $blog_post->blog_content !!} </p>
                            </div>
                        </div>
                        <!-- Details Tag area starts -->
                        <div class="details-tag-area color-two padding-top-25 padding-bottom-50">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mt-4">
                                    <div class="blog-details-share-content">
                                        <h4 class="blog-details-share-content-title"> {{ __('Share:') }} </h4>
                                        <ul class="blog-details-share-social">
                                            {!! single_post_share(route('tenant.frontend.blog.single', $blog_post->title), $blog_post->title, $post_img) !!}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <div class="blog-details-share-content right-align">
                                        <h4 class="blog-details-share-content-title"> {{ __('Tags:') }} </h4>
                                        <ul class="blog-details-tag">
                                            @php
                                                $all_tags = explode(',', $blog_post->tags);
                                            @endphp
                                            @foreach ($all_tags as $tag)
                                                @php
                                                    $slug = Str::slug($tag);
                                                @endphp
                                                @if (!empty($slug))
                                                    <li class="blog-details-tag-list">
                                                        <a class="blog-details-tag-list-item text-capitalize"
                                                            href="{{ route(route_prefix() . 'frontend.blog.tags.page', ['any' => $slug]) }}">{{ $tag }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Details Tag area end -->

                        <!-- Comment area Starts -->
                        @include('blog::tenant.frontend.partial.blog.blog-all-comments')
                        <!-- Comment area ends -->

                        @if (Auth::guard('web')->check())
                            <!-- Post Comment area Starts -->
                            @include('blog::tenant.frontend.partial.blog.blog-comment-form')
                            <!-- Post Comment area ends -->
                        @else
                            <div class="details-comment-content">
                                <div class="sign-in register">
                                    <h4 class="title">{{ __('Sign In') }}</h4>
                                    <div class="form-wrapper">
                                        <x-error-msg />
                                        <x-flash-msg />
                                        <form action="" method="post" enctype="multipart/form-data"
                                            class="account-form" id="login_form_order_page">
                                            <div class="error-wrap"></div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">{{ __(' Username') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" name="username" class="form-control"
                                                    id="exampleInputEmail1" placeholder="Type your username">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">{{ __('Password') }}<span
                                                        class="required">*</span></label>
                                                <input type="password" name="password" class="form-control"
                                                    id="exampleInputPassword1" placeholder="Password">
                                            </div>

                                            <div class="form-group form-check">
                                                <div class="box-wrap">
                                                    <div class="left">
                                                        <input type="checkbox" name="remember" class="form-check-input"
                                                            id="exampleCheck1">
                                                        <label class="form-check-label"
                                                            for="exampleCheck1">{{ __('Remember me') }}</label>
                                                    </div>
                                                    <div class="right">
                                                        <a
                                                            href="{{ route('tenant.user.forget.password') }}">{{ __('Forgot Password?') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="btn-wrapper">
                                                <button type="submit" id="login_btn"
                                                    class="btn-default rounded-btn">{{ __('Sign In') }}</button>
                                            </div>

                                        </form>
                                        <p class="info">{{ __("Don'/t have an account") }} <a
                                                href="{{ route('tenant.user.register') }}"
                                                class="active">{{ __('Sign up') }}</a></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop area end -->
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {


                //Blog Comment Insert
                $(document).on('click', '#submitComment', function(e) {
                    e.preventDefault();

                    var erContainer = $(".error-message");
                    var el = $(this);
                    var form = $('#blog-comment-form');
                    var user_id = $('#user_id').val();
                    var blog_id = $('#blog_id').val();
                    var commented_by = $('#commented_by').val();
                    var comment_content = $('#comment_content').val();
                    let comment_id = $('#blog-comment-form input[name=comment_id]').val();

                    el.text('{{ __('Submitting') }}...');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            user_id: user_id,
                            blog_id: blog_id,
                            commented_by: commented_by,
                            comment_id: comment_id,
                            comment_content: comment_content,
                        },
                        success: function(data) {
                            $('#comment_content').val('');
                            erContainer.html('<div class="alert alert- ' + data.msg +
                                '"></div>');
                            load_comment_data('{{ $blog_post->id }}');
                            $('#blog-comment-form input[name=comment_id]').val('');
                            location.reload();
                        },
                        error: function(data) {
                            var errors = data.responseJSON;

                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function(index, value) {
                                erContainer.find('.alert.alert-danger').append(
                                    '<p>' + value + '</p>');
                            });
                            el.text('{{ __('Comment') }}');
                        },

                    });
                });

                //Blog Replay
                $(document).on('click', '.btn-replay', function(e) {
                    e.preventDefault();
                    $(this).css({
                        'backgroundColor': 'transparent',
                        'color': 'var(--main-color-one)'
                    });
                    let comment_id = $(this).data('comment_id');
                    let parent_name = $(this).parent().parent().find('.title').data('parent_name');

                    $('#blog-comment-form input[name=comment_id]').val(comment_id);

                    $('#comment_content').attr('placeholder', 'Replying to ' + parent_name + '..');

                    $('html').animate({
                        scrollTop: $("#comment_content").offset().top - 500
                    }, 100, 'linear');
                });

                function load_comment_data(id) {
                    var commentData = $('#comment_data');
                    var items = commentData.attr('data-items');
                    $.ajax({
                        url: "{{ route(route_prefix() . 'frontend.load.blog.comment.data') }}",
                        method: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                            items: items
                        },
                        success: function(data) {

                            commentData.attr('data-items', parseInt(items) + 5);

                            $('#comment_data').append(data.markup);
                            $('#load_more_comment_button').text('{{ __('Load More') }}');


                            if (data.blogComments.length === 0) {
                                $('#load_more_comment_button').text(
                                    '{{ __('No More Comment Found') }}');

                                setTimeout(function() {
                                    $('#load_more_comment_button').text(
                                        '{{ __('Load More') }}');
                                }, 2000)
                            }
                        }
                    })
                }

                $(document).on('click', '#load_more_comment_button', function() {
                    $(this).text('{{ __('Loading...') }}');
                    load_comment_data('{{ $blog_post->id }}');
                });

            });
        })(jQuery);
    </script>

    <x-custom-js.ajax-login />
@endsection
