<div class="single-blog-grid-01">
    <div class="thumb">
        {!! render_image_markup_by_attachment_id($image,'grid') !!}
    </div>
    <div class="content-wrapper">
        <div class="news-date">
            <h5 class="title">{{ date_format($date,'d') }}</h5>
            <span>{{ date_format($date,'y') }}</span>
        </div>
        <div class="content">
            <ul class="post-meta">
                <li>{{ __('By') }} {{ $author ?? __('Anonymous') }}</li>
                <li>
                    {!! get_blog_category_by_id($catid, 'link') !!}
                </li>
            </ul>
            <h4 class="title"><a href="{{ route('frontend.blog.single',$slug) }}">{{ $title }}</a></h4>
        </div>
    </div>
</div>
