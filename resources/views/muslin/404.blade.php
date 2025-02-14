
@extends('muslin.layout')

@section('title', 'Page Not Found')

@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li>404</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->

    <!-- about area start -->

    <section class="about-area">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div style="text-align: center;">
              <h2>404</h2>
              <br>
              <h2>Page Not Found</h2>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- about area end -->

@endsection
