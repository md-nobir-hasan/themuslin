@extends('muslin.layout') 

@section('title', 'Search')

@section('content') 


@php  
  $image = null;
@endphp


<!-- product inner banner start -->
<section class="product-inner-banner">
    <div class="product-inner-banner__wrap"> 
        {!! render_image($image, class: 'modify-img lazyloads', defaultImage: true, imageType:'banner' ) !!} 
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 product-inner-banner__title">
            </div>
        </div>
    </div>
</section>
<!-- product inner banner end -->


<?php 
    $userAgent = request()->header('User-Agent');
    $isMobile = is_numeric(strpos(strtolower($userAgent), 'mobile'));

    $isTablet = false;
    if (strpos(strtolower($userAgent), 'tablet') !== false || strpos(strtolower($userAgent), 'ipad') !== false) {
        $isTablet = true;
    }
?>


<!-- Product Listing Area Start -->
<section class="product-listing">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 widgets">
                <div id="accordion">

                    @if(!$isMobile || $isTablet)

                        <!-- Categories -->
                        <div class="card">
                            <div class="card-header" id="headingCategories">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories"> Categories <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="category" />
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseCategories" class="collapse show" aria-labelledby="headingCategories" data-parent="#accordion">
                                <div class="card-body"> 
                                    @if(!empty($categories) && count($categories) > 0) 
                                        <ul> 
                                            @foreach($categories as $category) 
                                                <li>
                                                    <input type="radio" name="category" class="search-param" value="{{ $category->slug }}" />
                                                    <label for="sharee">{{ $category->name }}</label>
                                                </li> 
                                            @endforeach 
                                        </ul>
                                    @endif 
                                </div>
                            </div>
                        </div>

                        <!-- Sub-Categories -->
                        <div class="card subcategory-card" style="display: none;">
                            <div class="card-header" id="headingSubCategories">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSubCategories" aria-expanded="false" aria-controls="collapseSubCategories"> Sub-Categories <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="subcategory" />
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseSubCategories" class="collapse show" aria-labelledby="headingSubCategories" data-parent="#accordion">
                                <div class="card-body">
                                    
                                    <ul class="subcategory-ul"> 
                                        
                                    </ul> 
                                </div>
                            </div>
                        </div>

                        <!-- Child-Categories -->
                        <div class="card childcategory-card" style="display: none;">
                            <div class="card-header" id="headingChildCategories">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseChildCategories" aria-expanded="false" aria-controls="collapseChildCategories"> Child Categories <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="subcategory" />
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseSubCategories" class="collapse show" aria-labelledby="headingChildCategories" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="card-body"> 
                                        
                                        <ul class="childcategory-ul"> 
                                        </ul> 

                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($colors->count() > 0)

                            <!-- Color -->
                            <div class="card">
                                <div class="card-header" id="headingColor">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseColor" aria-expanded="false" aria-controls="collapseColor"> 
                                            Color 
                                            <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="color" />
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseColor" class="collapse show" aria-labelledby="headingColor" data-parent="#accordion">
                                    <div class="card-body">
                                        <div class="card-body">
                                            <ul>
                                                @foreach ($colors as $key => $value)
                                                    <li>
                                                        <input type="checkbox" name="color" value="{{ $value->productColor->name }}" class="search-param" />
                                                        <label for="red">{{ $value->productColor->name }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($sizes->count() > 0)

                            <!-- Size -->
                            <div class="card">
                                <div class="card-header" id="headingSize">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSize" aria-expanded="false" aria-controls="collapseSize">
                                            Size 
                                            <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="size" />
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseSize" class="collapse show" aria-labelledby="headingSize" data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            @foreach ($sizes as $key => $value)
                                                <li>
                                                    <input type="checkbox" name="size" value="{{ $value->productSize->name }}" class="search-param" />
                                                    <label>{{ $value->productSize->name }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif


                    @endif

                </div>
            </div>

            <div class="col-lg-9 col-md-8 product-listing__products">
                <div class="product-listing__products__sort">
                    <div class="product-listing__products__sort__left">
                        <img src="{{ asset('assets/muslin/images/static/filter.svg') }}" alt="filter icon" />
                        <p class="pagination"> Showing {{ count($products ) }} of {{ count($products ) }} results</p>
                        <p class="filter">Filter</p>
                    </div>
                    <div class="product-listing__products__sort__right">
                        <span>Sort by:</span>

                        <div class="Select search-param" id="sorting">
                            <select name="sort" class="sort">
                                <option value="asc">A-Z</option>
                                <option value="desc">Z-A</option>
                                <option value="low_high">Low-High</option>
                                <option value="high_low">High-Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="product-listing__products__items">
                    <div class="row product-data-list"> 
                        @foreach($products as $product) 
                            @php 
                                $image = $product->image;
                            @endphp

                            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                <div class="single-item">
                                    <a href="{{ route('product.details', $product->slug) }}">
                                        <div class="single-item__img"> 
                                            {!! render_image($image, class: 'modify-img lazyloads') !!} 
                                        </div>
                                        <div class="single-item__content">
                                            <span>{{ !empty( $product->category->name) ?  $product->category->name : '' }}</span>
                                            <h6>{{ $product->name }} </h6>
                                        </div>
                                    </a>
                                </div>
                            </div> 

                        @endforeach 

                        @if(empty($products->count() > 0))

                            <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                <p>No product found</p>
                            </div>

                        @endif
                    </div>

                    <!-- Pagination Links -->
                    <div class="product-pagination">

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>


@if($isMobile)

<!-- this widget will be show on the mobile device -->
<div class="widgets global-widget">
    <div class="global-widget__top">
        <p>Filter</p>
        <svg id="close" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
            <path d="M15 30C23.2843 30 30 23.2843 30 15C30 6.71573 23.2843 0 15 0C6.71573 0 0 6.71573 0 15C0 23.2843 6.71573 30 15 30Z" fill="#F9F9F9" />
            <path d="M10 10L20 20" stroke="#221F1F" stroke-linecap="round" />
            <path d="M10 20L20 10" stroke="#221F1F" stroke-linecap="round" />
        </svg>
    </div>
    <div id="accordion">

        <!-- Categories -->
        <div class="card">
            <div class="card-header" id="headingCategories">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories"> 
                        Categories 
                        <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="category" />
                    </button>
                </h5>
            </div>
            <div id="collapseCategories" class="collapse show" aria-labelledby="headingCategories" data-parent="#accordion">
                <div class="card-body"> 

                    @if(!empty($categories) && count($categories) > 0) 
                        <ul> 
                            @foreach($categories as $value) 
                                <li>
                                    <input type="radio" name="category" value="{{ $value->slug }}"  />
                                    <label for="sharee">{{ $value->name }}</label>
                                </li> 

                            @endforeach 
                        </ul>
                    @endif 
                </div>
            </div>
        </div> 


        <!-- Sub-Categories -->
        <div class="card subcategory-card" style="display: none;">
            <div class="card-header" id="headingSubCategories">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSubCategories" aria-expanded="false" aria-controls="collapseSubCategories"> 
                        Sub-Categories 
                        <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="subcategory" />
                    </button>
                </h5>
            </div>
            <div id="collapseSubCategories" class="collapse show" aria-labelledby="headingSubCategories" data-parent="#accordion">
                <div class="card-body">
                    <ul  class="subcategory-ul"> 
                    </ul>
                </div>
            </div>
        </div> 

        <!-- Child-Categories -->
        <div class="card childcategory-card" style="display: none;">
            <div class="card-header" id="headingChildCategories">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseChildCategories" aria-expanded="false" aria-controls="collapseChildCategories"> 
                        Child-Categories 
                        <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="subcategory" />
                    </button>
                </h5>
            </div>
            <div id="collapseChildCategories" class="collapse show" aria-labelledby="headingChildCategories" data-parent="#accordion">
                <div class="card-body">
                    <ul class="childcategory-ul">
                    </ul>
                </div>
            </div>
        </div> 

        @if($colors->count() > 0)

            <!-- Color -->
            <div class="card">
                <div class="card-header" id="headingColor">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseColor" aria-expanded="false" aria-controls="collapseColor"> Color <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="color" />
                        </button>
                    </h5>
                </div>
                <div id="collapseColor" class="collapse show" aria-labelledby="headingColor" data-parent="#accordion">
                    <div class="card-body">
                        <div class="card-body">
                            <ul>
                                @foreach ($colors as $key => $value)
                                    <li>
                                        <input type="checkbox" name="color" value="{{ $value->productColor->name }}" class="search-param" />
                                        <label>{{ $value->productColor->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        @endif


        @if($sizes->count() > 0)

            <!-- Size -->
            <div class="card">
                <div class="card-header" id="headingSize">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSize" aria-expanded="false" aria-controls="collapseSize"> 
                            Size 
                            <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="size" />
                        </button>
                    </h5>
                </div>
                <div id="collapseSize" class="collapse show" aria-labelledby="headingSize" data-parent="#accordion">
                    <div class="card-body">
                        <ul>
                            @foreach ($sizes as $key => $value)
                                <li>
                                    <input type="checkbox" name="size" value="{{ $value->productSize->name }}" class="search-param" />
                                    <label>{{ $value->productSize->name }}</label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div> 


@endif

@endsection




@push('scripts')
    <script type="text/javascript">

        $('input[type="radio"][name="category"]').change(function() 
        {
            var checkedValues = $('input[name="category"]:checked').map(function() {
                    return this.value;
                }).get();

            if(checkedValues.length > 0) {

                $.ajax({
                    url: "{{ route('category.info') }}", 
                    type: 'POST',
                    data: {ids:checkedValues,type:'category',_token: "{{ csrf_token() }}"}, 
                    success: function(response) {

                        if(response.type == 'success' && response.html.length > 10) {

                            $('.subcategory-ul').html(response.html);
                            $('.subcategory-card').show();

                            $('.childcategory-ul').html('');
                            $('.childcategory-card').hide();
                        }
                        else {
                            $('.subcategory-card').hide();
                            $('.childcategory-ul').html('');
                            $('.childcategory-card').hide();
                        }
                        
                        search();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                }); 
            }
            
            return false;
        });


        $(document).on('change', 'input[type="radio"][name="subcategory"]', function() 
        {
            var checkedValues = $('input[name="subcategory"]:checked').map(function() {
                    return this.value;
                }).get();

            if(checkedValues.length > 0) {
                $.ajax({
                    url: "{{ route('category.info') }}", 
                    type: 'POST',
                    data: {ids:checkedValues,type:'subcategory',_token: "{{ csrf_token() }}"}, 
                    success: function(response) {

                        if(response.type == 'success' && response.html.length > 10) {
                            $('.childcategory-ul').html(response.html);
                            $('.childcategory-card').show();
                        }
                        else {
                            $('.childcategory-ul').html('');
                            $('.childcategory-card').hide();
                        }

                        search();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            return false;
        });

        $(document).on('click', '.ajax-search', function (e) {
            e.preventDefault();
            var page = $(this).attr('data-id');

            search(page);
        });

        function search(page = 1) {

            var category = $('input[name="category"]:checked').map(function() {
                                return this.value;
                            }).get();

            var subcategory = $('input[name="subcategory"]:checked').map(function() {
                                return this.value;
                            }).get();

            var childcategory = $('input[name="childcategory"]:checked').map(function() {
                                return this.value;
                            }).get();

            var size = $('input[name="size"]:checked').map(function() {
                                return this.value;
                            }).get();

            var color = $('input[name="color"]:checked').map(function() {
                                return this.value;
                            }).get();

            var sort = $('.sort').val();

            $.ajax({

                url: "{{ route('product.search') }}",
                type: 'POST',
                data: {
                    category: category,
                    sub_category: subcategory,
                    child_category: childcategory,
                    size: size,
                    color: color,
                    order_by: sort,
                    page: page,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {

                    $('.product-data-list').html(response.html);
                    $('.pagination').html(response.message);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }


        $(document).delegate('.search-param').change(function() {
            search();
        });

    </script>
@endpush



