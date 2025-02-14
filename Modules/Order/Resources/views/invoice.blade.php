@extends('backend.admin-blank')


@section('content')
    <style type="text/css">
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Invoice Styles */
        .invoice-area {
            /* margin: 30px 0; */
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
        }

        .invoice-area-header {
            display: flex;
            justify-content: space-between;
            /*      align-items: center;*/
            margin-bottom: 10px;
            margin-to: 10px;
        }

        .invoice-area-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .invoice-area-header-info p {
            margin: 0;
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }



        .invoice-area-header-qr img {
            width: 50px;
            height: 50px;
            margin-left: 10px;
        }

        .invoice-area-body-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        /* .invoice-area-body-info-single {
                                          flex: 0 0 48%;
                                        } */

        .invoice-area-body-info-single h3 {
            margin-top: 0;
            margin-bottom: unset;
            font-size: 18px;
            color: #333;
        }

        .invoice-area-body-info-single-content {
            font-size: 14px;
            color: #666;
        }

        .invoice-area-body-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-area-body-table th,
        .invoice-area-body-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .invoice-area-body-table th {
            background-color: #f2f2f2;
            font-size: 14px;
            color: #333;
        }

        .invoice-area-body-table td {
            font-size: 12px;
            color: #666;
        }

        .invoice-area-footer p {
            margin: 10px 0;
            font-size: 12px;
            color: #666;
        }

        .invoice-area-footer table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-area-footer th,
        .invoice-area-footer td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .invoice-area-footer th {
            background-color: #f2f2f2;
            font-size: 14px;
            color: #333;
            text-align: right;
        }

        .invoice-area-footer td {
            font-size: 14px;
            color: #666;
            text-align: right;
        }

        .footer-note {
            margin-top: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    </style>



    <section class="invoice-area">
        <div class="container">
            <div class="invoice-area-header">
                <div class="invoice-area-header-logo">
                    <a href="javascript:void(0)"><img src="{{ asset('assets/muslin/images/static/logo-black.svg') }}"
                            alt="logo" /></a>
                </div>
                <div class="invoice-area-header-info">
                    <div class="invoice-area-header-qr">
                        <img src="{{ asset('assets/muslin/images/static/qr-left.jpg') }}" alt="logo" />
                        <img src="{{ asset('assets/muslin/images/static/qr-right.jpg') }}" alt="logo" />
                    </div>
                </div>
            </div>
            <div class="invoice-area-header" style="padding-top: 10px;">
                <h1>RECEIPT</h1>
                <div class="invoice-area-header-info">
                    <h1>{{ strtoupper($order->payment_status == 'complete' ? 'paid' : 'unpaid') }}</h1>
                    <p>Invoice No.: <b>{{ $order->invoice_number }}</b></p>
                    <p>Date: <b>{{ date('d M Y', strtotime($order->created_at)) }}</b></p>
                </div>
            </div>
            <div class="invoice-area-body">
                <div class="invoice-area-body-info">
                    <div class="invoice-area-body-info-single">
                        <h3>Seller</h3>
                        <div class="invoice-area-body-info-single-content">
                            <p><b>The Muslin</b></p>
                            <p>Phone: +88 01777 774324</p>
                            <p>Email: meredith@example.com</p>
                            <p>Address: 79/A, Commercial Area, Airport Road Khilkhet,
                                <br>Nikunja 2, Dhaka 1229, Bangladesh
                                <br>
                            </p>
                        </div>
                    </div>
                    <div class="invoice-area-body-info-single">
                        <h3>Customer</h3>
                        <div class="invoice-area-body-info-single-content">
                            <p><b>{{ $order->address?->name }}</b></p>
                            <p>Phone: {{ $order->address?->phone }}</p>
                            <p>Email: {{ $order->address?->email }}</p>
                            <p>Address: {{ $order->address?->address }} {{ $order->address?->city }} <br>
                                {{ $order->address?->zipcode }}, {{ $order->address?->state?->name }},
                                {{ $order->address?->country?->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="invoice-area-body-table">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-left">Description</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
                      $subtotal = 0; 
                      foreach($order->orderItems as $orderItem) {

                          $total_item_price =  $orderItem->price *  $orderItem->quantity;
                          $subtotal = $subtotal + $total_item_price; 
                ?>
                            <tr>
                                <td>
                                    {{ $orderItem->product->name }}
                                </td>
                                <td class="text-center">{{ number_format($orderItem->price) }}</td>
                                <td class="text-center"> {{ $orderItem->quantity }}
                                    {{ $orderItem->product?->uom?->unit?->name ?? '' }} </td>
                                <td class="text-right">{{ float_amount_with_currency_symbol($total_item_price) }}</td>
                            </tr>
                            <?php
                      }
                  ?>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
                <div class="invoice-area-footer">
                    <table>
                        <tbody>
                            <tr>
                                <th>Subtotal :</th>
                                <td>{{ float_amount_with_currency_symbol($subtotal) }}</td>
                            </tr>
                            <tr>
                                <th>Discount :</th>
                                <td>{{ float_amount_with_currency_symbol($order->paymentMeta?->coupon_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td>{{ float_amount_with_currency_symbol($order->paymentMeta->shipping_cost) }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td>{{ float_amount_with_currency_symbol($order->paymentMeta->total_amount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @if ($order->note)
                        <div class="footer-note">
                            <p>
                                Notes: {{$order->note ?? ''}}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            <div style="float: right;">
                <button id="invoice"
                    style="margin:10px 0px;padding: 10px 20px; border-radius: 8px; background-color: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
                    Download
                </button>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('invoice').addEventListener('click', function(e) {
                e.preventDefault();
                var invoiceElement = document.getElementById('invoice');
                invoiceElement.style.display = 'none';

                window.print();
                setTimeout(function() {
                    invoiceElement.style.display = 'block';
                }, 1000);
            });
        });
    </script>
@endsection
