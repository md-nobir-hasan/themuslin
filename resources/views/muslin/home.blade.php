@extends('muslin.layout')

@section('title', 'Home')

@section('content')


  <?php 
    $userAgent = request()->header('User-Agent');
    $isMobile = is_numeric(strpos(strtolower($userAgent), 'mobile'));

    $slider_images = $isMobile ? $home_slider->mobileImages : $home_slider->desktopImages;
  ?>

    @if($slider_images->count() > 0)

      <!-- banner slider start -->
      <section class="banner-slider">
        <!-- Slider main container -->
        <div class="arrows">
          <ul>
            <li class="prev-arrow hover-arrow">
              <svg
                width="10"
                height="18"
                viewBox="0 0 10 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M9 17L1.00001 9L9 1"
                  stroke="white"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </li>
            <li class="next-arrow hover-arrow">
              <svg
                width="10"
                height="18"
                viewBox="0 0 10 18"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M1 1L8.99999 9L1 17"
                  stroke="white"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </li>
          </ul>
        </div>
        <div class="swiper banner-active">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">

            @foreach($slider_images as $slider)
                <!-- Slides -->
                <div class="swiper-slide">
                  
                  <img
                    class="modify-img"
                    data-image-small="{{ asset('assets/uploads/media-uploader/page/' . $slider->path) }}"
                    data-image-large="{{ asset('assets/uploads/media-uploader/page/' . $slider->path) }}"
                    data-image-standard="{{ asset('assets/uploads/media-uploader/page/' . $slider->path) }}"
                    data-src="{{ asset('assets/uploads/media-uploader/page/' . $slider->path) }}"
                    src="{{ asset('assets/muslin/images/static/blur.jpg') }}"
                    alt=""
                  />

                  <div class="container">
                    <div class="row">
                      <div class="col heading">
                        <h1 class="slide-title">{{ $slider->title_text }}</h1>
                      </div>
                    </div>
                  </div>
                </div>

            @endforeach
          </div>
        </div>
      </section>
      <!-- banner slider end -->

    @endif


    <!-- facilities start -->
    <section class="facilities-area">
      <div class="container">
        <div class="swiper facilities">
          <div class="swiper-wrapper">

            {!! $service_slider->content  !!}

          </div>
        </div>
       
      </div>
    </section>



    @if(count($selling_products) > 0)

      <!-- best selling start -->
      <section class="best-area">
        <div class="container">
          <div class="subtitle">
            <h2>Best Selling Product</h2>
            <div class="arrows">
              <ul>
                <li class="custom-prev hover-arrow">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="10"
                    height="18"
                    viewBox="0 0 10 18"
                    fill="none"
                  >
                    <path
                      d="M9 17L1.00001 9L9 1"
                      stroke="black"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </li>
                <li class="custom-next hover-arrow">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="10"
                    height="18"
                    viewBox="0 0 10 18"
                    fill="none"
                  >
                    <path
                      d="M1 1L8.99999 9L1 17"
                      stroke="black"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </li>
              </ul>
            </div>
          </div>
          <div class="swiper best-selling">
            <div class="swiper-wrapper">

              @foreach($selling_products as $product)

                <?php 
                  $image = $product->image;
                ?> 

                  <div class="swiper-slide">
                    <div class="single-item">
                      <a class="link" href="{{ route('product.details', $product->slug) }}"></a>
                      <div class="single-item__img">

                         {!! render_image($image, class: 'modify-img lazyloads') !!}
                        
                      </div>
                      <div class="single-item__content">
                        <span>{{ !empty($product->category->name) ? $product->category->name : '' }}</span>
                        <h6>{{ $product->name }}</h6>
                      </div>
                    </div>
                  </div>

              @endforeach


            </div>
          </div>
        </div>
      </section>
      <!-- best selling end -->

    @endif


    @if(!empty($featured))

    <?php 
      $image = $featured->image;
    ?>  

    <!-- big feature img start -->
    <section class="big-feature">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="subtitle">
              <h2>{{ $featured->name }}</h2>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="feature-card">
              <div class="feature-card__img">
                <div class="feature-card__img__wrap">

                  {!! render_image($image,  class: 'modify-img big lazyloads') !!}

                </div>
              </div>
              <div class="feature-card__btn">
                <a href="{{ route('category', $featured->slug) }}" class="shop-btn">
                  <span>Shop Now</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- big feature img end-->

    @endif

    <!-- all features list start -->
    <section class="feature-list">
      <div class="container">
        <div class="row">

          @if(!empty($top_category))

            @foreach($top_category as $category)

              <?php 
                $image = $category->image;
              ?>  
              <div class="col-lg-6 feature-list__item">
                <div class="feature-list__item__wrap">
                  <div class="feature-list__item__wrap__img">
                    {!! render_image($image,  class: 'modify-img lazyloads') !!}
                  </div>
                </div>
                <div class="feature-list__item__btn">
                  <a href="{{ route('category', $category->slug) }}" class="shop-btn">
                    <span>{{ $category->name }}</span>
                  </a>
                </div>
              </div>

            @endforeach

          @endif

        </div>
      </div>
    </section>
    <!-- all features list end -->
    

    @if(!empty($campaign->count()))

        <!-- best offer area start -->
        <section class="best-offer">
          <div class="container">
            <div class="subtitle">
              <h2>Best Offser</h2>
              <div class="arrows">
                <ul>
                  <li class="custom-prev hover-arrow">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="10"
                      height="18"
                      viewBox="0 0 10 18"
                      fill="none"
                    >
                      <path
                        d="M9 17L1.00001 9L9 1"
                        stroke="black"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </li>
                  <li class="custom-next hover-arrow">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="10"
                      height="18"
                      viewBox="0 0 10 18"
                      fill="none"
                    >
                      <path
                        d="M1 1L8.99999 9L1 17"
                        stroke="black"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </li>
                </ul>
              </div>
            </div>
            <div class="swiper best-offer-active">
              <div class="swiper-wrapper">

                @foreach($campaign as $camp)

                  <div class="swiper-slide">
                    <div class="best-offer__single">
                      <div class="best-offer__single__img">

                        {!! render_image($camp->image,  class: 'modify-img lazyloads') !!}

                      </div>
                      <div class="best-offer__single__btn">
                        <a href="{{ route('campaign', $camp->slug)  }}" class="shop-btn">
                          <span>Shop Now</span>
                        </a>
                      </div>
                    </div>
                  </div>

                @endforeach

              </div>
            </div>
          </div>
        </section>
        <!-- best offer area end -->

    @endif


@endsection
