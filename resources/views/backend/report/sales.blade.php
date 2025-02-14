@extends('backend.admin-master')
@section('style')
    <x-summernote.css />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <x-datatable.css />
@endsection
@section('site-title')
    Stock Report
@endsection

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        display: block;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        opacity: 0.7;
    }

    input[type="date"] {
        position: relative;
        padding-right: 30px;
    }
</style>

@section('content')
    <?php
    $orderStatus = [
        'pending' => 'Pending',
        'complete' => 'Complete',
        'failed' => 'Failed',
        'rejected' => 'Rejected',
    ];
    
    $paymentStatus = [
        'pending' => 'Pending',
        'complete' => 'Complete',
        'failed' => 'Failed',
    ];
    
    ?>

    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-msg.success />
                <x-msg.error />
                <div class="recent-order-wrapper dashboard-table bg-white mb-3">
                    <div id="product-list-title-flex"
                        class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="cursor-pointer">Filter Sales<i class="las la-angle-down"></i></h3>
                        <button id="sales-search-button" type="submit" class="cmn_btn btn_bg_profile">Apply Filter</button>
                    </div>

                    <form id="sales-search-form" class="custom__form" action="{{ route('report.sales.filter') }}"
                        method="get">
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-category">Payment Status</label>
                                    <select name="payment_status" id="">
                                        <option value="">Select</option>
                                        @foreach ($paymentStatus as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-category">Order Status</label>
                                    <select name="order_status" id="">
                                        <option value="">Select</option>
                                        @foreach ($orderStatus as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <!-- Start Date Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label-1" for="search-from_date">Start Date</label>
                                            <input type="date" id="search-from_date" name="start_date"
                                                class="form-control" max="" />
                                        </div>
                                    </div>

                                    <!-- End Date Input -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="label-1" for="search-to_date">End Date</label>
                                            <input type="date" id="search-to_date" name="end_date" class="form-control"
                                                max="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="product_name">Product Namer</label>
                                    <input name="product_name" placeholder="Enter Product Name" class="form-control"
                                        id="product_name" value="{{ old('product_name') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-category">Discount/Non Discount Sales</label>
                                    <select name="discount" id="">
                                        <option value="">Select</option>
                                        <option value="1">Discount</option>
                                        <option value="2">Non Discount</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">Sales Report</h4>
                        <div class="dashboard__card__header__right">
                            <button class="cmn_btn btn_bg_profile" id="download-excel"
                                data-bs-order="{{ json_encode($orders) }}">Download as Excel</button>
                        </div>
                    </div>
                    <div class="dashboard__card__body
                                mt-4">
                        <div class="table-wrap" id="sales-list-body">
                            {!! view('backend.report.sales_search', compact('orders')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection

@section('script')
    <x-datatable.js />
    <script src="{{ asset('assets/backend/js/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>

    <script>
        (function($) {

            // search start

            //to prevent future date input
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("search-from_date").setAttribute("max", today);
            document.getElementById("search-to_date").setAttribute("max", today);


            $("#sales-search-form").fadeOut();

            $(document).on("click", "#product-list-title-flex h3", function() {
                $("#sales-search-form").fadeToggle();
            })

            $(document).on("click", "#sales-search-button", function() {
                $("#sales-search-form").trigger("submit");
            });

            $(document).on("submit", "#sales-search-form", function(e) {
                e.preventDefault();

                let form_data = $(this).serialize(); // Serialize the form data

                $.ajax({
                    url: $(this).attr("action"), // Get the form's action URL
                    method: "POST", // Use POST method
                    data: {
                        _token: "{{ csrf_token() }}", // Include CSRF token
                        data: form_data,
                    },
                    success: function(response) {
                        $("#sales-list-body").html(response);
                        extractSalesData();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        prepare_errors(xhr.responseJSON); // Call a function to handle errors
                    }
                });
            });

            extractSalesData();

            $('#download-excel').on('click', function() {
                var orders = $(this).data('bs-order');
                console.log(orders);
                $.ajax({
                    url: "{{ route('sales.report') }}", // Your route for Excel download
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        orders: JSON.stringify(orders)
                    },
                    xhrFields: {
                        responseType: 'blob' // Expect binary data in the response
                    },
                    success: function(response, status, xhr) {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');

                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(
                                /['"]/g, '');
                        }

                        var blob = new Blob([response], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename ? filename : 'sales_report.xlsx';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error downloading Excel file:', error);
                    }
                });
            });

        })(jQuery)

        function extractSalesData() {
            var orders = [];

            $("#sales-list-body table tbody tr").each(function() {
                var row = $(this);
                var order = {
                    id: row.find("td:nth-child(8)").text().trim(), // Order ID
                    user_id: row.find("td:nth-child(9)").text().trim(), // User ID
                    invoice_number: row.find("td:nth-child(1)").text().trim(), // Invoice Number
                    address: {
                        name: row.find("td:nth-child(2)").text().trim(), // Customer Name
                        email: row.find("td:nth-child(3)").text().trim(), // Customer Email
                        full_address: row.find("td:nth-child(13)").text().trim() // Shipping Address
                    },
                    orderItems: [{
                        product_name: row.find("td:nth-child(4)").text().trim(), // Product Name
                        quantity: row.find("td:nth-child(5)").text().trim(), // Quantity
                        sale_price: row.find("td:nth-child(6)").text().trim(), // Product Sale Price
                        product_sku: row.find("td:nth-child(10)").text().trim() // Product SKU
                    }],
                    total_amount: row.find("td:nth-child(7)").text().trim(), // Total Order Amount
                    created_at: row.find("td:nth-child(11)").text().trim(), // Order Date
                    payment_gateway: row.find("td:nth-child(12)").text().trim(), // Payment Method
                    payment_status: row.find("td:nth-child(18)").text().trim(),
                    order_status: row.find("td:nth-child(14)").text().trim(), // Order Status
                    coupon_amount: row.find("td:nth-child(15)").text().trim(), // Discount Applied
                    coupon: row.find("td:nth-child(16)").text().trim(), // Coupon Applied
                    shipping_cost: row.find("td:nth-child(17)").text().trim() // Shipping Cost
                };
                orders.push(order);
            });
            $('#download-excel').data('bs-order', orders);
            // console.log(orders);
        }
    </script>
@endsection
