{{--<div class="comment-area padding-top-60 padding-bottom-60">--}}
{{--    <h3 class="details-section-title"> Comments(03) </h3>--}}
{{--    <div class="row">--}}
{{--        <div class="col-lg-12">--}}
{{--            <ul class="comment-list">--}}
{{--                @php--}}
{{--                    $blogComments = $blog_post?->comments;--}}
{{--                @endphp--}}
{{--                @foreach($blogComments as $key => $data)--}}
{{--                    @php--}}
{{--                        $avatar_image =--}}
{{--                        $commented_user_image = render_image_markup_by_attachment_id($data?->user?->image ?? get_static_option('blog_avater_image'),'','thumb');--}}
{{--                    @endphp--}}
{{--                    <li>--}}
{{--                        <div class="single-comment-wrap">--}}
{{--                            <div class="thumb">--}}
{{--                                {!! $commented_user_image !!}--}}
{{--                            </div>--}}
{{--                            <div class="content">--}}
{{--                                <div class="content-top">--}}
{{--                                    <div class="left">--}}
{{--                                        <h4 class="title" data-parent_name="{{optional($data->user)->name }}">{{optional($data->user)->name ?? ''}}</h4>--}}
{{--                                        <ul class="post-meta">--}}
{{--                                            <li class="meta-item comment-date">--}}
{{--                                                <i class="lar la-calendar icon"></i>--}}
{{--                                                {{date('d F Y', strtotime($data->created_at ?? ''))}}--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <p class="comment common-para">{!! $data->comment_content ?? '' !!}--}}
{{--                                </p>--}}

{{--                                @if(auth('web')->check() && auth('web')->id() != $data->user_id)--}}
{{--                                    <div class="reply">--}}
{{--                                        <a href="#1" data-comment_id="{{ $data->id }}" class="reply-btn btn-replay"></i><span class="text">{{__('Reply')}}</span></a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </li>--}}

{{--                    <li class="has-children">--}}
{{--                        <ul>--}}
{{--                            @foreach($data->reply as $repData)--}}
{{--                                @php--}}
{{--                                    $commented_child_author_image = render_image_markup_by_attachment_id(get_static_option('blog_avater_image'),'','thumb');--}}
{{--                                @endphp--}}
{{--                                <li>--}}
{{--                                    <div class="single-comment-wrap">--}}
{{--                                        <div class="thumb">--}}
{{--                                            {!! $commented_child_author_image !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="content">--}}
{{--                                            <div class="content-top">--}}
{{--                                                <div class="left">--}}
{{--                                                    <h4 class="title">{{optional($repData->user)->name }}</h4>--}}
{{--                                                    <ul class="post-meta">--}}
{{--                                                        <li class="meta-item">--}}
{{--                                                            <i class="lar la-calendar icon"></i>--}}
{{--                                                            {{date('d F Y', strtotime($repData->created_at ?? ''))}}--}}
{{--                                                        </li>--}}
{{--                                                    </ul>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <p class="comment common-para">{!! $repData->comment_content ?? '' !!}</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="comment-area padding-top-60 padding-bottom-60">
{{--    <h3 class="details-section-title"> Comments{{$blogComments->count() ? '('.$blogComments->count().')' : ''}} </h3>--}}
    <div class="row">
        <div class="col-lg-12 mt-2">
            <div class="comment-show-contents" id="comment_data" data-items="{{$blog_comments->count()}}">

                <ul class="comment-list wow fadeInLeft" data-wow-delay=".1s">
                    @foreach($blog_comments as $key => $data)
                        @php
                            $avatar_image =
                            $commented_user_image = render_image_markup_by_attachment_id($data?->user?->image ?? get_static_option('blog_avater_image'),'','thumb');
                        @endphp
                        <li>
                            <div class="blog-details-flex-content">
                                <div class="blog-details-thumb radius-10">
                                    {!! $commented_user_image !!}
                                </div>
                                <div class="blog-details-content">
                                    <div class="blog-details-content-flex">
                                        <div class="blog-details-content-item">
                                            <h5 class="blog-details-content-title"><a
                                                    href="#1" data-parent_name="{{optional($data->user)->name }}"> {{optional($data->user)->name ?? ''}} </a>
                                            </h5>
                                            <span
                                                class="blog-details-content-date"> {{date('d F Y', strtotime($data->created_at ?? ''))}} </span>
                                        </div>

{{--                                        @if(auth('web')->check() && auth('web')->user()->id() != $data->user_id)--}}
                                        <a href="#1" class="reply-btn btn-replay" data-comment_id="{{ $data->id }}"> <i
                                                class="las la-reply-all"></i> {{__('Reply')}} </a>
{{--                                        @endif--}}
                                    </div>
                                    <p class="blog-details-content-para"> {!! $data->comment_content ?? '' !!} </p>
                                </div>
                            </div>

                            @foreach($data->reply as $key => $repData)
                                @php
                                    $commented_child_author_image = render_image_markup_by_attachment_id(get_static_option('blog_avater_image'),'','thumb');
                                @endphp
                                <ul class="comment-list wow fadeInLeft" data-wow-delay=".2s">
                                <li>
                                    <div class="blog-details-flex-content">
                                        <div class="blog-details-thumb radius-10">
                                            {!! $commented_child_author_image !!}
                                        </div>
                                        <div class="blog-details-content">
                                            <div class="blog-details-content-flex">
                                                <div class="blog-details-content-item">
                                                    <h5 class="blog-details-content-title"><a
                                                            href="#1"> {{optional($repData->user)->name }} </a></h5>
                                                    <span class="blog-details-content-date"> {{date('d F Y', strtotime($repData->created_at ?? ''))}} </span>
                                                </div>
                                                <a href="#1" class="btn-replay"> <i
                                                        class="las la-reply-all"></i> Reply </a>
                                            </div>
                                            <p class="blog-details-content-para"> {!! $repData->comment_content ?? '' !!} </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="btn-wrapper mt-4">
                <a href="#1" class="btn-see-more" id="load_more_comment_button"> {{__('Show More')}} </a>
            </div>
        </div>
    </div>
</div>
