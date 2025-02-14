<div class="comment-area padding-top-60 padding-bottom-60" id="comment-area">
    <h3 class="details-section-title"> {{__('Comments')}}{{$blog_comments->count() ? '('.$blog_comments->count().')' : ''}} </h3>
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

                                        @if(auth('web')->check())
                                        <a href="#1" class="reply-btn btn-replay" data-comment_id="{{ $data->id }}"> <i
                                                class="las la-reply-all"></i> {{__('Reply')}} </a>
                                        @endif
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
