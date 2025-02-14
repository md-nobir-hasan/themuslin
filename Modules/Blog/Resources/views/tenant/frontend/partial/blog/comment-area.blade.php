<div class="details-comment-content">
    <form action="{{route(route_prefix().'frontend.blog.comment.store')}}" method="POST"
          class="comment-form" id="blog-comment-form">
        @csrf

        <input type="hidden" name="comment_id"/>
        <input type="hidden" name="blog_id" id="blog_id" value="{{$blog_post->id}}">
        <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}">
        <input type="hidden" name="commented_by" id="commented_by" value="{{$user->name}}">

        <div class="single-commetns">
            <label class="comment-label"> {{__('Comment')}} </label>
            <textarea name="comment_content" class="form--control radius-5 form--message"
                      placeholder="{{__('Post Comments')}}" id="comment_content"></textarea>
        </div>
        <button type="submit" class="submit-btn radius-5 mt-4"
                id="submitComment"> {{__('Post Comment')}} </button>
    </form>
</div>

