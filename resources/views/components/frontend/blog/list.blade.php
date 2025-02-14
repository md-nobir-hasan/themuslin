@php
    $category_url = route('frontend.blog.category', ['id' => optional($blog->category)->id, 'name' => optional($blog->category)->title]) ?? '';
@endphp

<div class="single-blog-list-item-style-1">
    <div class="img-box">
        {!! render_image_markup_by_attachment_id($blog->image) !!}
    </div>
    <div class="blog-contents">
        <div class="post-meta">
            <ul class="post-meta-list">
                <li class="post-meta-item">
                    <a href="{{ route('frontend.blog.single', $blog->slug) }}">
                        <i class="lar la-calendar icon"></i>
                        <span class="text">{{ date_format($blog->created_at, 'Y F Y') }}</span>
                    </a>
                </li>
                <li class="post-meta-item">
                    <a href="{{ $category_url }}">
                        <i class="las la-tag icon"></i>
                        <span class="text">{{ optional($blog->category)->name }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="blog-contents-inner mt-2">
            <h4 class="blog-contents-inner-title">
                <a href="{{ route('frontend.blog.single', $blog->slug) }}">
                    {!! Str::limit(purify_html_raw($blog->title), 70) !!}
                </a>
            </h4>
            <p class="blog-contents-inner-info mt-3">{!! Str::limit(purify_html_raw($blog->blog_content), 378) !!}</p>
            <div class="btn-wrapper mt-3">
                <a href="{{ route('frontend.blog.single', $blog->slug) }}"
                    class="blog-contents-btn btn-default rounded-btn outline-blog-button">{{ $readMoreBtnText }}</a>
            </div>
        </div>
    </div>
</div>
