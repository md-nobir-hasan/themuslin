<div class="single-news-update">
    <div class="news-flex-content">
        <div class="news-update-thumb radius-10">
            <a href="{{ route('frontend.blog.single', $blog->slug) }}">
                {!! render_image($blog->blogImage) !!}
            </a>
        </div>
        <div class="news-update-contents">
            <h5 class="common-title-three hover-color-two">
                <a href="{{ route('frontend.blog.single', $blog->slug) }}">
                    {{ $blog->title }}
                </a>
            </h5>
            <span class="dates d-block mt-2"> {{ $blog->created_at->format("Y F d") }} </span>
        </div>
    </div>
</div>