@extends('backend.admin-master')
@section('style')
    <x-datatable.css />
@endsection
@section('site-title')
    {{ __('My Orders') }}
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

    <div class="dashboard__card">
        <div class="dashboard__card__header">
            <h4 class="dashboard__card__title">{{ __('All Orders') }}</h4>
        </div>

        @can('product-search')
            <div class="col-md-12">
                <x-flash-msg />
                <div class="recent-order-wrapper dashboard-table bg-white">
                    <div id="product-list-title-flex"
                        class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="cursor-pointer">Search<i class="las la-angle-down"></i></h3>
                    </div>

                    <form id="order-search-form" class="custom__form" action="{{ route('admin.orders.search') }}" method="get">
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-invoice_number">Invoice Number</label>
                                    <input name="invoice_number" placeholder="Enter invoice number" class="form-control"
                                        id="search-invoice_number" value="{{ request()->name ?? old('invoice_number') }}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-order_status">Order Status</label>
                                    <select name="order_status" id="">
                                        <option value="">Select</option>
                                        @foreach ($orderStatus as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="label-1" for="search-payment_status">Payment Status</label>
                                    <select name="payment_status" id="">
                                        <option value="">Select</option>
                                        @foreach ($paymentStatus as $key => $status)
                                            <option value="{{ $key }}">{{ $status }}</option>
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
                                            <input type="date" id="search-from_date" name="start_date" class="form-control"
                                                max="" />
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

                            <div class="col-md-2">
                                <button id="order-search-button" type="submit" class="cmn_btn btn_bg_profile" style="margin-top: 25px;">{{ __('Search') }}</button>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        @endcan

        <div class="dashboard__card__body mt-4">
            <div class="table-wrap table-responsive all-user-campaign-table">
                <div class="order-history-inner text-center" id="order-table-body">
                    {!! view('order::admin.search', compact('all_orders')) !!}
                </div>
            </div>
            <div class="pagination">
                {!! $all_orders->links() !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/backend/js/sweetalert2.js') }}"></script>

    <script>
        (function($) {

            // search start

            //to prevent future date input
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("search-from_date").setAttribute("max", today);
            document.getElementById("search-to_date").setAttribute("max", today);


            $("#order-search-form").fadeOut();

            $(document).on("click", "#product-list-title-flex h3", function() {
                $("#order-search-form").fadeToggle();
            })

            $(document).on("click", "#order-search-button", function() {
                $("#order-search-form").trigger("submit");
            });

            $(document).on("submit", "#order-search-form", function(e) {
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
                        // Handle successful response
                        // Example: Update the table body with the received data
                        // if(response.success){
                        //     console.log(response);
                        // }
                        $("#order-table-body").html(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        prepare_errors(xhr.responseJSON); // Call a function to handle errors
                    }
                });

                // Alternatively, if you want to send an AJAX GET request
                // Uncomment and adjust the following code as needed
                /*
                send_ajax_request("GET", null, $(this).attr("action") + "?" + form_data, () => {
                    // Before sending the request
                    $(".load-ajax-data").fadeIn();
                }, (data) => {
                    // Success callback
                    $("#order-table-body").html(data);
                    $(".load-ajax-data").fadeOut();
                }, (data) => {
                    // Error callback
                    prepare_errors(data);
                });
                */
            });


            //search end


            "use strict";
            $(document).ready(function() {

                $(document).on('click', '.bodyUser_overlay', function() {
                    $('.user-dashboard-wrapper, .bodyUser_overlay').removeClass('show');
                });
                $(document).on('click', '.mobile_nav', function() {
                    $('.user-dashboard-wrapper, .bodyUser_overlay').addClass('show');
                });

                $(document).on('click', '.swal_delete_button', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '{{ __('Are you sure?') }}',
                        text: '{{ __('You would not be able to revert this item!') }}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });
            })
        })(jQuery)
    </script>

    <x-datatable.js />
@endsection
