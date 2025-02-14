@php
    $category_url = route('frontend.blog.category',  ['id' => optional($blog->category)->id, 'name' => optional($blog->category)->title]) ?? '';
    $read_more_btn_text = get_static_option('blog_page_read_more_btn_text');
@endphp

<div class="col-md-6 col-lg-4">
    <div class="single-blog-item">
        <div class="img-box">
            {!! render_image_markup_by_attachment_id($blog->image) !!}
        </div>
        <div class="content">
            <ul class="post-meta">
                <li class="meta-item">
                    <a href="{{ route('frontend.blog.single', $blog->slug) }}">
                        <i class="las la-calendar icon"></i>
                        {{ $blog->created_at->format('D M Y') }}
                    </a>
                </li>
                <li class="meta-item">
                    <a href="{{ $category_url }}">
                        <i class="las la-tag icon"></i>
                        {{ optional($blog->category)->name }}
                    </a>
                </li>
            </ul>
            <h4 class="title"><a href="{{ route('frontend.blog.single', $blog->slug) }}">{!! Str::limit(purify_html_raw(strip_tags($blog->title)), 55) !!}</a></h4>
            <div class="btn-wrapper">
                <a href="{{ route('frontend.blog.single', $blog->slug) }}" class="borderless-btn">{{ $readMoreBtnText }}</a>
            </div>
        </div>
    </div>
</div>
