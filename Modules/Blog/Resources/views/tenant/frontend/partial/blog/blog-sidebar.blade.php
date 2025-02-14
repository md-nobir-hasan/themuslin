<div class="shop-icon">
    <div class="sidebar-icon">
        <i class="las la-bars"></i>
    </div>
</div>
<div class="shop-sidebar-content">
    <div class="shop-close-main">
        <div class="close-bars"> <i class="las la-times"></i> </div>
        <div class="single-shop-left">
            <div class="single-shop-left-search">
                <div class="single-shop-left-search-input">
                    <form action="{{route('tenant.frontend.blog.search.page')}}" method="POST">
                        @csrf
                        <input type="text" class="form--control" name="search" placeholder="{{__('Search Products')}}">
                        <button type="submit"> <i class="las la-search"></i> </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="single-shop-left mt-5">
            <div class="shop-left-title open">
                <h5 class="title title-borders fw-500"> {{__('Category')}} </h5>
                <div class="shop-left-list margin-top-15">
                    <ul class="category-lists active-list">
                        @foreach($all_category as $category)
                            <li class="list {{isset($blog_post) == true ? ($blog_post->category_id == $category->id ? 'active' : '') : ''}}">
                                <a href="{{route('tenant.frontend.blog.category', ['slug' => $category->slug])}}" class="item">
                                    <span class="ad-values"> {{$category->title}} </span>
                                    <span> {{$category->blogs_count}} </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="single-shop-left mt-5">
            <div class="shop-left-title open">
                <h5 class="title title-borders fw-500"> {{__('Tags')}} </h5>
                <div class="shop-left-list margin-top-15">
                    <ul class="tag-lists active-list">
                        @foreach($all_tags as $tag)
                            <li class="list">
                                <a class="radius-0" href="{{tenant_blog_tag_route($tag->slug)}}"> {{$tag->title}} </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="single-shop-left mt-5">
            <div class="shop-advertisesdfs">
                {!! render_frontend_sidebar('blog_sidebar',['column' => false]) !!}
            </div>
        </div>
    </div>
</div>
