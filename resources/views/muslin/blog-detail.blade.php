@extends('muslin.layout')
 
@section('title', $blog->title)

@section('content')


  <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li><a href="{{ route('blogs') }}">Blogs</a></li>
              <li>
                {{ $blog->title }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->

    <!-- blog details area start -->
    <section class="blog-details"  style="min-height: 90svh;">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="blog-details__title">
              <h2>{{ $blog->title }}</h2>
              <p>{{ !empty($blog->date) ? date('d M Y', strtotime($blog->date)) : '' }}</p>
            </div>

            <div>
                {!! $blog->blog_content !!}
            </div>


          </div>
        </div>
      </div>
    </section>
    <!-- blog details area end -->

@endsection
