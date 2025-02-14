@extends('backend.admin-master')
@section('style')
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Checkout Page Settings') }}
@endsection
@section('content')
    @can('page-settings-checkout-page')
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-lg-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Checkout Page Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.page.settings.checkout') }}" method="POST">
                                @csrf
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Empty checkout page') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_page_no_product_text">{{ __('Checkout page no product text') }}</label>
                                                <input type="text" class="form-control" id="checkout_page_no_product_text"
                                                    name="checkout_page_no_product_text"
                                                    value="{{ get_static_option('checkout_page_no_product_text') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Login Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="returning_customer_text">{{ __('Returning customer text') }}</label>
                                                <input type="text" class="form-control" id="returning_customer_text"
                                                    name="returning_customer_text"
                                                    value="{{ get_static_option('returning_customer_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="toggle_login_text">{{ __('Toggle login text') }}</label>
                                                <input type="text" class="form-control" id="toggle_login_text"
                                                    name="toggle_login_text"
                                                    value="{{ get_static_option('toggle_login_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_username">{{ __('Checkout username') }}</label>
                                                <input type="text" class="form-control" id="checkout_username"
                                                    name="checkout_username"
                                                    value="{{ get_static_option('checkout_username') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_password">{{ __('Checkout password') }}</label>
                                                <input type="text" class="form-control" id="checkout_password"
                                                    name="checkout_password"
                                                    value="{{ get_static_option('checkout_password') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_remember_text">{{ __('Checkout remember text') }}</label>
                                                <input type="text" class="form-control" id="checkout_remember_text"
                                                    name="checkout_remember_text"
                                                    value="{{ get_static_option('checkout_remember_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_forgot_password">{{ __('Checkout forgot password') }}</label>
                                                <input type="text" class="form-control" id="checkout_forgot_password"
                                                    name="checkout_forgot_password"
                                                    value="{{ get_static_option('checkout_forgot_password') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_login_btn_text">{{ __('Checkout login button text') }}</label>
                                                <input type="text" class="form-control" id="checkout_login_btn_text"
                                                    name="checkout_login_btn_text"
                                                    value="{{ get_static_option('checkout_login_btn_text') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Coupon Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="have_coupon_text">{{ __('Have coupon text') }}</label>
                                                <input type="text" class="form-control" id="have_coupon_text"
                                                    name="have_coupon_text"
                                                    value="{{ get_static_option('have_coupon_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="enter_coupon_text">{{ __('Enter coupon text') }}</label>
                                                <input type="text" class="form-control" id="enter_coupon_text"
                                                    name="enter_coupon_text"
                                                    value="{{ get_static_option('enter_coupon_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="coupon_placeholder">{{ __('Coupon placeholder') }}</label>
                                                <input type="text" class="form-control" id="coupon_placeholder"
                                                    name="coupon_placeholder"
                                                    value="{{ get_static_option('coupon_placeholder') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="apply_coupon_btn_text">{{ __('Apply coupon button text') }}</label>
                                                <input type="text" class="form-control" id="apply_coupon_btn_text"
                                                    name="apply_coupon_btn_text"
                                                    value="{{ get_static_option('apply_coupon_btn_text') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Billing Info Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_billing_section_title">{{ __('Checkout billing title') }}</label>
                                                <input type="text" class="form-control"
                                                    id="checkout_billing_section_title" name="checkout_billing_section_title"
                                                    value="{{ get_static_option('checkout_billing_section_title') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_billing_city">{{ __('Checkout billing city') }}</label>
                                                <input type="text" class="form-control" id="checkout_billing_city"
                                                    name="checkout_billing_city"
                                                    value="{{ get_static_option('checkout_billing_city') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_billing_zipcode">{{ __('Checkout billing zipcode') }}</label>
                                                <input type="text" class="form-control" id="checkout_billing_zipcode"
                                                    name="checkout_billing_zipcode"
                                                    value="{{ get_static_option('checkout_billing_zipcode') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_billing_address">{{ __('Checkout billing address') }}</label>
                                                <input type="text" class="form-control" id="checkout_billing_address"
                                                    name="checkout_billing_address"
                                                    value="{{ get_static_option('checkout_billing_address') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_billing_email">{{ __('Checkout billing email') }}</label>
                                                <input type="text" class="form-control" id="checkout_billing_email"
                                                    name="checkout_billing_email"
                                                    value="{{ get_static_option('checkout_billing_email') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_billing_phone">{{ __('Checkout billing phone') }}</label>
                                                <input type="text" class="form-control" id="checkout_billing_phone"
                                                    name="checkout_billing_phone"
                                                    value="{{ get_static_option('checkout_billing_phone') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_order_note">{{ __('Checkout order note') }}</label>
                                                <input type="text" class="form-control" id="checkout_order_note"
                                                    name="checkout_order_note"
                                                    value="{{ get_static_option('checkout_order_note') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Create Account Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="create_account_text">{{ __('Create account text') }}</label>
                                                <input type="text" class="form-control" id="create_account_text"
                                                    name="create_account_text"
                                                    value="{{ get_static_option('create_account_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="create_account_username">{{ __('Create account username') }}</label>
                                                <input type="text" class="form-control" id="create_account_username"
                                                    name="create_account_username"
                                                    value="{{ get_static_option('create_account_username') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="create_account_password">{{ __('Create account password') }}</label>
                                                <input type="text" class="form-control" id="create_account_password"
                                                    name="create_account_password"
                                                    value="{{ get_static_option('create_account_password') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="create_account_confirmed_password">{{ __('Create account confirmed password') }}</label>
                                                <input type="text" class="form-control"
                                                    id="create_account_confirmed_password"
                                                    name="create_account_confirmed_password"
                                                    value="{{ get_static_option('create_account_confirmed_password') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Shipping Address Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="ship_to_another_text">{{ __('Ship to another text') }}</label>
                                                <input type="text" class="form-control" id="ship_to_another_text"
                                                    name="ship_to_another_text"
                                                    value="{{ get_static_option('ship_to_another_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_name">{{ __('Shipping name') }}</label>
                                                <input type="text" class="form-control" id="shipping_name"
                                                    name="shipping_name" value="{{ get_static_option('shipping_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_country">{{ __('Shipping country') }}</label>
                                                <input type="text" class="form-control" id="shipping_country"
                                                    name="shipping_country"
                                                    value="{{ get_static_option('shipping_country') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_state">{{ __('Shipping state') }}</label>
                                                <input type="text" class="form-control" id="shipping_state"
                                                    name="shipping_state" value="{{ get_static_option('shipping_state') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_city">{{ __('Shipping city') }}</label>
                                                <input type="text" class="form-control" id="shipping_city"
                                                    name="shipping_city" value="{{ get_static_option('shipping_city') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_zipcode">{{ __('Shipping zipcode') }}</label>
                                                <input type="text" class="form-control" id="shipping_zipcode"
                                                    name="shipping_zipcode"
                                                    value="{{ get_static_option('shipping_zipcode') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_address">{{ __('Shipping address') }}</label>
                                                <input type="text" class="form-control" id="shipping_address"
                                                    name="shipping_address"
                                                    value="{{ get_static_option('shipping_address') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_email">{{ __('Shipping email') }}</label>
                                                <input type="text" class="form-control" id="shipping_email"
                                                    name="shipping_email" value="{{ get_static_option('shipping_email') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_phone">{{ __('Shipping phone') }}</label>
                                                <input type="text" class="form-control" id="shipping_phone"
                                                    name="shipping_phone" value="{{ get_static_option('shipping_phone') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="chekout__items mt-4">
                                    <h6 class="chekout__items__title">{{ __('Order Summary Section') }}</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="order_summary_title">{{ __('Order summary title') }}</label>
                                                <input type="text" class="form-control" id="order_summary_title"
                                                    name="order_summary_title"
                                                    value="{{ get_static_option('order_summary_title') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="subtotal_text">{{ __('Subtotal text') }}</label>
                                                <input type="text" class="form-control" id="subtotal_text"
                                                    name="subtotal_text" value="{{ get_static_option('subtotal_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="discount_text">{{ __('Discount text') }}</label>
                                                <input type="text" class="form-control" id="discount_text"
                                                    name="discount_text" value="{{ get_static_option('discount_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="vat_text">{{ __('VAT text') }}</label>
                                                <input type="text" class="form-control" id="vat_text" name="vat_text"
                                                    value="{{ get_static_option('vat_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="shipping_text">{{ __('Shipping text') }}</label>
                                                <input type="text" class="form-control" id="shipping_text"
                                                    name="shipping_text" value="{{ get_static_option('shipping_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="total_text">{{ __('Total text') }}</label>
                                                <input type="text" class="form-control" id="total_text" name="total_text"
                                                    value="{{ get_static_option('total_text') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_place_order">{{ __('Checkout place order') }}</label>
                                                <input type="text" class="form-control" id="checkout_place_order"
                                                    name="checkout_place_order"
                                                    value="{{ get_static_option('checkout_place_order') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="checkout_return_cart">{{ __('Checkout return cart') }}</label>
                                                <input type="text" class="form-control" id="checkout_return_cart"
                                                    name="checkout_return_cart"
                                                    value="{{ get_static_option('checkout_return_cart') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_page_terms_text">{{ __('Checkout page terms text') }}</label>
                                                <input type="text" class="form-control" id="checkout_page_terms_text"
                                                    name="checkout_page_terms_text"
                                                    value="{{ get_static_option('checkout_page_terms_text') }}">
                                                <small>{{ __('Embrace the part of text you want to make a link with ') }}
                                                    <b>[lnk]</b> {{ __(' and ') }} <b>[/lnk]</b>.</small>
                                                <small>{!! __('For example: I have read and agree to the Website <b>[lnk]</b>terms & conditions<b>[/lnk]</b>.') !!}</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label
                                                    for="checkout_page_terms_link_url">{{ __('Checkout page terms link url') }}</label>
                                                <select name="checkout_page_terms_link_url" id="checkout_page_terms_link_url"
                                                    class="form-control">
                                                    @foreach ($all_pages as $page)
                                                        <option value="{{ $page->slug }}"
                                                            @if ($page->slug == get_static_option('checkout_page_terms_link_url')) selected @endif>
                                                            {{ $page->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 mt-4">
                                    <button class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup />
    @endcan
@endsection
@section('script')
    <x-media.js />
@endsection
