@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Inventory') }}
@endsection
@section('style')
    <x-niceselect.css />
@endsection
@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Create Order') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <x-msg.error />
                        <x-msg.flash />
                        <form action="{{ route('admin.product.order.new') }}" id="order_form" method="POST">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="country">{{ __('Country') }}</label>
                                    <select class="form-control" name="country" id="country">
                                        <option value="">{{ __('Select Country') }}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="state">{{ __('State') }}</label>
                                    <select class="form-control" id="state" name="state">
                                        <option value="">{{ __('Select State') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="state">{{ __('User') }}</label>
                                    <select id="user_id" name="user_id" class="form-control wide">
                                        <option value="">{{ __('Select User') }}</option>
                                        @foreach ($all_users as $user)
                                            <option value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}" data-phone="{{ $user->phone }}"
                                                data-address="{{ $user->address }}" data-country="{{ $user->country }}"
                                                data-state="{{ $user->state }}" data-city="{{ $user->city }}"
                                                data-zipcode="{{ $user->zipcode }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="f-name">{{ __('Full name') }}</label>
                                    <input class="form-control" type="text" id="name" name="name">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input class="form-control" type="email" id="email" name="email">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="address_phone">{{ __('Phone') }}</label>
                                    <input class="form-control" type="text" id="phone" name="phone" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="address">{{ __('Address') }}</label>
                                    <input class="form-control" type="text" id="address" name="address">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="address_03">{{ __('City/Town') }}</label>
                                    <input class="form-control" type="text" id="city" name="city">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="address_zip_ship_2">{{ __('Zip Code') }}</label>
                                    <input class="form-control" type="text" id="zipcode" name="zipcode" />
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="payment_gateway">{{ __('Payment Gateway') }}</label>
                                    <select name="payment_gateway" class="form-control">
                                        <option value="">{{ __('Select Shipping Method') }}</option>
                                        @php
                                            $all_gateways = ['paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'bank_transfer'];
                                        @endphp
                                        @foreach ($all_gateways as $gateway)
                                            <option value="{{ $gateway }}"
                                                @if (get_static_option('site_default_payment_gateway') == $gateway) selected @endif>
                                                {{ ucwords(str_replace('_', ' ', $gateway)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="user_shipping">{{ __('User Saved Shipping Address') }}</label>
                                    <select class="form-control" name="shipping_address_id" id="shipping_address_id">
                                        <option value="">{{ __('Select Shipping Address') }}</option>
                                        @foreach ($user_shipping_address as $shippingAddress)
                                            <option value="{{ $shippingAddress->id }}">{{ $shippingAddress->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="status">{{ __('Status') }}</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="in_progress">{{ __('In Progress') }}</option>
                                        <option value="shipped">{{ __('Shipped') }}</option>
                                        <option value="cancel">{{ __('Cancel') }}</option>
                                        <option value="complete">{{ __('Complete') }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="order_note">{{ __('Order Note') }}</label>
                                    <textarea class="form-control" id="order_note" name="order_note" rows="3"></textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="products">{{ __('Products') }}</label>
                                    <select id="all_products" class="form-control wide">
                                        @foreach ($all_products as $product)
                                            <option value="{{ $product->id }}"
                                                data-attributes="{{ $product->attributes }}">{{ $product->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6" id="product_container">
                                    <h5 class="mb-3">{{ __('Cart Products') }}</h5>
                                    <div class="table-wrap">
                                        <table class="table table-default">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Product Name') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-center col-12 my-4">
                                    <button class="btn btn-primary px-5"
                                        id="order_form_submit_btn">{{ __('Create Order') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <x-niceselect.js />
    <script>
        (function($) {
            let count = 0;
            $(document).ready(function($) {
                if ($('.nice-select').length) {
                    $('.nice-select').niceSelect();
                }

                $('#country').on('change', function() {
                    let id = $(this).val();
                    $.get('{{ route('country.info.ajax') }}', {
                        id: id
                    }).then(function(data) {
                        let options = '<option value="">{{ __('Select State') }}</option>';
                        data.states.map(function(e) {
                            options += '<option value="' + e.id + '">' + e.name +
                                '</option>';
                        });
                        $('#state').html(options);
                        $('#tax_amount').text(site_currency_symbol + Number(data.tax).toFixed(
                            0));
                        calculateTotal();
                    });
                });

                $('#state').on('change', function() {
                    let id = $(this).val();
                    $.get('{{ route('state.info.ajax') }}', {
                        id: id
                    }).then(function(data) {
                        $('#tax_amount').text(site_currency_symbol + Number(data.tax).toFixed(
                            0));
                        calculateTotal();
                    });
                });

                $('#user_id').on('change', function() {
                    let user_data = $(this).find('option:checked').data();

                    $('#name').val(user_data['name']);
                    $('#email').val(user_data['email']);
                    $('#email').val(user_data['email']);
                    $('#phone').val(user_data['phone']);
                    $('#address').val(user_data['address']);
                    $('#country').val(user_data['country']);
                    $('#city').val(user_data['city']);
                    $('#zipcode').val(user_data['zipcode']);
                });

                $('#all_products').on('change', function() {
                    let container = $('#product_container');
                    let id = $(this).find('option:checked').val();

                    count += 1;

                    $.get('{{ route('admin.product.order.product.row') }}', {
                        id: id,
                        count: count
                    }).then(function(data) {
                        $('#product_container table tbody').append(data);
                    });
                });

                $('#order_form_submit_btn').on('click', function(e) {
                    e.preventDefault();

                    let available_attributes = $('.product_row .product_name .product_attribute_list')
                        .length
                    let selected_attribute = $(
                        '.product_row .product_name .product_attribute_list input:checked').length

                    if (available_attributes != selected_attribute) {
                        toastr.error('{{ __('Select all product attributes to proceed.') }}');
                    } else {
                        $('#order_form').trigger('submit');
                    }
                })

                $(document).on('click', '.delete_order_product', function() {
                    $(this).closest('tr').remove();
                });
            });
        })(jQuery)
    </script>
@endsection
