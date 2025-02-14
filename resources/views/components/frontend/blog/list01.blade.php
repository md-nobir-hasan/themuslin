<div class="single-blog-item">
    <div class="img-box">
        {!! render_image_markup_by_attachment_id($image) !!}
    </div>
    <div class="content">
        <ul class="post-meta">
            <li class="meta-item">
                <a href="{{ route('frontend.blog.single', $slug) }}">
                    <i class="las la-calendar icon"></i>
                    {{ date('d M Y', strtotime($date)) }}
                </a>
            </li>
            <li class="meta-item">
                <a href="#1">
                    <i class="las la-tag icon"></i>
                    {!! get_blog_category_by_id($catid, 'link') !!}
                </a>
            </li>
        </ul>
        <h4 class="title"><a href="{{ route('frontend.blog.single', $slug) }}">{{ $title }}</a></h4>
        <p class="info">{!! Str::words(purify_html_raw($content), 60) !!}</p>
        <div class="btn-wrapper mt-3">
            <a href="{{ route('frontend.blog.single', $slug) }}" class="borderless-btn btn-default rounded-btn">{{ get_static_option('blog_page_read_more_btn_text') }}</a>
        </div>
    </div>
</div>
