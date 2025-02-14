@extends('muslin.layout')
 
@section('title', 'Blogs')

@section('content')

	<!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li><a href="#">Blogs</a></li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->

    <section class="blog-area"  style="min-height: 90svh;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 blog-area__title">
            <h1>Blogs</h1>
          </div>
        </div>

        <div class="row">

        	@if(!empty($blogs))
        		@foreach($blogs as $blog)

        			<?php 
	                $image = $blog->blogImage;
							?> 

			        <div class="col-lg-3 col-md-4 col-sm-2 blog-area__single-blog">
			            <a class="link" href="{{ route('blog.detail', $blog->slug) }}"></a>
			            <div class="blog-area__single-blog__img">

			            		{!! render_image($image, class: 'modify-img lazyloads') !!}
			            		
			            </div>
			            <div class="blog-area__single-blog__wrap">
			              	<span>{{ !empty($blog->date) ? date('d F Y', strtotime($blog->date)) : '' }}</span>
			              	<p> {{ $blog->title }} </p>
			            </div>
			        </div>

			    @endforeach
				@endif


        </div>
      </div>
    </section>

@endsection