@extends('muslin.layout')

@section('title', __('Checkout'))

@section('content')

    @php
        $carts = Cart::instance('default')->content();
        $itemsTotal = null;
        $enableTaxAmount = !\Modules\TaxModule\Services\CalculateTaxServices::isPriceEnteredWithTax();
        $shippingTaxClass = \Modules\TaxModule\Entities\TaxClassOption::where('class_id', get_static_option('shipping_tax_class'))->sum('rate');
        $tax = Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress::init();
        $uniqueProductIds = $carts
        ->pluck('id')
        ->unique()
        ->toArray();

        $country_id = old('country_id') ?? 0;
        $state_id = old('state_id') ?? 0;
        $city_id = old('city') ?? 0;

        if (empty($uniqueProductIds)) {
        $taxProducts = collect([]);
        } else {
        if (\Modules\TaxModule\Services\CalculateTaxBasedOnCustomerAddress::is_eligible()) {
        $taxProducts = $tax
            ->productIds($uniqueProductIds)
            ->customerAddress($country_id, $state_id, $city_id)
            ->generate();
        } else {
        $taxProducts = collect([]);
        }
        }

        $carts = $carts->groupBy('options.vendor_id');

        $vendors = \Modules\Vendor\Entities\Vendor::with('shippingMethod', 'shippingMethod.zone')
        ->whereIn('id', $carts->keys())
        ->get();

        $currency = getCurrency();
        $currencySymbol = currencySign();
        $currencyRate = getCurrencyRate();

    @endphp

            <!-- breadcrumb section start -->
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li>
                            <a href="{{ route('homepage') }}">Home</a>
                        </li>
                        <li>
                            <a href="#"> Profile </a>
                        </li>
                        <li> Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb section end -->

    <!-- checkout section start -->

    <section class="content-area">
        <div class="container">
            <form action="{{route('dc.checkout')}}" method="POST" id="billing_info">
                @csrf
                <div class="row">
                    <input type="hidden" name="coupon" id="coupon_code"
                           value="{{ old('coupon') ?? request()->coupon }}">
                    <input type="hidden" name="tax_amount">
                    <input type="hidden" name="ship_to_another_address" id="ship_to_another_address">
                    <input type="hidden" name="selected_shipping_option"
                           value="">
                    <input type="hidden" name="selected_payment_gateway" id="payment_gateway_val"
                           value="{{ get_static_option('site_default_payment_gateway') }}">
                    <input type="hidden" name="agree" id="term_agree" value="1">


                    @if($errors->any())
                        <div class="alert alert-danger" style="color: #f9ebec; background-color: #df1f30;">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li class="font-weight-bold">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <div class="col-lg-12 content-area__title">
                        <h1>Delivery Address</h1>
                    </div>
                    <div class="col-lg-6 col-md-12 content-area__left">
                        <div class="content-area__left__top">
                            <div class="content-area__left__form">


                                <div class="form-group">
                                    <label for="address">Full Name*</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            name="full_name"
                                            placeholder="Enter Full Name"
                                            style="width: 100%" required=""
                                    />
                                </div>


                                <div class="form-group">
                                    <label for="address">Address*</label>
                                    <input
                                            type="text"
                                            class="form-control"
                                            id="address"
                                            name="address"
                                            placeholder="Enter Address"
                                            style="width: 100%"  required=""
                                    />
                                </div>

                                <div class="form-row">

                                    <div class="form-group col">
                                        <label>Country*</label>

                                        <select class="form-control country" required="" style="width: 100%" name="country_id">
                                            <option>Select</option>
                                            @foreach ($all_country as $key => $country)

                                                <option value="{{ $key }}" {{ !empty(old('country_id')) && old('$country_id') && in_array($key, old('country_id')) ? 'selected'  : '' }} >{{ $country }}</option>

                                            @endforeach
                                            
                                        </select>
                                    </div>

                                    <div class="form-group col">
                                        <label for="zipCode">State*</label>
                                        <select class="form-control state" required="" style="width: 100%" name="state_id">
                                            <option>Select</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="zipCode">City/Town*</label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                id="city"
                                                name="city"
                                                placeholder="Enter City/Town" required=""
                                        />
                                    </div>
                                    <div class="form-group col">
                                        <label for="city">Zip Code</label>
                                        <input
                                                type="number"
                                                min="0"
                                                class="form-control"
                                                id="zipcode"
                                                name="zip_code"
                                                placeholder="Enter Zip Code"
                                        />
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col">
                                        <label for="phoneNumber">Phone Number*</label>
                                        <input
                                                type="tel"
                                                class="form-control"
                                                id="phone"
                                                name="phone"
                                                placeholder="Enter Phone Number" required=""
                                                style="width: 100%"
                                        />
                                    </div>
                                    <div class="form-group col">
                                        <label for="phoneNumber">Email Address</label>
                                        <input
                                                type="email"
                                                class="form-control"
                                                id="email"
                                                name="email"
                                                placeholder="Enter Email Address"
                                                style="width: 100%"
                                        />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber">Order Notes</label>
                                    <textarea class="form-control form--message" name="note" value="" id="message" placeholder="Type Messages" spellcheck="false"></textarea>
                                </div>
                            </div>
                        </div>


                        @if($all_user_shipping->count() > 0)
                            <div class="content-area__left__bottom">
                                <h6>Saved Addresses</h6>
                                <div class="content-area__left__bottom__items">
                                    @foreach($all_user_shipping as $key => $all_user_shipping_item)
                                        <div class="content-area__left__bottom__items__item">
                                            <div class="content-area__left__bottom__items__item__left">

                                                <div class="d-none">
                                                    <p id="shipping-address-{{$all_user_shipping_item->id}}-full_name">{{$all_user_shipping_item->name}}</p>
                                                    <p id="shipping-address-{{$all_user_shipping_item->id}}-country">{{$all_user_shipping_item->country_id}}</p>
                                                    <p id="shipping-address-{{$all_user_shipping_item->id}}-state">{{$all_user_shipping_item->state_id}}</p>
                                                    <p id="shipping-address-{{$all_user_shipping_item->id}}-email">{{$all_user_shipping_item->email}}</p>
                                                </div>

                                                <p class="font-weight-bold">Address: </p>
                                                <p id="shipping-address-{{$all_user_shipping_item->id}}-address">
                                                    {{$all_user_shipping_item->address}}
                                                </p>
                                                <div class="content-area__left__bottom__items__item__left__info">
                                                    <div class="content-area__left__bottom__items__item__left__info__single">
                                                        <p class="font-weight-bold">Zipcode:</p>
                                                        <p id="shipping-address-{{$all_user_shipping_item->id}}-zipcode">{{$all_user_shipping_item->zip_code}}</p>
                                                    </div>
                                                    <div class="content-area__left__bottom__items__item__left__info__single">
                                                        <p class="font-weight-bold">City:</p>
                                                        <p id="shipping-address-{{$all_user_shipping_item->id}}-city">{{$all_user_shipping_item->city}}</p>
                                                    </div>

                                                </div>
                                                <div class="content-area__left__bottom__items__item__left__info">

                                                    <div class="content-area__left__bottom__items__item__left__info__single">
                                                        <p class="font-weight-bold">State:</p>
                                                        <p id="shipping-address-{{$all_user_shipping_item->id}}-state">{{ !empty($all_user_shipping_item->state->name) ? $all_user_shipping_item->state->name : ''  }}</p>
                                                    </div>
                                                    <div class="content-area__left__bottom__items__item__left__info__single">
                                                        <p class="font-weight-bold">Country:</p>
                                                        <p id="shipping-address-{{$all_user_shipping_item->id}}-country">{{ !empty($all_user_shipping_item->country->name) ? $all_user_shipping_item->country->name : ''}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="content-area__left__bottom__items__item__middle">
                                                <p class="font-weight-bold">Phone Number:</p>
                                                <p id="shipping-address-{{$all_user_shipping_item->id}}-phone">{{$all_user_shipping_item->phone}}</p>
                                            </div>
                                            <div class="content-area__left__bottom__items__item__end">
                                                <div class="checkbox2">
                                                    <input
                                                            type="checkbox"
                                                            data-address="{{$all_user_shipping_item->id}}"
                                                            class="hidden-checkbox2" 
                                                    />
                                                    <label for="checkSavedAddress" class="check-box2" id="{{  $all_user_shipping_item->default_shipping_status == 1 ? 'active_address' : '' }}">
                                                        <svg
                                                                width="16"
                                                                height="11"
                                                                viewBox="0 0 16 11"
                                                                fill="none"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                        >
                                                            <path
                                                                    d="M0.5 5.5L5.5 10.5"
                                                                    stroke="#F9F9F9"
                                                                    stroke-linecap="round"
                                                            />
                                                            <path
                                                                    d="M5.5 10.5L15.5 0.5"
                                                                    stroke="#F9F9F9"
                                                                    stroke-linecap="round"
                                                            />
                                                        </svg>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="content-area__left__delivery">
                            @foreach ($carts as $key => $vendor)
                                @php
                                    $c_vendor = $vendors->find($key);
                                    $adminShippingMethod = null;
                                    $adminShopManage = null;
                                    $subtotal = null;
                                    $default_shipping_cost = null;
                                    $v_tax_total = 0;

                                    if (empty($key)) {
                                        $adminShippingMethod = \Modules\ShippingModule\Entities\AdminShippingMethod::with('zone')->get();
                                        $adminShopManage = \App\AdminShopManage::latest()->first();
                                    }
                                @endphp


                                @if (empty($key))
                                    <!-- <h6>Delivery Method</h6> -->
                                    <div class="content-area__left__delivery__items">
                                        <input type="hidden" class="shipping_cost" name="shipping_cost[admin]">
                                        <input type="hidden" class="shipping_cost_id" name="shipping_id">

                                        @foreach ($adminShippingMethod ?? [] as $method)
                                            @php
                                                //$method->cost = calculatePrice($method->cost, $shippingTaxClass, 'shipping');
                                                //if ($method->is_default) {
                                                  //  $default_shipping_cost = $method->cost;
                                                // }    
                                            @endphp
                                            <div class="content-area__left__delivery__items__item {{ $method->is_default ? 'active' : '' }}"
                                                 data-shipping-cost-id="{{ $method->id }}"
                                                 data-shipping-cost="{{ $method->cost }}"
                                                 data-shipping-percentage="{{ $shippingTaxClass }}"
                                                 style="display: none;" 

                                                 id="area_id_{{ $method->id }}"
                                                 >
                                                <div class="content-area__left__delivery__items__item__left">
                                                    <h4>{{ $method?->title }}</h4>
                                                </div>
                                                <div class="content-area__left__delivery__items____item__right">
                                                    <h4>{{ $currencySymbol }} {{ round($method->cost) }}</h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    </div>
            
                    <div class="col-lg-6 col-md-12 content-area__right">
                        <!-- add to cart popup start -->
                        <div class="cart-default">
                            <div class="el">
                                <div class="cart-default__wrap">
                                    <div class="cart-default__bottom">

                                        @php
                                            $totalPrice = 0;
                                            $all_cart_items = getLocationBasedCartContent();
                                           
                                        @endphp
                                        @if ($all_cart_items->count() > 0 && auth('web')->check())
                                            @foreach ($all_cart_items as $cartDataItem)
                                               
                                                {{-- @dd($cartDataItem, $cartDataItem->product) --}}
                                                <div class="cart-default__bottom__item">
                                                    <div class="cart-default__bottom__item__thumb">
                                                        <img
                                                                src="{{render_image($cartDataItem?->options['image'] ?? 0, render_type: 'path')}}"
                                                                alt="{{$cartDataItem->name}}"
                                                        />
                                                    </div>
                                                    <div class="cart-default__bottom__item__info">
                                                        <p>{{$cartDataItem->name}}</p>
                                                        <ul>
                                                            @if (!empty($cartDataItem?->options['color_name'] ?? null))
                                                                <li>
                                                                    Color: {{ $cartDataItem?->options['color_name'] }}
                                                                </li>
                                                            @endif

                                                            @if (!empty($cartDataItem?->options['size_name'] ?? null))
                                                                <li>
                                                                    Size: {{ $cartDataItem?->options['size_name'] ?? null }}
                                                                </li>

                                                            @endif
                                                        </ul>
                                                        @php
                                                            $currentItemPrice = ($cartDataItem->price * $cartDataItem->qty);
                                                            $totalPrice += $currentItemPrice;
                                                        @endphp

                                                        <h3>{{ $currencySymbol }} {{ number_format($currentItemPrice, 0, '.', ',') }}</h3>
                                                    </div>
                                                    <div class="cart-default__bottom__item__btns">
                                                        <div class="cart-default__bottom__item__btns__qty">
                                                            <p>{{$cartDataItem->qty}} QTY</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="cart-default__bottom__pricing">
                                            <ul>
                                                <li>
                                                    <p>Products Total</p>
                                                    <p id="products_total">
                                                        {{ $currencySymbol }} {{number_format( $totalPrice, 2, '.', ',')}}</p>
                                                </li>
                                                <li>
                                                    <p>Delivery Fee</p>
                                                    <p id="shipping_fee">0</p>
                                                </li>
                                                <li>
                                                    <p>Discount Price</p>
                                                    <p>(-) <span id="discount_fee">0</span></p>
                                                </li>
                                                <li>
                                                    <p>Grand Total</p>
                                                    <p id="grand_total">
                                                        {{ $currencySymbol }} {{number_format( $totalPrice, 2, '.', ',')}}</p>
                                                </li>
                                            </ul>
                                        </div>

                                        <div id="checkCoupon" class="cart-default__bottom__check">
                                            <h6>Use Coupon</h6>
                                        </div>
                                        <div class="cart-default__bottom__coupon">
                                            <input
                                                    type="text"
                                                    class="form-control"
                                                    id="coupon_input"
                                                    placeholder="Enter your coupon code"
                                                    style="width: 100%"
                                            />
                                            <a data-action="{{route('dc.apply-coupon')}}" id="apply_now_coupon" href="#">Apply</a>
                                        </div>

                                        @if($currency == 'USD')
                                            <div id="shipping_method" class="cart-default__bottom__check">
                                                <h6> <div id="shipping_method" class="cart-default__bottom__check">
                                                    <h6>Shipping Method</h6>
                                                </div>
                                                <div class="dhl-shipping-section mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="dhlCheckbox" name="dhlCheckbox" class="form-check-input">
                                                        <label for="dhlCheckbox" class="form-check-label">DHL Express Shipping</label>
                                                    </div>
                                                    
                                                    <div id="dhlCalculation" style="display: none;" class="mt-2">
                                                        <div class="dhl-price mt-2">
                                                            Shipping Cost: <span id="dhlShippingCost">Calculating...</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </h6>
                                            </div>
                                        @endif
                                        <!-- selected form  -->
                                        <h6 class="font-weight-bold mb-20">Select Payment Method</h6>
                                        <div class="content-area__left__top__check checkboxPayment"
                                             data-payment="online_payment">
                                            <div class="">
                                                <input
                                                        type="checkbox"
                                                        class="hidden-checkbox"
                                                />
                                                <label for="checkboxPayment" class="check-box">
                                                    <svg
                                                            width="15"
                                                            height="11"
                                                            viewBox="0 0 15 11"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                                fill-rule="evenodd"
                                                                clip-rule="evenodd"
                                                                d="M13.9331 0.846691C14.1246 0.647711 14.1185 0.331187 13.9195 0.139715C13.7205 -0.0517568 13.404 -0.0456702 13.2126 0.15331L4.85767 8.83581L0.860286 4.68168C0.668814 4.4827 0.35229 4.47661 0.15331 4.66808C-0.0456702 4.85955 -0.0517568 5.17608 0.139715 5.37506L4.49726 9.90347C4.558 9.9666 4.63133 10.0103 4.70949 10.0345C4.8843 10.0886 5.0825 10.0444 5.21804 9.90352L13.9331 0.846691Z"
                                                                fill="white"
                                                        />
                                                    </svg>
                                                </label>
                                            </div>
                                            <p>Online Payment Gateway</p>
                                        </div>
                                        <div class="content-area__left__top__check checkboxCashDelivery"
                                             data-payment="cash_on_delivery">
                                            <div>
                                                <input
                                                        type="checkbox"
                                                        class="hidden-checkbox"
                                                        checked
                                                />
                                                <label class="check-box">
                                                    <svg
                                                            width="15"
                                                            height="11"
                                                            viewBox="0 0 15 11"
                                                            fill="none"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                    >
                                                        <path
                                                                fill-rule="evenodd"
                                                                clip-rule="evenodd"
                                                                d="M13.9331 0.846691C14.1246 0.647711 14.1185 0.331187 13.9195 0.139715C13.7205 -0.0517568 13.404 -0.0456702 13.2126 0.15331L4.85767 8.83581L0.860286 4.68168C0.668814 4.4827 0.35229 4.47661 0.15331 4.66808C-0.0456702 4.85955 -0.0517568 5.17608 0.139715 5.37506L4.49726 9.90347C4.558 9.9666 4.63133 10.0103 4.70949 10.0345C4.8843 10.0886 5.0825 10.0444 5.21804 9.90352L13.9331 0.846691Z"
                                                                fill="white"
                                                        />
                                                    </svg>
                                                </label>
                                            </div>
                                            <p>Cash On Delivery</p>
                                        </div>

                                        <div class="content-area__left__top__check">
                                            <p>{{ get_static_option('delivery_message') }}</p>
                                        </div>

                                        <hr>


                                        <div class="is-agree">
                                          <input id="isAgree" type="checkbox" class="agree-checkbox"/>
                                            <label for="isAgree">I have read and agree to the Website
                                                <a href="{{ route('terms.condition') }}" target="_blank" style="text-decoration: underline; font-weight: 500;">terms & conditions</a>
                                            </label>
                                        </div>


                                    </div>
                                    <div class="cart-default__order">
                                        <button
                                                disabled
                                                type="submit"
                                                id="btnPlaceOrder"
                                                class="btn-sign"
                                        >
                                            <span> Place Order </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- add to cart end -->
                    </div>

                </div>
            </form>
        </div>
    </section>

    <!-- checkout section end -->

@endsection

@push('scripts')
    <script type="text/javascript">

        $(document).delegate(".content-area__left__top__check", "click", function () {
            let dataPayment = $(this).data("payment");
            $("#payment_gateway_val").val(dataPayment);
        });


        $(document).delegate(".is-agree", "click", function () {

        })

        $(document).on('click', '.content-area__left__bottom__items__item .check-box2', function() {

        // $(document).delegate(".content-area__left__bottom__items__item .check-box2", "click", function () {

            let parentItem = $(this).closest(".content-area__left__bottom__items__item");
            let dataAddress = parentItem.find(".hidden-checkbox2").data("address");

            $("#name").val($("#shipping-address-" + dataAddress + "-full_name").text().trim());
            // $("#country").val($("#shipping-address-" + dataAddress + "-country").text().trim());
            // $("#state").val($("#shipping-address-" + dataAddress + "-state").text().trim());
            $("#city").val($("#shipping-address-" + dataAddress + "-city").text().trim());
            $("#email").val($("#shipping-address-" + dataAddress + "-email").text().trim());
            $("#zipcode").val($("#shipping-address-" + dataAddress + "-zipcode").text().trim());
            $("#phone").val($("#shipping-address-" + dataAddress + "-phone").text().trim());
            $("#address").val($("#shipping-address-" + dataAddress + "-address").text().trim());

            var country_id = $("#shipping-address-" + dataAddress + "-country").text().trim();
            var state_id = $("#shipping-address-" + dataAddress + "-state").text().trim();

            $('.country').val(country_id).trigger('change');

            setTimeout(function() {
                $('.state').val(state_id).trigger('change');
            }, 1000);

        });


        function calculateOrderSummary() {
            
            let subTotal = Number($("#products_total").text().replace(/[^\d.-]/g, ''));
            let shippingFee = Number($("#shipping_fee").text().replace(/[^\d.-]/g, ''));
            let discountFee = Number($("#discount_fee").text().replace(/[^\d.-]/g, ''));
            let grandTotal = subTotal + shippingFee - discountFee;
            $("#grand_total").text("{{ $currencySymbol }} " + formatNumber(grandTotal, 2, '.', ','));
        }


        $(document).on("click", ".content-area__left__delivery__items__item", function () {
            let shippingCost = Number($(this).attr("data-shipping-cost"));
            let shippingCostId = $(this).attr("data-shipping-cost-id");
            $(this).parent().parent().find(".shipping_cost").val(shippingCostId);
            $(this).parent().parent().find(".shipping_cost_id").val(shippingCostId);
            $("#selected_shipping_option").val(shippingCostId);
            let modShippingText = "{{ $currencySymbol }} " + formatNumber(Math.round(shippingCost), 2, '.', ',');
            $("#shipping_fee").text(modShippingText);

            calculateOrderSummary();

        });


        //- State , Country dropdown

        $(document).on("change", ".country", function() {
            let id = $(this).val().trim();

            $.get('{{ route('country.state.info.ajax') }}', {
                id: id
            }).then(function(data) {
                $('.state').html(data);
            });
        });


        $(document).on("change", ".state", function() {
            let value = $(this).val().trim();

            if(value == 1) {
                $("#area_id_1").click();   
            }
            else { 

                console.log('sssAAS')
                $("#area_id_2").click();  
            }

        });



        $(document).on("click", "#apply_now_coupon", function() {
            let url = $(this).attr('data-action');
            let coupon = $("#coupon_input").val();
            $('.lds-ellipsis').show();
            $('#coupon_code').val(coupon);
            submitCoupon(url, coupon);
        });

        function submitCoupon(url, coupon) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    coupon: coupon,
                },
                success: function(data) {
                    if (data.type == 'success') {
                        showSuccessAlert('{{ __('Coupon applied') }}');
                        $("#discount_fee").text(formatNumber(data.coupon_amount));
                        calculateOrderSummary();
                    } else {
                        calculateOrderSummary();
                        showErrorAlert('{{ __('Coupon invalid') }}');
                    }
                },
                error: function(err) {
                    showErrorAlert('{{ __('Something went wrong') }}');
                }
            });
        }

        $('#active_address').click();

        $('#billing_info').submit(function(event) {
            $('#btnPlaceOrder').prop('disabled', true);
            $('#btnPlaceOrder').text('Processing...');
        });


        $(document).ready(function() {
            // DHL Shipping Calculation
            $('#dhlCheckbox').on('change', function() {
                $('#dhlCalculation').toggle($(this).is(':checked'));
                if ($(this).is(':checked')) {
                    calculateDHLShipping();
                } else {
                    $('#dhlShippingCost').text('-');
                    // Reset shipping cost in total calculation
                    $("#shipping_fee").text('0');
                    calculateOrderSummary();
                }
            });

            // Calculate shipping when address fields change
            $('#address, #city, #zipcode, .country, .state').on('change', function() {
                if ($('#dhlCheckbox').is(':checked')) {
                    calculateDHLShipping();
                }
            });

            function calculateDHLShipping() {
                let address = $('#address').val();
                let city = $('#city').val();
                let zipCode = $('#zipcode').val();
                let country = $('.country').val();
                let state = $('.state').val();
                
                if (!address || !city || !zipCode || !country) {
                    $('#dhlShippingCost').text('Please fill all address fields');
                    return;
                }

                $('#dhlShippingCost').text('Calculating...');

                $.ajax({
                    url: '{{ route("dhl.calculate") }}',
                    type: 'POST',
                    data: {
                        address: address,
                        city: city,
                        postal_code: zipCode,
                        country: country,
                        state: state,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#dhlShippingCost').text(response.cost);
                            // Update shipping cost in order summary
                            $("#shipping_fee").text(response.cost);
                            calculateOrderSummary();
                        } else {
                            $('#dhlShippingCost').text('Calculation failed');
                            toastr.error(response.message || 'Failed to calculate shipping cost');
                        }
                    },
                    error: function(xhr) {
                        $('#dhlShippingCost').text('Calculation failed');
                        toastr.error('Failed to calculate shipping cost');
                    }
                });
            }
        });

    </script>
@endpush