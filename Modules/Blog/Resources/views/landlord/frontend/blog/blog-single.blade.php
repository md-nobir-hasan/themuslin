@extends('landlord.frontend.frontend-page-master')
@php
    $post_img = null;
@endphp

@section('page-title')
    {{$blog_post->title}}
@endsection

@section('site-title')
    {{$blog_post->title}}
@endsection

@section('content')
    <section class="blog-details-area section-bg-1 padding-top-95">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-details-wrapper">
                        <div class="single-blog-details">
                            <div class="single-blog-details-thumb">
                                {!! render_image_markup_by_attachment_id($blog_post->image, '', 'full', false) !!}
                            </div>
                            <div class="single-blog-details-content">
                                <h2 class="single-blog-details-content-title"> {{$blog_post->title}} </h2>
                                <div class="single-blog-details-content-tags mt-3">
                                    <span class="single-blog-details-content-tags-item">
                                        <a href="{{route(route_prefix().'frontend.blog.category',['id' => optional($blog_post->category)->id,'any'=> Str::slug(optional($blog_post->category)->title)])}}"> {{optional($blog_post->category)->title}} </a>
                                    </span>
                                    <span
                                        class="single-blog-details-content-tags-item"> {{ $blog_post->created_at->diffForHumans()}} </span>
                                </div>
                                <p class="single-blog-details-content-para mt-4"> {!! $blog_post->blog_content !!} </p>
                            </div>
                        </div>

                        <!-- Details Tag area starts -->
                        <div class="details-tag-area padding-top-35 padding-bottom-60">
                            <div class="row align-items-center">
                                <div class="col-lg-6 mt-4">
                                    <div class="blog-details-share-content">
                                        <h4 class="blog-details-share-content-title"> {{__('Share:')}} </h4>
                                        <ul class="blog-details-share-social">
                                            {!! single_post_share(route(route_prefix().'frontend.blog.single', $blog_post->slug),$blog_post->title,$post_img) !!}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-4">
                                    <div class="blog-details-share-content right-align">
                                        <h4 class="blog-details-share-content-title"> {{__('Tags:')}} </h4>
                                        <ul class="blog-details-tag">
                                            @php
                                                $all_tags = explode(',',$blog_post->tags);
                                            @endphp
                                            @foreach($all_tags as $tag)
                                                @php
                                                    $slug = Str::slug($tag);
                                                @endphp
                                                @if (!empty($slug))
                                                    <li class="blog-details-tag-list">
                                                        <a class="blog-details-tag-list-item"
                                                           href="{{route(route_prefix().'frontend.blog.tags.page',['any' => $slug])}}"> {{$tag}} </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Details Tag area end -->

                        <!-- Comment Show Area Start-->
                        @include('blog::landlord.frontend.partial.blog.comment-show-data')
                        <!-- Comment Show Area End-->

                        <!-- Post Comment area Starts -->
                        <div class="comment-area padding-top-60 padding-bottom-60">
                            <h3 class="details-section-title"> {{__('Post Your Comment')}} </h3>
                            <div class="error-message"></div>
                            <div class="row">
                                <div class="col-lg-12 padding-top-20">
                                    @php
                                        $user = Auth::guard('web')->user();
                                    @endphp

                                    @if($user != null)
                                        @include('blog::landlord.frontend.partial.blog.comment-area')
                                    @else
                                        @if(Auth::guard('admin')->user() == null)
                                            <div class="details-comment-content">
                                            <div class="sign-in register">
                                                <h4 class="title">{{__('Sign In')}}</h4>
                                                <div class="form-wrapper">
                                                    <x-error-msg/>
                                                    <x-flash-msg/>
                                                    <form action="" method="post" enctype="multipart/form-data" class="account-form" id="login_form_order_page">
                                                        <div class="error-wrap"></div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">{{__(' Username')}}<span class="required">*</span></label>
                                                            <input type="text" name="username" class="form-control" id="exampleInputEmail1" placeholder="Type your username">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">{{__('Password')}}<span class="required">*</span></label>
                                                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                                        </div>

                                                        <div class="form-group form-check">
                                                            <div class="box-wrap">
                                                                <div class="left">
                                                                    <input type="checkbox" name="remember" class="form-check-input" id="exampleCheck1">
                                                                    <label class="form-check-label" for="exampleCheck1">{{__('Remember me')}}</label>
                                                                </div>
                                                                <div class="right">
                                                                    <a href="{{route('tenant.user.forget.password')}}">{{__('Forgot Password?')}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="btn-wrapper">
                                                            <button type="submit" id="login_btn" class="btn-default rounded-btn">{{__('Sign In')}}</button>
                                                        </div>

                                                    </form>
                                                    <p class="info">{{__("Don'/t have an account")}} <a href="{{route('tenant.user.register')}}" class="active">{{__('Sign up')}}</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Post Comment area ends -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="getupdate-area section-bg-1 padding-top-50 padding-bottom-50">
        <div class="container">
            <div class="getupdate-wrapper getupdate-bg radius-20">
                <div class="getupdate-shape">
                    <img src="{{asset('assets/img/getupdate-line.png')}}" alt="">
                </div>
                <div class="getupdate-content center-text">
                    <h2 class="getupdate-content-title"> {{__('Get Updates as soon as they happen.')}}</h2>
                    <p class="getupdate-content-para mt-4"> {{__('Signup now for our newsletter and app launch.')}} </p>
                    <form action="" class="getupdate-content-form mt-4" id="landlord-newsletter-form">
                        <div class="getupdate-content-form-single radius-5">
                            <input type="email" class="getupdate-content-form-single-input radius-5" name="email"
                                   placeholder="{{__('Your Email Here')}}">
                            <button type="submit"> {{__('Sign Up')}} </button>
                        </div>
                    </form>
                    <div class="form-message-show mt-4"></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        (function($){
            "use strict";

            $(document).ready(function(){
                //Blog Comment Insert
                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var erContainer = $(".error-message");
                    var el = $(this);
                    var form = $('#blog-comment-form');
                    var user_id = $('#user_id').val();
                    var blog_id = $('#blog_id').val();
                    var commented_by = $('#commented_by').val();
                    var comment_content = $('#comment_content').val();
                    let comment_id = $('#blog-comment-form input[name=comment_id]').val();

                    el.text('{{__('Submitting')}}...');

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: {
                            _token: "{{csrf_token()}}",
                            user_id: user_id,
                            blog_id: blog_id,
                            commented_by: commented_by,
                            comment_id: comment_id,
                            comment_content: comment_content,
                        },
                        success: function (data){
                            $('#comment_content').val('');
                            erContainer.html('<div class="mt-3 mb-0 alert alert-'+data.type+'">'+data.msg+'</div>');


                            if (comment_id != '')
                            {
                                location.reload();
                            } else {
                                load_comment_data('{{$blog_post->id}}', 'load-one');

                                $('#blog-comment-form input[name=comment_id]').val('');
                                $('#comment_content').attr('placeholder','Post Comments');
                            }

                            el.text('{{__('Comment')}}');
                        },
                        error: function (data) {
                            var errors = data.responseJSON;

                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function (index, value) {
                                erContainer.find('.alert.alert-danger').append('<p>' + value + '</p>');
                            });
                            el.text('{{__('Comment')}}');
                        },

                    });
                });

                //Blog Replay
                $(document).on('click', '.btn-replay', function (e) {
                    e.preventDefault();
                    let el = $(this);

                    let comment_id = el.data('comment_id');
                    let parent_name = el.siblings('div').children('.blog-details-content-title').find('a').data('parent_name');

                    $('#blog-comment-form input[name=comment_id]').val(comment_id);
                    $('#comment_content').attr('placeholder','Replying to '+ parent_name + '..');

                    $('html').animate({
                        scrollTop: $("#comment_content").offset().top-500
                    },100,'linear');
                });

                function load_comment_data(id, type) {
                    var commentData = $('#comment_data');
                    var items = commentData.attr('data-items');

                    $.ajax({
                        url: "{{ route(route_prefix().'frontend.load.blog.comment.data') }}",
                        method: "POST",
                        data: {id: id, _token: "{{csrf_token()}}", items: items, type: type},
                        success: function (data) {

                            commentData.attr('data-items',parseInt(items) + 5);

                            commentData.find('ul').append(data.markup);
                            $('#load_more_comment_button').text('{{__('Load More')}}');


                            if (data.blogComments.length === 0) {
                                $('#load_more_comment_button').text('{{__('No More Comment Found')}}');

                                setTimeout(function (){
                                    $('#load_more_comment_button').text('{{__('Load More')}}');
                                }, 2000)
                            }

                        }
                    })
                }

                $(document).on('click', '#load_more_comment_button', function () {
                    $(this).text('{{__('Loading...')}}');
                    load_comment_data('{{$blog_post->id}}', 'load-more');
                });

            });
        })(jQuery);

        // When the user scrolls the page, execute myFunction
        window.onscroll = function () {
            myFunction()
        };

        function myFunction() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById("myBar").style.width = scrolled + "%";
        }
    </script>

    <x-custom-js.ajax-login/>
@endsection
