@extends('landlord.frontend.frontend-page-master')
@section('site-title')
    {{ $search_term}}
@endsection
@section('page-title')
    {{__('Search For: ').$search_term}}
@endsection
@section('content')

    <section class="blog-content-area padding-120 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        @if(count($all_blogs) < 1)
                            <div class="col-lg-12">
                                <div class="alert alert-danger">
                                    {{__('Nothing found related to ').$search_term}}
                                </div>
                            </div>
                        @endif
                        @foreach($all_blogs as $data)
                            <div class="col-lg-6 col-md-6">
                                <div class="single-blog-grid margin-bottom-30">
                                    <div class="thumb">
                                        {!! render_image_markup_by_attachment_id($data->image) !!}
                                    </div>
                                    <div class="content">
                                        <ul class="post-meta">
                                            <li><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}"><i class="las la-calendar-alt"></i> {{$data->created_at->diffForHumans()}}</a></li>
                                            <li><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">{{__('By ')}} {{($data->author) ?? __("Anonymous")}}</a></li>
                                        </ul>
                                        <h4 class="title"><a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}">{{$data->getTranslation('title',get_user_lang())}}</a></h4>
                                        <div class="description">
                                            {!! Str::words($data->content,55) !!}
                                        </div>
                                        <a href="{{route(route_prefix().'frontend.blog.single',$data->slug)}}" class="readmore">{{__("Read More")}}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                        <nav class="pagination-wrapper" aria-label="Page navigation ">
                            {{$all_blogs->links()}}
                        </nav>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog_single_sidebar_sidebar">
                        {!! render_frontend_sidebar('sidebar',['column' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
