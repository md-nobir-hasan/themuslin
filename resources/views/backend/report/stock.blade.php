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
                        <h3 class="cursor-pointer">Filter Stock<i class="las la-angle-down"></i></h3>
                        <button id="stock-search-button" type="submit" class="cmn_btn btn_bg_profile">Apply Filter</button>
                    </div>

                    <form id="stock-search-form" class="custom__form" action="{{ route('report.stock.filter') }}"
                        method="get">
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-category">Category</label>
                                    <select name="category" id="">
                                        <option value="">Select</option>
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-5">
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
                        </div>
                    </form>
                </div>
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">Stock Report</h4>
                        <div class="dashboard__card__header__right">
                            <button class="cmn_btn btn_bg_profile" id="download-excel"
                                data-bs-product="{{ json_encode($products) }}">Download as Excel</button>
                        </div>
                    </div>
                    <div class="dashboard__card__body
                                mt-4">
                        <div class="table-wrap" id="stock-list-body">
                            {!! view('backend.report.stock_search', compact('products')) !!}
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


            $("#stock-search-form").fadeOut();

            $(document).on("click", "#product-list-title-flex h3", function() {
                $("#stock-search-form").fadeToggle();
            })

            $(document).on("click", "#stock-search-button", function() {
                $("#stock-search-form").trigger("submit");
            });

            $(document).on("submit", "#stock-search-form", function(e) {
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
                        $("#stock-list-body").html(response);
                        extractProductData();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        prepare_errors(xhr.responseJSON); // Call a function to handle errors
                    }
                });
            });

            extractProductData();

            $('#download-excel').on('click', function() {
                var products = $(this).data('bs-product');
                $.ajax({
                    url: "{{ route('stock.report') }}", // Your route for Excel download
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        products: JSON.stringify(products)
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
                        link.download = filename ? filename : 'stock_report.xlsx';
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

        function extractProductData() {
            var products = [];

            $("#stock-list-body table tbody tr").each(function() {
                var row = $(this);
                var product = {
                    id: row.find("td:nth-child(1)").text().trim(),
                    name: row.find("td:nth-child(2)").text().trim(),
                    inventory: {
                        sku: row.find("td:nth-child(3)").text().trim()
                    },
                    inventoryDetail: [{
                        stock_count: row.find("td:nth-child(4)").text().trim(),
                        sold_count: row.find("td:nth-child(5)").text().trim()
                    }],
                    category: {
                        name: row.find("td:nth-child(6)").text().trim()
                    },
                    sale_price: row.find("td:nth-child(7)").text().trim()
                };
                products.push(product);
            });
            if (products.length === 0) {
                $("#download-excel").attr("data-bs-product", JSON.stringify([]));
            } else {
                $("#download-excel").attr("data-bs-product", JSON.stringify(products));
            }
        }
    </script>
@endsection
