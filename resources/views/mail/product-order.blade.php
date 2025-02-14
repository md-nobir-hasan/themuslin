<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <title>{{get_static_option('site_'.get_default_language().'_title')}} {{__('Mail')}}</title>

    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .mail-container {
            max-width: 650px;
            margin: 0 auto;
            text-align: center;
        }

        .mail-container .logo-wrapper {
            background-color: {{get_static_option('site_main_color_two')}};
            padding: 20px 0 20px;
        }
        table {
            margin: 0 auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #f2f2f2;
            color: #333;
            text-transform: capitalize;
        }
        footer {
            margin: 20px 0;
            font-size: 14px;
        }
        .product-thumbnail img {
            max-width: 150px;
        }
        .product-title {
            text-align: left;
            font-weight: 500;
        }
        .billing-wrap,
        .shipping-wrap{
            text-align: left;
        }
        .subtitle {
            font-size: 20px;
            line-height: 30px;
            font-weight: 600;
        }
        .billing-wrap ul,
        .shipping-wrap ul{
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .billing-wrap ul li,
        .shipping-wrap ul li{
            margin: 5px 0;
        }
        .billing-wrap ul li strong,
        .shipping-wrap ul li strong{
            min-width: 100px;
            display: inline-block;
            position: relative;
        }

        .billing-wrap ul li strong:after ,
        .shipping-wrap ul li strong:after {
            position: absolute;
            right: 0;
            top: 0;
            content: ":";
        }
        .order-summery{
            margin-top: 40px;
            background-color: #f6f8ff;
            padding: 30px;
            text-align: left;
        }
        .order-summery table{
            text-align: left;
        }
        .extra-data {
            text-align: left;
            margin-bottom: 40px;
        }

        .extra-data ul {
            padding: 0;
            list-style: none;
            margin: 20px 0;
        }

        .extra-data ul li {
            margin-top: 14px;
        }
        .description h4 {
            font-size: 24px;
            margin-bottom: 5px;
            line-height: 34px;
        }

        .description p {
            margin: 0;
            margin-bottom: 40px;
            color: #f36d2e;
        }
        .brief-wrapper p {
            margin: 0;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 500;
            line-height: 26px;
        }

        .brief-wrapper {
            background-color: #f6f8ff;
            padding: 30px;
            text-align: left;
            margin-bottom: 40px;
        }
        .customer-data .subtitle {
            margin-top: 0;
        }
        .customer-data {
            display: flex;
            justify-content: space-between;
            background-color: #f6f8ff;
            padding: 30px;
            margin-bottom: 50px;
        }
        .product-info-wrap {
            text-align: left;
            padding: 20px;
            padding-top: 0;
        }

        .product-info-wrap h4 {
            font-size: 18px;
            line-height: 20px;
            margin-bottom: 20px;
        }

        .product-info-wrap .pdetails {
            font-size: 14px;
            display: block;
            line-height: 20px;
            margin-bottom: 2px;
        }
        .product-info-wrap h4 a {
            color: #333;
        }
        .order-summery h2 {
            margin: 0;
        }
         .logo-wrapper img{
            max-width: 200px;
        }
    </style>
</head>
<body>
<div class="mail-container">
    <div class="logo-wrapper">
        <a href="{{url('/')}}">
            {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
        </a>
    </div>
    @if($type == 'customer')
        <div class="description">
            <h4>{{__('Your Order has been Placed')}}</h4>
            <p>{{__('Order')}} #{{$data->id}}</p>
        </div>
        <div class="brief-wrapper">
            <p> {{__('Hey')}} {{$data->billing_name}}</p>
            <p>{{__('Your order')}} #{{$data->id}} {{__('has been placed on')}} {{date_format($data->created_at,'d F Y H:m:s')}} {{__('via')}} {{ucwords(str_replace('_',' ',$data->payment_gateway))}}. {{__('You will be updated with another email after your item(s) has been shipped.')}}</p>
        </div>
    @else
        <div class="description">
            <h4>{{__('Your have a new order')}}</h4>
            <p>{{__('Order')}} #{{$data->id}}</p>
        </div>
        <div class="brief-wrapper">
            <p> {{__('Hey')}} </p>
            <p>{{__('Your have an order')}} #{{$data->id}} {{$data->billing_name}} {{__('has been placed it on')}} {{date_format($data->created_at,'d F Y H:m:s')}} {{__('via')}} {{ucwords(str_replace('_',' ',$data->payment_gateway))}}.</p>
        </div>
    @endif
    <div class="customer-data">
        <div class="billing-wrap">
            <h2 class="subtitle">{{__('Billing Details')}}</h2>
            <ul>
                <li><strong>{{__('Name')}}</strong> {{$data->billing_name}}</li>
                <li><strong>{{__('Email')}}</strong> {{$data->billing_email}}</li>
                <li><strong>{{__('Phone')}}</strong> {{$data->billing_phone}}</li>
                <li><strong>{{__('Country')}}</strong> {{$data->billing_country}}</li>
                <li><strong>{{__('Address')}}</strong> {{$data->billing_street_address}}</li>
                <li><strong>{{__('Town')}}</strong> {{$data->billing_town}}</li>
                <li><strong>{{__('District')}}</strong>  {{$data->billing_district}}</li>
            </ul>
        </div>
        @if($data->different_shipping_address == 'yes')
            <div class="shipping-wrap">
                <h2 class="subtitle">{{__('Shipping Details')}}</h2>
                <ul>
                    <li><strong>{{__('Name')}}</strong> {{$data->shipping_name}}</li>
                    <li><strong>{{__('Email')}}</strong> {{$data->shipping_email}}</li>
                    <li><strong>{{__('Phone')}}</strong> {{$data->shipping_phone}}</li>
                    <li><strong>{{__('Country')}}</strong> {{$data->shipping_country}}</li>
                    <li><strong>{{__('Address')}}</strong> {{$data->shipping_street_address}}</li>
                    <li><strong>{{__('Town')}}</strong> {{$data->shipping_town}}</li>
                    <li><strong>{{__('District')}}</strong> {{$data->shipping_district}}</li>
                </ul>
            </div>
        @endif
    </div>
    <table>
        <thead>
        <th>{{__('thumbnail')}}</th>
        <th>{{__('Product Info')}}</th>
        </thead>
        <tbody>
        @php $cart_items = unserialize($data->cart_items); @endphp
        @foreach($cart_items as $item)
            @php $product_info = \App\Products::find($item['id']);@endphp
            <tr>
                <td>
                    <div class="product-thumbnail">
                        {!! render_image_markup_by_attachment_id($product_info->image,'','thumb') !!}
                    </div>
                </td>
                <td>
                    <div class="product-info-wrap">
                        <h4 class="product-title"><a href="{{route('frontend.products.single',$product_info->slug)}}">{{$product_info->title}}</a></h4>
                        <span class="pdetails"><strong>{{__('Price :')}}</strong> {{amount_with_currency_symbol($product_info->sale_price)}}</span>
                        <span class="pdetails"><strong>{{__('Quantity :')}}</strong> {{$item['quantity']}}</span>
                        @php $tax_amount = 0;@endphp
                        @if(get_static_option('product_tax_type') === 'individual' && is_tax_enable())
                            @php
                                $percentage = !empty($product_info->tax_percentage) ? $product_info->tax_percentage : 0;
                                $tax_amount = ($product_info->sale_price * $item['quantity']) / 100 * $product_info->tax_percentage;
                            @endphp
                            <span class="pdetails" style="color: red"><strong>{{__('Tax')}} {{'('.$percentage.'%) :'}}</strong> +{{amount_with_currency_symbol($tax_amount)}}</span>
                        @endif
                        <span class="pdetails"><strong>{{__('Subtotal :')}}</strong> {{amount_with_currency_symbol($product_info->sale_price * $item['quantity'] + $tax_amount )}}</span>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="order-summery">
        <h2 class="title">{{__('Order Summery')}}</h2>
        <div class="extra-data">
            <ul>
                <li><strong>{{__('Shipping Method:')}}</strong> {{ucwords(get_shipping_name_by_id($data->product_shippings_id))}}</li>
                <li><strong>{{__('Payment Method:')}}</strong> {{str_replace('_',' ', ucfirst($data->payment_gateway))}}</li>
                <li><strong>{{__('Payment Status:')}}</strong> {{ucfirst($data->payment_status)}}</li>
            </ul>
        </div>
        <table>
            <tr>
                <td><strong>{{__('Subtotal')}}</strong></td>
                <td>{{amount_with_currency_symbol($data->subtotal)}}</td>
            </tr>
            <tr>
                <td><strong>{{__('Coupon Discount')}}</strong></td>
                <td>- {{amount_with_currency_symbol($data->coupon_discount)}}</td>
            </tr>
            <tr>
                <td><strong>{{__('Shipping Cost')}}</strong></td>
                <td>+ {{amount_with_currency_symbol($data->shipping_cost)}}</td>
            </tr>
            @if(\App\Facades\Cart::is_tax_enable() && get_static_option('product_tax_type') === 'individual')
                @php $tax_percentage = get_static_option('product_tax_type') === 'total' ? '('.get_static_option('product_tax_percentage').')' : '';  @endphp
                <tr>
                    <td><strong>{{__('Tax')}} {{$tax_percentage}}</strong></td>
                    <td>+ {{amount_with_currency_symbol(\App\Facades\Cart::cart_tax_for_mail_template($cart_items,$order_details))}}</td>
                </tr>
            @endif

            <tr>
                <td><strong>{{__('Total')}}</strong></td>
                <td>{{amount_with_currency_symbol($data->total)}}</td>
            </tr>
        </table>
        @if(get_static_option('product_tax') && get_static_option('product_tax_system') == 'inclusive')
            <p>{{__('Inclusive of custom duties and taxes where applicable')}}</p>
        @endif
    </div>

    <footer>
        <p> {!! get_footer_copyright_text() !!}</p>
    </footer>
</div>
</body>
</html>
