@extends('backend.admin-master')
@section('site-title')
    {{ __('Product List Page') }}
@endsection

@section('style')
    <link href="{{ asset('assets/css/flatpickr.min.css') }}" rel="stylesheet">
    <x-product::variant-info.css />

    <x-media.css />
@endsection
@section('content')
    <div class="dashboard-recent-order">
        <div class="row g-4">
            @can('product-search')
                <div class="col-md-12">
                    <x-flash-msg />
                    <div class="recent-order-wrapper dashboard-table bg-white">
                        <div id="product-list-title-flex"
                            class="product-list-title-flex d-flex flex-wrap align-items-center justify-content-between">
                            <h3 class="cursor-pointer">{{ __('Search Product Module') }} <i class="las la-angle-down"></i></h3>
                            
                        </div>

                        <form id="product-search-form" class="custom__form" action="{{ route('admin.products.search') }}" method="get">
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-name">{{ __('Name') }}</label>
                                        <input name="name" class="form-control" id="search-name" value="{{ request()->name ?? old('name') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-sku">{{ __('SKU') }}</label>
                                        <input name="sku" class="form-control" id="search-sku" value="{{ request()->sku ?? old('sku') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="sort_order">{{ __('Sort') }}</label>
                                        <select name="sort_order" id="" class="form-control">
                                            <option value="1">Price Low-High</option>
                                            <option value="2">Price High-Low</option>
                                            <option value="3">Name A-Z</option>
                                            <option value="4">Name Z-A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-category">{{ __('Category') }}</label>
                                        <select name="category" class="form-control" id="search-category" >
                                            <option value="">Select</option>

                                            @foreach($categories as $value) 

                                                <option value="{{ $value->slug }}">{{ $value->name }}
                                                </option>
                                            @endforeach 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-sub_category">{{ __('Sub Category') }}</label>

                                        <select name="subcategory" class="form-control" id="search-subcategory" >
                                            <option value="">Select</option>
                                        </select>

                                        <!-- <input name="sub_category[]" class="form-control" id="search-brand" value="{{ old('sub_category') }}" /> -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-category">{{ __('Child Category') }}</label>

                                        <select name="childcategory" class="form-control" id="search-childcategory" >
                                            <option value="">Select</option>
                                        </select>

                                        <!-- <input name="child_category[]" class="form-control" id="search-category" value="{{ old('child_category') }}" /> -->
                                    </div>
                                </div>
                               <!--  <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-color">{{ __('Color Name') }}</label>
                                        <input name="color" class="form-control" id="search-color" value="{{ old('color') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-size">{{ __('Size Name') }}</label>
                                        <input name="size" class="form-control" id="search-size" value="{{ old('size') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="search-is_inventory_warn_able" class="checkbox-label-1"><input
                                                type="checkbox" name="is_inventory_warn_able" class="form--checkbox-1"
                                                id="search-is_inventory_warn_able" value="{{ old('is_inventory_warn_able') }}" />
                                            {{ __("Inventory Warning") }}</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="search-refundable" class="checkbox-label-1"> <input type="checkbox"
                                                name="refundable" class="form--checkbox-1" id="search-refundable" value="{{ old('refundable') }}" /> {{ __("Refundable") }}</label>
                                    </div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label-1" for="search-from_price">{{ __('From Price') }}</label>
                                                <input name="from_price" class="form-control" id="search-from_price" value="{{ old('from_price') }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label-1" for="search-to_price">{{ __('To Price') }}</label>
                                                <input name="to_price" class="form-control" id="search-to_price" value="{{ old('to_price') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <button id="product-search-button" type="button" class="cmn_btn btn_bg_profile" style="margin-top: 25px;">{{ __('Search') }}</button>
                                </div>

                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-date_range">{{ __('Created Date Range') }}</label>
                                        <input name="date_range" class="form-control" id="search-date_range" value="{{ old('date_range') }}" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-order_by">{{ __('Order By') }}</label>
                                        <select name="order_by" class="form-control" id="search-order_by"
                                            value="{{ old('order_by') }}">
                                            <option value="">{{ __('Select Order By Option') }}</option>
                                            <option value="asc">{{ __('Asc') }}</option>
                                            <option value="desc">{{ __('DESC') }}</option>
                                        </select>
                                    </div>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
            @endcan

            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h3 class="dashboard__card__title">{{ __('Product list') }}</h3>

                        @can('product-create')
                            <div class="dashboard__card__header__right">
                                <a class="cmn_btn btn_bg_profile" href="{{ route('admin.products.create') }}">{{ __('Add New Product') }}</a>
                            </div>
                        @endcan
                    </div>
                    <div class="dashboard__card__header mt-4">
                        @can('product-bulk-destroy')
                            <x-product::table.bulk-action />
                        @endcan
                        <div class="dashboard__card__header__right">
                            <div class="d-flex bulk-delete-wrapper gap-2">
                                @can('product-search')
                                    <label for="number-of-item">{{ __('Number Of Rows') }}</label>
                                    <select name="count" id="number-of-item">
                                        <option value="10">{{ __('10') }}</option>
                                        <option value="25">{{ __('25') }}</option>
                                        <option value="50">{{ __('50') }}</option>
                                        <option value="100">{{ __('100') }}</option>
                                    </select>
                                @endcan
                                @can('product-trash')
                                    <div class="btn-wrapper-trash margin-right-20">
                                        <a class="cmn_btn btn_bg_danger btn-sm" href="{{ route('admin.products.trash.all') }}">
                                            {{ __('Trash') }}
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-table table-wrap">
                        <div class="table-responsive mt-4" id="product-table-body">
                            {!! view('product::search', compact('products', 'statuses')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <x-product::product-image-modal/>
    <x-media.markup />
@endsection
@section('script')
    <x-media.js />

    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    @can('product-status-update')
        <x-product::table.status-js />
    @endcan

    @can('product-bulk-destroy')
        <x-product::table.bulk-action-js :url="route('admin.products.bulk.destroy')" />
    @endcan
    <x-product::product-image-js />
    <script>
        $(function() {
            $("#search-date_range").flatpickr({
                mode: "range",
                dateFormat: "Y-m-d",
            });

            $("#product-search-form").fadeOut();

            $(document).on("click", "#product-list-title-flex h3", function() {
                $("#product-search-form").fadeToggle();
            })

            $(document).ready(function() {
                $(".load-ajax-data").fadeOut();
            })

            $(document).on("click", "#product-search-button", function() {
                $("#product-search-form").trigger("submit");
            });

            $(document).on("submit", "#product-search-form", function(e) {
                e.preventDefault();
                let form_data = $("#product-search-form").serialize().toString();
                form_data += "&count=" + $("#number-of-item").val();

                // product-table-body
                send_ajax_request("GET", null, $(this).attr("action") + "?" + form_data, () => {
                    // before send request
                    $(".load-ajax-data").fadeIn();
                }, (data) => {
                    $("#product-table-body").html(data);
                    $(".load-ajax-data").fadeOut();
                }, (data) => {
                    prepare_errors(data);
                });
            });

            $(document).on("change", "#number-of-item", function(e) {
                e.preventDefault();
                let form_data = $("#product-search-form").serialize().toString()
                form_data += "&count=" + $(this).val();

                // product-table-body
                send_ajax_request("GET", null, $("#product-search-form").attr("action") + "?" + form_data,
                    () => {
                        // before send request
                        $(".load-ajax-data").fadeIn();
                    }, (data) => {
                        $("#product-table-body").html(data);
                        $(".load-ajax-data").fadeOut();
                    }, (data) => {
                        prepare_errors(data);
                    });
            });


            $(document).on("click", ".pagination-list li a", function(e) {
                e.preventDefault();

                $(".pagination-list li a").removeClass("current");
                $(this).addClass("current");

                // product-table-body
                send_ajax_request("GET", null, $(this).attr("href"), () => {
                    // before send request
                    $(".load-ajax-data").fadeIn();
                }, (data) => {
                    $("#product-table-body").html(data);
                    $(".load-ajax-data").fadeOut();
                }, (data) => {
                    prepare_errors(data);
                });
            });

            $(document).on("click", ".delete-row", function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        send_ajax_request("GET", null, $(this).data("product-url"), () => {
                            // before send request
                            toastr.warning("Request send please wait while");
                        }, (data) => {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );

                            let product = $(this).parent().parent().parent();
                            product.fadeOut();

                            setTimeout(() => {
                                product.remove();
                                $(".tenant_info").load(location.href +
                                    " .tenant_info");
                                ajax_toastr_success_message(data);
                            }, 800)

                        }, (data) => {
                            prepare_errors(data);
                        })
                    }
                });
            });


            $(document).on('change', '#search-category', function() {

                var selectedValue = $('#search-category').val();

                if (selectedValue) {
                    $.ajax({
                        url: "{{ route('category.info.backend') }}",
                        type: 'POST',
                        data: { 
                            ids: [selectedValue],
                            type: 'category', 
                            _token: "{{ csrf_token() }}"
                        }, 
                        success: function(response) {
                            if (response.type == 'success') {
                                $('#search-subcategory').html(response.html_2);
                            } else {
                                $('#search-subcategory').html('');  
                                $('#search-childcategory').html(''); 
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);  // Log any errors.
                        }
                    }); 
                } else {
                    // Optionally clear dropdowns if no valid selection is made.
                    $('#search-subcategory').html('');
                    $('#search-childcategory').html('');
                }
                return false;  // Prevent default form submission.
            });

            $(document).on('change', '#search-subcategory', function() {

                var selectedValue = $('#search-subcategory').val();

                if (selectedValue) {
                    $.ajax({
                        url: "{{ route('category.info.backend') }}",
                        type: 'POST',
                        data: { 
                            ids: [selectedValue],
                            type: 'subcategory', 
                            _token: "{{ csrf_token() }}"
                        }, 
                        success: function(response) {
                                if (response.type == 'success') {
                                    $('#search-childcategory').html(response.html_2);
                                } else {
                                    $('#search-childcategory').html(''); 
                                }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    }); 
                } else {
                    $('#search-childcategory').html('');
                }
                return false;
            });

        });
    </script>
@endsection
