
@extends('muslin.layout')

@section('title', 'Server Issue')

@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li>500</li>
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
              <h2>500</h2>
              <br>
              <h2>Internal Server Error</h2>

              <p>{{__('Please contact support')}}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- about area end -->

@endsection
