@extends('muslin.layout')
 
@section('title', $data->title)

@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li>{{ $data->title }}</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
    <!-- breadcrumb section end -->

    <section class="policy-area"  style="min-height: 90svh;">
      <div class="container">
        <div class="row">
          <div class="col-md-12 policy-area__top">
            <div class="subtitle">
              <h2>{{ $data->title }}</h2>
            </div>
          </div>
        </div>

        {!! $data->content !!}

      </div>
    </section>

@endsection
