@extends('muslin.layout')
@section('title', $category ? $category->name : '')

@push('styles')

<style>
    .pagination-links {
        margin-left: auto;
        margin-right: 0;
        text-align: left;
        width: auto;
    }
    .product-pagination .active, .product-pagination .disabled {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: color 0.3s;
        border: 1px solid #ddd;
        margin: 0 4px;
    }
    .product-pagination .active {
        background-color: #e4bd99;
    }

    @media (min-width: 768px) {
        .pagination-links {
            margin-left: 80%;
        }
    }

    @media (max-width: 767px) {
        .pagination-links {
            margin-left: 0;
            margin-right: 0;
            text-align: center;
        }
    }
</style>

@endpush

@section('content')


    <?php
    $userAgent = request()->header('User-Agent');
    $isMobile = is_numeric(strpos(strtolower($userAgent), 'mobile'));
    
    $isTablet = false;
    if (strpos(strtolower($userAgent), 'tablet') !== false || strpos(strtolower($userAgent), 'ipad') !== false) {
        $isTablet = true;
    }
    
    if ($isMobile) {
        $image = !empty($category->mobileBannerImage) ? $category->mobileBannerImage : null;
    } else {
        $image = !empty($category->bannerImage) ? $category->bannerImage : null;
    }
    ?>

    <!-- product inner banner start -->
    <section class="product-inner-banner">
        <div class="product-inner-banner__wrap">
            {!! render_image($image, class: 'modify-img lazyloads', defaultImage: true, imageType: 'banner') !!}
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 product-inner-banner__title">
                    <h1>{{ $category ? $category->name : '' }}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- product inner banner end -->



    <!-- Product Listing Area Start -->
    <section class="product-listing">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 widgets">
                    <div id="accordion">

                        @if (!$isMobile || $isTablet)

                            <!-- Categories -->
                            <div class="card">
                                <div class="card-header" id="headingCategories">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseCategories" aria-expanded="true"
                                            aria-controls="collapseCategories"> Categories <img
                                                src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                alt="category" />
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseCategories" class="collapse show" aria-labelledby="headingCategories"
                                    data-parent="#accordion">
                                    <div class="card-body">
                                        <ul>
                                            @foreach ($parent_categories as $value)
                                                <li>
                                                    <input type="radio" name="category" class="search-param"
                                                        value="{{ $value->slug }}"
                                                        {{ $value->slug == $category_slug ? 'checked' : '' }} />
                                                    <label for="sharee">{{ $value->name }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>


                            <!-- Sub-Categories -->
                            <div class="card subcategory-card"
                                @if (empty($sub_categories)) style="display: none;" @endif>
                                <div class="card-header" id="headingSubCategories">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseSubCategories" aria-expanded="false"
                                            aria-controls="collapseSubCategories"> Sub-Categories <img
                                                src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                alt="subcategory" />
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseSubCategories" class="collapse show" aria-labelledby="headingSubCategories"
                                    data-parent="#accordion">
                                    <div class="card-body">

                                        <ul class="subcategory-ul">
                                            @foreach ($sub_categories as $value)
                                                <li>
                                                    <input type="radio" name="subcategory" class="search-param"
                                                        value="{{ $value->slug }}"
                                                        {{ $value->slug == $subcategory_slug ? 'checked' : '' }} />
                                                    <label>{{ $value->name }}</label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Child-Categories -->
                            <div class="card childcategory-card"
                                @if (empty($child_categories)) style="display: none;" @endif>
                                <div class="card-header" id="headingChildCategories">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapseChildCategories" aria-expanded="false"
                                            aria-controls="collapseChildCategories"> Child Categories <img
                                                src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                alt="subcategory" />
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseChildCategories" class="collapse show"
                                    aria-labelledby="headingChildCategories" data-parent="#accordion">
                                    <div class="card-body">

                                        <ul class="childcategory-ul">
                                            @if (!empty($child_categories))
                                                @foreach ($child_categories as $value)
                                                    <li>
                                                        <input type="checkbox" name="childcategory"
                                                            value="{{ $value->slug }}"
                                                            {{ $value->slug == $childcategory_slug ? 'checked' : '' }} />
                                                        <label>{{ $value->name }}</label>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            @if ($colors->count() > 0)
                                <!-- Color -->
                                <div class="card">
                                    <div class="card-header" id="headingColor">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseColor"
                                                aria-expanded="false" aria-controls="collapseColor">
                                                Color
                                                <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                    alt="color" />
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseColor" class="collapse show" aria-labelledby="headingColor"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                @foreach ($colors as $key => $value)
                                                    <li>
                                                        <input type="checkbox" name="color"
                                                            value="{{ $value->productColor->name }}"
                                                            class="search-param" />
                                                        <label>{{ $value->productColor->name }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($sizes->count() > 0)

                                <!-- Size -->
                                <div class="card">
                                    <div class="card-header" id="headingSize">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSize"
                                                aria-expanded="false" aria-controls="collapseSize">
                                                Size
                                                <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                                    alt="size" />
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseSize" class="collapse show" aria-labelledby="headingSize"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <ul>
                                                @foreach ($sizes as $key => $value)
                                                    <li>
                                                        <input type="checkbox" name="size"
                                                            value="{{ $value->productSize->name }}"
                                                            class="search-param" />
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
                            <p class="pagination pagination-text">
                                @if ($products->total() > 1)
                                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                    {{ $products->total() }} products
                                @endif
                            </p>
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
                            @foreach ($products as $product)
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
                                                <span>{{ !empty($product->category->name) ? $product->category->name : '' }}</span>
                                                <h6>{{ $product->name }} </h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            @if (empty($products->count() > 0))
                                <div class="col-lg-4 col-md-6 col-sm-6 col-6">
                                    <p>No product found</p>
                                </div>
                            @endif
                        </div>

                        <!-- Pagination Links -->
                        <div class="product-pagination">
                            @if ($products->hasPages())
                                <nav aria-label="Page navigation" class="pagination-btn">
                                    <ul class="pagination">
                                        {{-- Previous Page Link --}}
                                        @if ($products->onFirstPage())
                                            <li class="disabled"><span>&laquo;</span></li>
                                        @else
                                            <li><a class="ajax-search" href="{{ $products->previousPageUrl() }}" rel="prev" data-id="<?= $products->currentPage() - 1; ?>">&laquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                            @if ($page == $products->currentPage())
                                                <li class="active"><span>{{ $page }}</span></li>
                                            @else
                                                <li><a class="ajax-search" href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($products->hasMorePages())
                                            <li><a class="ajax-search" href="{{ $products->nextPageUrl() }}" rel="next" data-id="<?= $products->currentPage() + 1; ?>" >&raquo;</a></li>
                                        @else
                                            <li class="disabled"><span>&raquo;</span></li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @if ($isMobile)

        <!-- this widget will be show on the mobile device -->
        <div class="widgets global-widget">
            <div class="global-widget__top">
                <p>Filter</p>
                <svg id="close" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                    viewBox="0 0 30 30" fill="none">
                    <path
                        d="M15 30C23.2843 30 30 23.2843 30 15C30 6.71573 23.2843 0 15 0C6.71573 0 0 6.71573 0 15C0 23.2843 6.71573 30 15 30Z"
                        fill="#F9F9F9" />
                    <path d="M10 10L20 20" stroke="#221F1F" stroke-linecap="round" />
                    <path d="M10 20L20 10" stroke="#221F1F" stroke-linecap="round" />
                </svg>
            </div>
            <div id="accordion">

                <!-- Categories -->
                <div class="card">
                    <div class="card-header" id="headingCategories">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseCategories"
                                aria-expanded="true" aria-controls="collapseCategories">
                                Categories
                                <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="category" />
                            </button>
                        </h5>
                    </div>
                    <div id="collapseCategories" class="collapse show" aria-labelledby="headingCategories"
                        data-parent="#accordion">
                        <div class="card-body">

                            @if (!empty($parent_categories))
                                <ul>
                                    @foreach ($parent_categories as $value)
                                        <li>
                                            <input type="radio" name="category" class="search-param"
                                                value="{{ $value->slug }}" {{ $value->slug == $category_slug ? 'checked' : '' }} />
                                            <label for="sharee">{{ $value->name }}</label>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Sub-Categories -->
                <div class="card subcategory-card" @if (empty($sub_categories)) style="display: none;" @endif>
                    <div class="card-header" id="headingSubCategories">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSubCategories"
                                aria-expanded="false" aria-controls="collapseSubCategories">
                                Sub-Categories
                                <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="subcategory" />
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSubCategories" class="collapse show" aria-labelledby="headingSubCategories"
                        data-parent="#accordion">
                        <div class="card-body">
                            <ul class="subcategory-ul">
                                @foreach ($sub_categories as $value)
                                    <li>
                                        <input type="radio" name="subcategory" class="search-param"
                                            value="{{ $value->slug }}"
                                            {{ $value->slug == $subcategory_slug ? 'checked' : '' }} />
                                        <label for="sharee">{{ $value->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>


                <!-- Child-Categories -->
                <div class="card childcategory-card" @if (empty($child_categories)) style="display: none;" @endif>

                    <div class="card-header" id="headingChildCategories">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseChildCategories"
                                aria-expanded="false" aria-controls="collapseChildCategories">
                                Child Categories
                                <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                    alt="childcategory" />
                            </button>
                        </h5>
                    </div>
                    <div id="collapseChildCategories" class="collapse show" aria-labelledby="headingChildCategories"
                        data-parent="#accordion">
                        <div class="card-body">
                            <ul class="childcategory-ul">
                                @foreach ($child_categories as $value)
                                    <li>
                                        <input type="checkbox" name="childcategory" class="search-param"
                                            value="{{ $value->slug }}"
                                            {{ $value->slug == $childcategory_slug ? 'checked' : '' }} />
                                        <label for="sharee">{{ $value->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($colors->count() > 0)

                    <!-- Color -->
                    <div class="card">
                        <div class="card-header" id="headingColor">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseColor"
                                    aria-expanded="false" aria-controls="collapseColor"> Color <img
                                        src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}" alt="color" />
                                </button>
                            </h5>
                        </div>
                        <div id="collapseColor" class="collapse show" aria-labelledby="headingColor"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="card-body">
                                    <ul>
                                        @foreach ($colors as $key => $value)
                                            <li>
                                                <input type="checkbox" name="color"
                                                    value="{{ $value->productColor->name }}" class="search-param" />
                                                <label for="red">{{ $value->productColor->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif

                @if ($sizes->count() > 0)

                    <!-- Size -->
                    <div class="card">
                        <div class="card-header" id="headingSize">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSize"
                                    aria-expanded="false" aria-controls="collapseSize">
                                    Size
                                    <img src="{{ asset('assets/muslin/images/static/down-arrow.svg') }}"
                                        alt="size" />
                                </button>
                            </h5>
                        </div>
                        <div id="collapseSize" class="collapse show" aria-labelledby="headingSize"
                            data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                    @foreach ($sizes as $key => $value)
                                        <li>
                                            <input type="checkbox" name="size"
                                                value="{{ $value->productSize->name }}" class="search-param" />
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
        $('input[type="radio"][name="category"]').change(function() {
            var checkedValues = $('input[name="category"]:checked').map(function() {
                return this.value;
            }).get();

            if (checkedValues.length > 0) {
                $.ajax({
                    url: "{{ route('category.info') }}",
                    type: 'POST',
                    data: {
                        ids: checkedValues,
                        type: 'category',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        if (response.type == 'success' && response.html.length > 10) {

                            $('.subcategory-ul').html(response.html);
                            $('.subcategory-card').show();

                            $('.childcategory-ul').html('');
                            $('.childcategory-card').hide();
                        } else {
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


        $(document).on('change', 'input[type="radio"][name="subcategory"]', function() {
            var checkedValues = $('input[name="subcategory"]:checked').map(function() {
                return this.value;
            }).get();

            if (checkedValues.length > 0) {

                $.ajax({
                    url: "{{ route('category.info') }}",
                    type: 'POST',
                    data: {
                        ids: checkedValues,
                        type: 'subcategory',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        if (response.type == 'success' && response.html.length > 10) {
                            $('.childcategory-ul').html(response.html);
                            $('.childcategory-card').show();
                        } else {
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
                    $('.pagination-text').html(response.message);
                    $('.pagination-btn').html(response.pagination);
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
