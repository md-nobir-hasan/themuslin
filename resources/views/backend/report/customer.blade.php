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
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-msg.success />
                <x-msg.error />
                <div class="recent-order-wrapper dashboard-table bg-white mb-3">
                    <div id="product-list-title-flex"
                        class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="cursor-pointer">Filter Customer<i class="las la-angle-down"></i></h3>
                        <button id="customer-search-button" type="submit" class="cmn_btn btn_bg_profile">Apply Filter</button>
                    </div>

                    <form id="customer-search-form" class="custom__form" action="{{ route('report.customer.filter') }}"
                        method="get">
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="phone_number">Phone Number</label>
                                    <input type="text" id="phone_number" name="phone_number"
                                                class="form-control"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="email">Email</label>
                                    <input type="text" id="email" name="email"
                                                class="form-control"/>
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
                                    <label class="label-1" for="order_count">Order Count</label>
                                    <input type="number" id="order_count" name="order_count"
                                                class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">Customer Report</h4>
                        <div class="dashboard__card__header__right">
                            <button class="cmn_btn btn_bg_profile" id="download-excel"
                                data-bs-customer="{{ json_encode($customers) }}">Download as Excel</button>
                        </div>
                    </div>
                    <div class="dashboard__card__body
                                mt-4">
                        <div class="table-wrap" id="customer-list-body">
                            {!! view('backend.report.customer_search', compact('customers')) !!}
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


            $("#customer-search-form").fadeOut();

            $(document).on("click", "#product-list-title-flex h3", function() {
                $("#customer-search-form").fadeToggle();
            })

            $(document).on("click", "#customer-search-button", function() {
                $("#customer-search-form").trigger("submit");
            });

            $(document).on("submit", "#customer-search-form", function(e) {
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
                        $("#customer-list-body").html(response);
                        extractCustomerData();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        prepare_errors(xhr.responseJSON); // Call a function to handle errors
                    }
                });
            });

            extractCustomerData();

            $('#download-excel').on('click', function() {
                var customers = $(this).data('bs-customer');
                $.ajax({
                    url: "{{ route('customer.report') }}", // Your route for Excel download
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customers: JSON.stringify(customers)
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
                        link.download = filename ? filename : 'customer_report.xlsx';
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

        function extractCustomerData() {
            var customers = [];

            $("#customer-list-body table tbody tr").each(function() {
                var row = $(this);
                var customer = {
                    id: row.find("td:nth-child(1)").text().trim(),
                    first_name: row.find("td:nth-child(2)").text().trim(),
                    last_name: row.find("td:nth-child(3)").text().trim(),
                    full_name: row.find("td:nth-child(4)").text().trim(),
                    email: row.find("td:nth-child(5)").text().trim(),
                    phone: row.find("td:nth-child(6)").text().trim(),
                    created_at: row.find("td:nth-child(7)").text().trim(),
                    order_count: row.find("td:nth-child(8)").text().trim(),
                    total_spent: row.find("td:nth-child(9)").text().trim(),
                    last_order: row.find("td:nth-child(10)").text().trim(),
                    shipping_address: row.find("td:nth-child(11)").text().trim(),
                };
                customers.push(customer);
            });
            $('#download-excel').data('bs-customer', customers);
        }
    </script>
@endsection
