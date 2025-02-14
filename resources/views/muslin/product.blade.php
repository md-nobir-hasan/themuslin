    @extends('muslin.layout')

    @section('title', $product->name)

    @section('content')
       <!-- breadcrumb section start -->
        <section class="breadcrumb-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul>
                            <li>
                                <a href="{{ route('homepage') }}">Home</a>
                            </li>

                            @if (!empty($product->subCategory->name))
                                <li>
                                    <a href="{{ route('category', $product->subCategory->slug) }}">
                                        {{ $product->subCategory->name }}
                                    </a>
                                </li>
                                @endif @if (!empty($product->childCategory->name))
                                    <li>
                                        <a href="{{ route('category', $product->childCategory->slug) }}">
                                            {{ $product->childCategory->name }}
                                        </a>
                                    </li>
                                @endif
                                <li>{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- breadcrumb section end -->
{{-- @dd($product) --}}
        <!-- details gallery start -->

        <!--  Slider issue on dependent from HTML  -->

        <section class="product-gallery">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 product-gallery__img">
                        <div class="swiper-container gallery-slide product-gallery__img__top">
                            <div class="swiper-wrapper">
                                @foreach ($product->gallery_images ?? [] as $image)
                                    <div class="swiper-slide">
                                        <div class="product-gallery__img__top__wrap">
                                            <div class="product-gallery__img__top__wrap__img">
                                                {!! render_image($image, class: 'modify-img lazyloads') !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="arrows">
                                <ul>
                                    <li class="product-gallery-custom-prev hover-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18"
                                            viewBox="0 0 10 18" fill="none">
                                            <path d="M9 17L1.00001 9L9 1" stroke="white" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </li>
                                    <li class="product-gallery-custom-next hover-arrow">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18"
                                            viewBox="0 0 10 18" fill="none">
                                            <path d="M1 1L8.99999 9L1 17" stroke="white" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </li>
                                </ul>
                            </div>

                            <div class="favorite add_to_wishlist" data-id="{{ $product->id }}">
                                <a href="#1">
                                    <div class="fav">
                                        <div class="checkbox" id="checkboxContainer">
                                            <input type="checkbox" id="checkHomeAddress" class="hidden-checkbox-fav" />
                                            <label for="checkHomeAddress" class="check-fav">
                                                <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.99994 17.794C9.71551 17.7939 9.4408 17.6905 9.22694 17.503C8.41894 16.797 7.63994 16.133 6.95294 15.547C5.17502 14.1294 3.5143 12.5706 1.98694 10.886C0.752936 9.55727 0.0463857 7.82379 -5.99354e-05 6.01101C-0.0269747 4.43873 0.539687 2.91407 1.58694 1.74101C2.09506 1.18749 2.71353 0.746585 3.40248 0.446718C4.09144 0.146852 4.83558 -0.00531942 5.58694 1.33974e-05C6.7273 -0.00498111 7.83552 0.37759 8.72994 1.08501C9.21453 1.46398 9.64223 1.91053 9.99994 2.41101C10.3579 1.91011 10.7859 1.46322 11.2709 1.08401C12.1652 0.377163 13.273 -0.00503875 14.4129 1.33974e-05C15.1643 -0.00531942 15.9084 0.146852 16.5974 0.446718C17.2863 0.746585 17.9048 1.18749 18.4129 1.74101C19.4602 2.91407 20.0269 4.43873 19.9999 6.01101C19.9542 7.82259 19.2491 9.55524 18.0169 10.884C16.4899 12.5683 14.8295 14.1267 13.0519 15.544C12.3639 16.131 11.5829 16.796 10.7739 17.504C10.5596 17.6913 10.2846 17.7944 9.99994 17.794ZM5.58694 1.17201C4.99754 1.16733 4.41369 1.2862 3.87304 1.52096C3.33239 1.75572 2.84692 2.10116 2.44794 2.53501C1.60051 3.49199 1.14458 4.73303 1.17094 6.01101C1.21584 7.54995 1.82602 9.01842 2.88494 10.136C4.37036 11.7671 5.98311 13.2775 7.70794 14.653C8.39794 15.241 9.17994 15.907 9.99394 16.619C10.8129 15.906 11.5939 15.238 12.2869 14.65C14.0117 13.2748 15.6245 11.7648 17.1099 10.134C18.1688 9.01637 18.7789 7.54792 18.8239 6.00901C18.8503 4.7315 18.3948 3.49088 17.5479 2.53401C17.149 2.09998 16.6636 1.75435 16.123 1.51942C15.5823 1.28448 14.9984 1.16546 14.4089 1.17001C13.5285 1.16709 12.6731 1.46326 11.9829 2.01001C11.4405 2.44433 10.9822 2.97443 10.6309 3.57401C10.5652 3.6838 10.4722 3.77467 10.3609 3.83778C10.2496 3.90088 10.1239 3.93405 9.99594 3.93405C9.868 3.93405 9.74225 3.90088 9.63096 3.83778C9.51966 3.77467 9.42663 3.6838 9.36094 3.57401C9.01083 2.97506 8.55396 2.44532 8.01294 2.01101C7.32269 1.46455 6.46731 1.16872 5.58694 1.17201Z"
                                                        fill="black" />
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="swiper-container gallery-thumb product-gallery__img__bottom">
                            <div class="swiper-wrapper">
                                @foreach ($product->gallery_images ?? [] as $image)
                                    <div class="swiper-slide">
                                        <div class="product-gallery__img__bottom__wrap">
                                            <div class="product-gallery__img__bottom__wrap__img">
                                                {!! render_image($image, class: 'modify-img lazyloads') !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <!-- ------------ -->

                        <!-- ------------ -->
                    </div>
                    <form action="{{ route('product.add-to-cart') }}" class="col-lg-5 offset-lg-1 product-gallery__details"
                        id="cart_form" method="post">
                        @csrf

                        <h1>{{ $product->name }}</h1>
                        <p class="sku">SKU:
                            <span>{{ !empty($product->inventory->sku) ? $product->inventory->sku : '' }}</span></p>

                        @if (!empty($product->campaign_product))
                            <h3 class="price"><span>{{ currencySign() }}</span> {{ number_format($product->campaign_product->campaign_price) }}
                            @else
                                <h3 class="price"><span>{{ currencySign() }}</span> {{ number_format($product->sale_price) }}
                            @endif
                            @if ($product->price > $product->sale_price)
                                <del>{{ number_format($product->price) }}</del>
                            @endif
                        </h3>
                      

                       

                        @if ($productColors->count() > 0 && current(current($productColors)))
                            <p class="color">COLOR:</p>
                            <div class="product-gallery__details__colors">
                                <ul>
                                    @foreach ($productColors as $product_color)
                                        @if (!empty($product_color))
                                            <li>
                                                <input class="color-input" data-color="{{ optional($product_color)->id }}"
                                                    type="checkbox" name="product_color"
                                                    data-value="{{ optional($product_color)->name }}"
                                                    value="{{ optional($product_color)->id }}" />
                                                <svg data-color-id="{{ optional($product_color)->id }}"
                                                    class="svg-color-item" xmlns="http://www.w3.org/2000/svg" width="48"
                                                    height="48" viewBox="0 0 48 48" fill="none">
                                                    <circle cx="24" cy="24" r="20"
                                                        fill="{{ optional($product_color)->color_code }}" />

                                                    <!-- Hidden Circle initially -->
                                                    <circle data-color-id="{{ optional($product_color)->id }}"
                                                        class="svg-color-item-circle" cx="23" cy="23" r="23.5"
                                                        transform="matrix(1 0 0 -1 1 47)"
                                                        stroke="{{ optional($product_color)->color_code }}" />
                                                </svg>
                                                <div class="circle"></div>
                                                <p>{{ optional($product_color)->name }}</p>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                        @if ($productSizes->count() > 0 && !empty(current(current($productSizes))))
                            <div class="product-gallery__details__sizes">
                                <p class="sizes">SIZE:</p>
                                <div class="product-gallery__details__sizes__btns">
                                    <div class="size-box">
                                        @foreach ($productSizes as $product_size)
                                            @if (!empty($product_size))
                                                <input class="size-item-input d-none"
                                                    data-size="{{ optional($product_size)->slug }}" type="checkbox"
                                                    name="size" value="{{ optional($product_size)->id }}" />
                                                <label class="size-item" data-size-id="{{ optional($product_size)->id }}"
                                                    data-value="{{ optional($product_size)->name }}"
                                                    for="{{ optional($product_size)->name }}">{{ optional($product_size)->name }}</label>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($product->inventory->stock_count > 0)
                            <div class="product-gallery__details__qty">
                                <span>Available: <span style="color: rgba(32, 211, 12, 0.84)"> In Stock</span></span>
                                <p class="quantity">QUANTITY:</p>
                                <div class="product-gallery__details__qty__btns">
                                    <span class="minus" onclick="setInput('minus')"><svg width="10" height="2"
                                            viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="10" height="2" fill="black" />
                                        </svg>
                                    </span>
                                    <input type="hidden" name="selected_color" id="selected_color" value="">
                                    <input type="hidden" name="selected_size" id="selected_size" value="">


                                    <input class="d-none" data-json="" id="product_json_data">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" id="quantity_id" value="1">
                                    <input type="hidden" name="product_variant" id="product_variant"
                                        data-variant="{{ $productSizes->count() > 0 || $productColors->count() > 0 ? 1 : 0 }}"
                                        value="">
                                    <input type="hidden" name="pid_id" id="pid_id" value="">
                                    <input type="hidden" name="attributes[price]" id="attributes"
                                        value="{{ $product->price > $product->sale_price ? $product->sale_price : $product->price }}">
                                    <p id="qty_text">1</p>
                                    <span class="plus" onclick="setInput('plus')"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                            viewBox="0 0 10 10" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6 4V0H4L4 4H0V6H4L4 10H6V6H10V4H6Z" fill="black" />
                                        </svg></span>
                                </div>
                            </div>
                            <div class="product-gallery__details__buy">
                                <a id="buyNow" href="#" data-action="{{ route('product.add-to-cart') }}"
                                    class="buy-btn"><span>Buy Now</span></a>
                                <a id="addToCart" href="#" data-action="{{ route('product.add-to-cart') }}"
                                    class="add-cart">
                                    <span>Add to Cart</span>
                                </a>
                            </div>
                        @else
                            <span
                                style="color: #f65e50; background-color: #ffe0e0; padding:5px; border-radius: 5px; font-weight: bold; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                Out of stock
                            </span>
                            <hr>
                        @endif

                        @if (!empty($product->description) && $product->description)
                            <div class="widgets description">
                                <div id="accordion">
                                    <!-- Categories -->
                                    <div class="card">
                                        <div class="card-header" id="headingCategories">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse"
                                                    data-target="#collapseCategories" aria-expanded="true"
                                                    aria-controls="collapseCategories" type="button">
                                                    Product Description
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="10"
                                                        viewBox="0 0 17 10" fill="none">
                                                        <path d="M16.0957 1L8.54788 8.99999L1.00005 1" stroke="black"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseCategories" class="collapse show"
                                            aria-labelledby="headingCategories" data-parent="#accordion">
                                            <div class="card-body">
                                                {!! $product->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </section>
        <!-- details gallery end -->



        @if ($related_products->count() > 0 && !empty(current(current($related_products))))
            <!-- best selling start -->
            <section class="best-area pb-60">
                <h2 class="sr-only">Related Products</h2>
                <div class="container">
                    <div class="subtitle">
                        <p style="font-size:xx-large" class="section-heading">Related Products</p>
                        <div class="arrows">
                            <ul>
                                <li class="custom-prev hover-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18"
                                        viewBox="0 0 10 18" fill="none">
                                        <path d="M9 17L1.00001 9L9 1" stroke="black" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </li>
                                <li class="custom-next hover-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18"
                                        viewBox="0 0 10 18" fill="none">
                                        <path d="M1 1L8.99999 9L1 17" stroke="black" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="swiper best-selling">
                        <div class="swiper-wrapper">
                            @foreach ($related_products as $rel_product)
                                <div class="swiper-slide">
                                    <div class="single-item">
                                        <a href="{{ route('product.details', $rel_product->slug) }}">
                                            <div class="single-item__img">
                                                {!! render_image($rel_product->image, class: 'modify-img lazyloads') !!}
                                            </div>
                                            <div class="single-item__content">
                                                <span>{{ !empty($rel_product->category->name) ? $rel_product->category->name : '' }}</span>
                                                <h6>{{ $rel_product->name }}</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <!-- best selling end -->

        @endif
    @endsection

    @push('scripts')
        <script type="text/javascript">
            var attribute_store = JSON.parse('{!! json_encode($product_inventory_set) !!}');
            var additional_info_store = JSON.parse('{!! json_encode($additional_info_store) !!}');

            var orderData = {};
            var selectedSize = 0;
            var selectedColor = 0;

            function getSelectionHash(selected_options) {
                return MD5(selected_options);
            }

            function getAttributesForCart(selectedOrderData) {

                let selected_options = selectedOrderData;
                let cart_selected_options = selected_options;
                let hashed_key = getSelectionHash(selected_options);

                // if selected attribute set is available
                if (additional_info_store[hashed_key]) {
                    return additional_info_store[hashed_key]['pid_id'];
                }

                // if selected attribute set is not available
                if (Object.keys(selected_options).length) {
                    // showErrorAlert('{{ __('Choose color and size') }}');
                    return 0;
                }

                return '';
            }


            $('input[type="checkbox"].color-input').click(function() {
                let inputColor = $(this).attr('data-color');

                if (inputColor.length > 0) {
                    $('svg.svg-color-item').removeClass('active');
                    $('circle.svg-color-item-circle').removeClass('active');
                    $('input.color-input').removeAttr('checked', true);
                    $('svg.svg-color-item[data-color-id="' + inputColor + '"]').addClass('active');
                    $('circle.svg-color-item-circle[data-color-id="' + inputColor + '"]').addClass('active');
                    $(this).attr('checked', true);
                    orderData["Color"] = $(this).attr('data-value');
                    selectedColor = inputColor;


                    let productJsonData = $('#product_json_data');
                    productJsonData.attr('data-json', JSON.stringify(orderData));

                    let orderJsonData = productJsonData.attr('data-json').length > 0 ? $('#product_json_data').attr(
                        'data-json') : {};

                    let pid_id = getAttributesForCart(orderJsonData);
                    $("#pid_id").val(pid_id);
                    $("#product_variant").val(pid_id);


                    $("#selected_color").val(inputColor);

                }
            });


            $('label.size-item').click(function() {
                let inputSize = $(this).attr('data-size-id');
                $('input.size-item-input').removeAttr('checked', true);
                $('label.size-item').removeClass('active');
                $(this).addClass('active');
                $('input[data-size="' + inputSize + '"]').attr('checked', true);
                orderData["Size"] = $(this).attr('data-value');
                selectedSize = inputSize;

                let productJsonData = $('#product_json_data');
                productJsonData.attr('data-json', JSON.stringify(orderData));

                let orderJsonData = productJsonData.attr('data-json').length > 0 ? $('#product_json_data').attr(
                    'data-json') : {};

                let pid_id = getAttributesForCart(orderJsonData);
                $("#pid_id").val(pid_id);
                $("#product_variant").val(pid_id);


                $("#selected_size").val(inputSize);
            });


            function setInput(param = null) {


                let quantityVal = $('#qty_text').text();
                if (param == 'plus') {
                    quantityVal = parseInt(quantityVal) + 1;
                } else if (param == 'minus') {
                    if (quantityVal > 1) {
                        quantityVal = parseInt(quantityVal) - 1;
                    }
                } else {
                    quantityVal = parseInt(quantityVal)
                }
                $('#quantity_id').val(quantityVal);

            }

            $(document).delegate('#addToCart, #buyNow', 'click', function(event, jqXHR, settings) {
                let clickedId = $(this).attr('id');

                let form = $(this).closest('form'),
                    form_id = form.attr('id');
                let actionUrl = $(this).attr('data-action');

                let orderJsonData = $('#product_json_data').attr('data-json').length > 0 ? $('#product_json_data').attr(
                    'data-json') : {};
                let variantData = $("#product_variant").attr('data-variant');
                let pid_id = getAttributesForCart(orderJsonData);

                // console.log("====", variantData, pid_id);

                if ((variantData == 1 && pid_id > 0) || (variantData == 0)) {
                    $.ajax({

                        url: actionUrl,
                        type: 'post',
                        data: new FormData(document.getElementById(form_id)),
                        processData: false,
                        contentType: false,
                        success: function(data) {

                            if (data.type == 'success') {
                                form[0].reset();
                                showSuccessAlert(data.msg);

                                if (clickedId == 'buyNow') {
                                    window.location.href = "{{ route('frontend.cart-checkout') }}";
                                } else {
                                    location.reload();
                                }

                            } else {

                                if ('quantity_msg' in data) {
                                    showErrorAlert(data.quantity_msg);
                                } else if ('error_msg' in data) {
                                    showErrorAlert(data.error_msg);
                                } else {
                                    showErrorAlert(data.msg);
                                }
                            }
                        },
                        error: function(jqXHR, exception) {
                            showErrorAlert('Sorry. Server Error!');
                        }
                    });
                } else {
                    showErrorAlert('Please select color & size');
                }



                return false;
            });

            $(document).delegate('.add_to_wishlist', 'click', function(e) {

                e.preventDefault();
                let product_id = {{ $product->id }};
                let quantity = 1;
                let pid_id = '';

                $.ajax({
                    url: '{{ route('product.add-to-wish') }}',
                    type: 'POST',
                    data: {
                        product_id: product_id,
                        quantity: quantity,
                        pid_id: pid_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        showSuccessAlert(data.msg);
                        location.reload();
                    },
                    erorr: function(err) {
                        showErrorAlert('{{ __('An error occurred') }}');
                    }
                });

            });

           
        </script>
    @endpush
