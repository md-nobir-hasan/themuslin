@extends('tenant.frontend.frontend-page-master')

@section('page-title')
    {{ __('All Blogs') }}
@endsection

@section('site-title')
    {{ __('All Blogs') }}
@endsection

@section('meta-data')
@endsection

@section('content')
    <section class="shop-area padding-top-100 padding-bottom-50">
        <div class="container container-one">
            <div class="shop-contents-wrapper">
                <div class="shop-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                <div class="shop-sidebar-content">
                    @include('blog::tenant.frontend.partial.blog.blog-sidebar')
                </div>
                <div class="shop-grid-contents">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="shop-left">
                                @php
                                    $pagination_data = $blogs->withQueryString()->toArray();
                                @endphp
                                <span class="showing-results color-light me-3"> {{ __('Showing') }}
                                    {{ $pagination_data['from'] }}-{{ $pagination_data['to'] }} {{ __('of') }}
                                    {{ $pagination_data['total'] }} {{ __('results') }} </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="shop-right">
                                <div class="single-shops">
                                    <form action="{{ URL::current() }}" method="GET" id="sorting-form">
                                        <input type="hidden" name="sort">
                                        <div class="shop-nice-select" id="nice-select">
                                            <select>
                                                <option value="1" {{ $order_type == 1 ? 'selected' : '' }}>
                                                    {{ __('Short By Name') }} </option>
                                                <option value="2" {{ $order_type == 2 ? 'selected' : '' }}>
                                                    {{ __('Short By Ascending') }} </option>
                                                <option value="3" {{ $order_type == 3 ? 'selected' : '' }}>
                                                    {{ __('Short By Descending') }} </option>
                                            </select>
                                        </div>
                                    </form>
                                    <ul class="shop-flex-icon tabs">
                                        <li class="shop-icons" data-tab="tab-grid">
                                            <a href="#1" class="icon"> <i class="las la-bars"></i> </a>
                                        </li>
                                        <li class="shop-icons active" data-tab="tab-grid2">
                                            <a href="#1" class="icon"> <i class="las la-border-all"></i> </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab-grid2" class="tab-content-item active">
                        <div class="row mt-2 gy-5">
                            @forelse($blogs as $blog)
                                <div class="col-xxl-4 col-lg-6 col-md-6">
                                    <div class="single-blog-two">
                                        <div class="single-blog-two-thumbs">
                                            <a href="{{ tenant_blog_single_route($blog->slug) }}">
                                                @php
                                                    $img = get_attachment_image_by_id($blog->image);
                                                @endphp
                                                <img class="lazyloads" data-src="{{ $img['img_url'] }}"
                                                    alt="{{ $blog->title }}">
                                            </a>
                                            <div class="single-blog-two-thumbs-date">
                                                <a href="#1">
                                                    <span class="date"> {{ $blog->created_at?->format('d') }} </span>
                                                    <span class="month"> {{ $blog->created_at?->format('M') }} </span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="single-blog-two-contents mt-3">
                                            <h4 class="single-blog-two-contents-title mt-3">
                                                <a href="{{ tenant_blog_single_route($blog->slug) }}">
                                                    {!! purify_html(Str::words($blog->title, 10)) !!} </a>
                                            </h4>
                                            <div class="single-blog-two-contents-tags mt-3">
                                                <span class="single-blog-two-contents-tags-item">
                                                    @php
                                                        $tags = explode(',', $blog->tags);
                                                    @endphp

                                                    @foreach ($tags as $tag)
                                                        @break($loop->index >= 3)
                                                        <a href="{{ tenant_blog_tag_route(Str::slug($tag)) }}"> <i
                                                                class="las la-tag"></i> {{ $tag }} </a>
                                                    @endforeach
                                                </span>

                                                @if (count($blog->comments) > 0)
                                                    <span class="single-blog-two-contents-tags-item">
                                                        <a
                                                            href="{{ url(tenant_blog_single_route($blog->slug) . '/#comment-area') }}">
                                                            {{ count($blog->comments) }}
                                                            {{ count($blog->comments) == 1 ? __('Comment') : __('Comments') }}
                                                        </a>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-xxl-4 col-lg-6 col-md-6">
                                    <div class="single-blog-two">
                                        <div class="single-blog-two-thumbs">
                                            <a href="01_blog_details.html"> <img class="lazyloads"
                                                    data-src="assets/img/blog/theme1_b1.jpg" alt=""> </a>
                                            <div class="single-blog-two-thumbs-date">
                                                <a href="#1"> <span class="date"> 18 </span> <span class="month">
                                                        Mar </span> </a>
                                            </div>
                                        </div>
                                        <div class="single-blog-two-contents mt-3">
                                            <h4 class="single-blog-two-contents-title mt-3"> <a href="01_blog_details.html">
                                                    The quickest way to know a woman is to go shopping with her </a> </h4>
                                            <div class="single-blog-two-contents-tags mt-3">
                                                <span class="single-blog-two-contents-tags-item">
                                                    <a href="#1"> <i class="las la-tag"></i> Fashion </a>
                                                    <a href="#1"> <i class="las la-tag"></i> Food </a>

                                                </span>
                                                <span class="single-blog-two-contents-tags-item"> <a href="#1"> 22
                                                        Comments </a> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{ $blogs->links() }}
                    </div>
                    <div id="tab-grid" class="tab-content-item">
                        <div class="row mt-2 gy-5">
                            @forelse($blogs as $blog)
                                <div class="col-sm-12">
                                    <div class="single-flex-blog d-flex align-items-center">
                                        @php
                                            $img = get_attachment_image_by_id($blog->image);
                                        @endphp
                                        <div class="single-flex-blog-thumbs flex-blog-height bg-image"
                                            style="background-image: url({{ $img['img_url'] }});">
                                        </div>
                                        <div class="single-flex-blog-contents mt-0 mt-xxs-3">
                                            <span class="single-flex-blog-contents-dates">
                                                {{ $blog->created_at?->format('d M Y') }} </span>
                                            <h4 class="single-flex-blog-contents-title fw-500 mt-3">
                                                <a href="{{ tenant_blog_single_route($blog->slug) }}">
                                                    {!! purify_html(Str::words($blog->title, 10)) !!} </a>
                                            </h4>
                                            <p class="single-flex-blog-contents-para mt-4"> {!! purify_html(Str::words($blog->blog_content, 50)) !!} </p>
                                            <div class="single-blog-two-contents-tags mt-3">
                                                <span class="single-blog-two-contents-tags-item">
                                                    @php
                                                        $tags = explode(',', $blog->tags);
                                                    @endphp

                                                    @foreach ($tags as $tag)
                                                        @break($loop->index >= 5)
                                                        <a href="{{ tenant_blog_tag_route(Str::slug($tag)) }}"
                                                            class="ff-jost"> <i class="las la-tag"></i> {{ $tag }}
                                                        </a>
                                                    @endforeach

                                                </span>
                                                @if (count($blog->comments) > 0)
                                                    <span class="single-blog-two-contents-tags-item">
                                                        <a
                                                            href="{{ url(tenant_blog_single_route($blog->slug) . '/#comment-area') }}">
                                                            {{ count($blog->comments) }}
                                                            {{ count($blog->comments) == 1 ? __('Comment') : __('Comments') }}
                                                        </a>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-sm-12">
                                    <div class="single-flex-blog d-flex align-items-center">
                                        <div class="single-flex-blog-thumbs flex-blog-height bg-image"
                                            style="background-image: url(assets/img/blog/theme1_b1.jpg);">
                                        </div>
                                        <div class="single-flex-blog-contents mt-0 mt-xxs-3">
                                            <span class="single-flex-blog-contents-dates"> 20 Mar 2022 </span>
                                            <h4 class="single-flex-blog-contents-title fw-500 mt-3"> <a
                                                    href="01_blog_details.html"> Does your whole team understand how to use
                                                    effectively?
                                                </a> </h4>
                                            <p class="single-flex-blog-contents-para mt-4"> The shoes set up the tone and
                                                attitude, they change the perception of the way one wears clothes, what we
                                                call in France le porté. The shoes set up the tone and attitude, they change
                                                the perception of the way one
                                                wears clothes, what we call in France le porté. </p>
                                            <div class="single-blog-two-contents-tags mt-3">
                                                <span class="single-blog-two-contents-tags-item">
                                                    <a href="#1" class="ff-jost"> <i class="las la-tag"></i> Fashion
                                                    </a>
                                                    <a href="#1" class="ff-jost"> <i class="las la-tag"></i> Food
                                                    </a>

                                                </span>
                                                <span class="single-blog-two-contents-tags-item"> <a href="#1"> 17
                                                        Comments </a> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforelse

                            {{ $blogs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        (function($) {
            $(document).ready(function() {
                $(document).on('click', '.shop-nice-select .list li', function(e) {
                    e.preventDefault();
                    let form = $('#sorting-form');
                    let input = $('input[name=sort]');

                    let el = $(this);
                    let sort_value = el.data('value');

                    input.val(sort_value);
                    form.trigger('submit');
                });
            });
        })(jQuery);
    </script>
@endsection
