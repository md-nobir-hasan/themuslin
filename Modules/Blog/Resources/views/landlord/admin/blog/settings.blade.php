@extends(route_prefix().'admin.admin-master')
@section('site-title') {{__('Blog Settings')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Blog Settings')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                <form class="forms-sample" method="post" action="{{route(route_prefix().'admin.blog.settings')}}">
                    @csrf

                    <x-fields.input type="text" value="{{get_static_option('blogs_page_item_show')}}" name="blogs_page_item_show" label="{{__('Blog Page Item Show')}}"/>
                    <x-fields.input type="text" value="{{get_static_option('category_page_item_show')}}" name="category_page_item_show" label="{{__('Category Page Item Show')}}"/>
                    <x-fields.input type="text" value="{{get_static_option('tag_page_item_show')}}" name="tag_page_item_show" label="{{__('Tag Page Item Show')}}"/>
                    <x-fields.input type="text" value="{{get_static_option('search_page_item_show')}}" name="search_page_item_show" label="{{__('Search Page Item Show')}}"/>

                    @if(tenant())
                      <x-fields.media-upload name="blog_avater_image" title="{{__('Blog Comment Avatar')}}"/>
                    @endif

                    <button type="submit" class="btn btn-gradient-primary me-2">{{__('Save Changes')}}</button>
                </form>
            </div>
        </div>
        </div>
    <x-media-upload.markup/>
@endsection
@section('script')
    <x-media-upload.js/>
@endsection
