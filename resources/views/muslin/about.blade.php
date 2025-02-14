
@extends('muslin.layout')

@section('title', 'About Us')

@section('content')

    <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul>
              <li><a href="{{ route('homepage') }}">Home</a></li>
              <li>About Us</li>
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
            <div class="subtitle">
              <h2>{{ $data->title}}</h2>
            </div>
          </div>
        </div>
        <div class="row about-area__wrap">

          <div class="col-lg-8 about-area__wrap__left">

            {!! $data->content !!}
          </div>

          
          <div class="col-lg-4 about-area__wrap__right">
            <div class="about-area__wrap__right__img">

              {!! render_image($data->imageInfo_1, class: 'modify-img lazyloads') !!}

            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- about area end -->

    <!-- ceo start -->
    <section class="ceo-area">
      <img
        class="modify-img"
        data-image-small="{{ asset('assets/muslin/images/dynamic/about/background.jpg') }}"
        data-image-large="{{ asset('assets/muslin/images/dynamic/about/background.jpg') }}"
        data-image-standard="{{ asset('assets/muslin/images/dynamic/about/background.jpg') }}"
        data-src=""
        src="{{ asset('assets/muslin/images/dynamic/about/background.jpg') }}"
        alt="ceo background image"
      />
      <div class="container">
        <div class="row">
          <div class="col-md-4 ceo-area__left">
            <div class="ceo-area__left__img">
              
                {!! render_image($data->imageInfo_2, class: 'modify-img lazyloads') !!}
            </div>
          </div>
          <div class="col-md-5 offset-md-1 ceo-area__right">
            <h3>Tasnuva Islam</h3>
            <p>Founder, Director and CEO The Muslin</p>
            <div class="ceo-area__right__content">
              <p>
                Tasnuva Islam is a visionary entrepreneur, a driving force
                behind The Muslin, a distinguished brand established in 2021
                proud sister concern of Iconx Lifestyle Limited. With an
                unwavering commitment to preserving and showcasing the rich
                cultural heritage of Bangladesh, Tasnuva has led The Muslin to
                become a cherished entity within the business landscape.
              </p>
              <p>
                Her astute leadership and innovative approach have not only
                elevated the brand's presence but also contributed significantly
                to the cultural recognition of the nation.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ceo end -->

@endsection
