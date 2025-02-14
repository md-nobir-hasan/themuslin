@extends('muslin.layout') 
@section('title', $campaign ? $campaign->title : '')

@section('content') 


@php  
    $image = !empty($campaign->image) ? $campaign->image : null;
@endphp


<!-- product inner banner start -->
<section class="product-inner-banner">
    <div class="product-inner-banner__wrap"> 
        {!! render_image($image, class: 'modify-img lazyloads', defaultImage: true, imageType:'banner') !!} 
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 product-inner-banner__title">
                <h1>{{ $campaign ? $campaign->title : '' }}</h1>
            </div>
        </div>
    </div>
</section>
<!-- product inner banner end -->


<!-- Product Listing Area Start -->
<section class="product-listing"  style="min-height: 90svh;">
    <div class="container">
        <div class="row">
             <!-- <div class="col-lg-3 col-md-4 widgets">
                <div id="accordion">
                   
                </div>
            </div> -->
            <div class="col-lg-9 col-md-8 product-listing__products">

                
                <div class="product-listing__products__items">
                    <div class="row product-data-list"> 

                            @foreach($campaign->products as $product) 

                                @php 
                                    $image = $product->product->image;
                                @endphp
                                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                                    <div class="single-item">
                                        <a href="{{ route('product.details', $product->product->slug) }}">
                                            <div class="single-item__img"> 
                                                {!! render_image($image, class: 'modify-img lazyloads') !!} 
                                            </div>
                                            <div class="single-item__content">
                                                <span>{{ !empty( $product->product->category->name) ?  $product->product->category->name : '' }}</span>
                                                <h6>{{ $product->product->name }} </h6>
                                            </div>
                                        </a>
                                    </div>
                                </div> 

                            @endforeach 
                        
                        @if($campaign->products->count() == 0)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                <p>No product found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
