@php
    $user = Auth::guard('web');
@endphp
@if($user->check())
    <div class="comment-area color-two padding-top-50">
        <h3 class="details-section-title theme-one"> {{__('Post Your Comment')}} </h3>

        <div class="error-message"></div>

        <div class="row">
            <div class="col-lg-12 padding-top-20">
                <div class="details-comment-content">
                    <form action="{{route(route_prefix().'frontend.blog.comment.store')}}" method="POST" class="comment-form" id="blog-comment-form">
                        @csrf
                        <input type="hidden" name="comment_id"/>
                        <input type="hidden" name="blog_id" id="blog_id" value="{{$blog_post->id}}">
                        <input type="hidden" name="user_id" id="user_id" value="{{$user->user()->id}}">
                        <input type="hidden" name="commented_by" id="commented_by" value="{{$user->user()->name}}">

                        <div class="single-commetns">
                            <label class="comment-label"> {{__('Comment')}} </label>
                            <textarea name="comment_content" id="comment_content" class="form--control radius-5 form--message" placeholder="{{__('Post Comments')}}"></textarea>
                        </div>
                        <button type="submit" id="submitComment" class="submit-btn radius-5 mt-4"> {{__('Post Comment')}} </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    login koren
@endif
