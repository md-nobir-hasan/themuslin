@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Product List Page') }}
@endsection

@section('style')
    <link href="{{ asset('assets/css/flatpickr.min.css') }}" rel="stylesheet">
    <x-product::variant-info.css />
    <x-media.css type="vendor" />
@endsection
@section('content')
    <div class="dashboard-recent-order">
        <div class="row">
            <div class="col-md-12">
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header" id="product-list-title-flex">
                        <h3 class="dashboard__card__title cursor-pointer">{{ __('Search Product Module') }} <i
                                class="las la-angle-down"></i></h3>
                        <button id="product-search-button" type="submit" class="cmn_btn btn_bg_profile">Search</button>
                    </div>
                    <div class="dashboard__card__body custom__form">
                        <form id="product-search-form" class="mt-4" action="{{ route('vendor.products.search') }}"
                            method="get">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-name">{{ __("Name") }}</label>
                                        <input name="name" class="form--control input-height-1" id="search-name"
                                            value="{{ request()->name ?? old('name') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-sku">{{ __("SKU") }}</label>
                                        <input name="sku" class="form--control input-height-1" id="search-sku"
                                            value="{{ request()->sku ?? old('sku') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-brand">{{ __("Brand") }}</label>
                                        <input name="brand" class="form--control input-height-1" id="search-brand"
                                            value="{{ request()->brand ?? old('brand') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-category">{{ __("Category") }}</label>
                                        <input name="category" class="form--control input-height-1" id="search-category"
                                            value="{{ old('category') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-sub_category">{{ __("Sub Categor") }}y</label>
                                        <input name="sub_category" class="form--control input-height-1" id="search-brand"
                                            value="{{ old('sub_category') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-category">{{ __("Child Category") }}</label>
                                        <input name="child_category" class="form--control input-height-1"
                                            id="search-category" value="{{ old('child_category') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-color">{{ __("Color Name") }}</label>
                                        <input name="color" class="form--control input-height-1" id="search-color"
                                            value="{{ old('color') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-size">{{ __("Size Name") }}</label>
                                        <input name="size" class="form--control input-height-1" id="search-size"
                                            value="{{ old('size') }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group dashboard_checkbox">
                                        <input type="checkbox" name="is_inventory_warn_able" class="check_input"
                                            id="search-is_inventory_warn_able"
                                            value="{{ old('is_inventory_warn_able') }}" />
                                        <label for="search-is_inventory_warn_able" class="checkbox_label">{{ __("Inventory
                                            Warning") }}</label>
                                    </div>
                                    <div class="form-group dashboard_checkbox">
                                        <input type="checkbox" name="refundable" class="check_input" id="search-refundable"
                                            value="{{ old('refundable') }}" />
                                        <label for="search-refundable" class="checkbox_label">{{ __("Refundable") }}</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label-1" for="search-from_price">{{ __("From Price") }}</label>
                                                <input name="from_price" class="form--control input-height-1"
                                                    id="search-from_price" value="{{ old('from_price') }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="label-1" for="search-to_price">{{ __("TO Price") }}</label>
                                                <input name="to_price" class="form--control input-height-1"
                                                    id="search-to_price" value="{{ old('to_price') }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-date_range">{{ __("Created Date Range") }}</label>
                                        <input name="date_range" class="form--control input-height-1"
                                            id="search-date_range" value="{{ old('date_range') }}" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="label-1" for="search-order_by">{{ __("Order By") }}</label>
                                        <select name="order_by" class="form--control input-height-1" id="search-order_by"
                                            value="{{ old('order_by') }}">
                                            <option value="">{{ __("Select Order By Option") }}</option>
                                            <option value="asc">{{ __("Asc") }}</option>
                                            <option value="desc">{{ __("DESC") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt-4">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <div class="dashboard__card__header__left">
                            <h3 class="dashboard__card__title mb-2">{{ __('Product List') }}</h3>
                            <div class="d-flex flex-wrap bulk-delete-wrapper gap-2">
                                <label for="number-of-item">{{ __("Number Of Rows") }}</label>
                                <select name="count" id="number-of-item">
                                    <option value="10">{{ __("10") }}</option>
                                    <option value="25">{{ __("25") }}</option>
                                    <option value="50">{{ __("50") }}</option>
                                    <option value="100">{{ __("100") }}</option>
                                </select>

                                <div class="btn-wrapper-trash">
                                    <a class="btn btn-danger btn-sm"
                                        href="{{ route('vendor.products.trash.all') }}">{{ __('Trash') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard__card__header__right">
                            <x-product::table.bulk-action />
                            <div class="btn-wrapper">
                                <a class="cmn_btn btn_bg_profile"
                                    href="{{ route('vendor.products.create') }}">{{ __('Add New Product') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard__card__body dashboard-table mt-4">
                        <div class="table-wrap table-responsive" id="product-table-body">
                            @php
                                $route = 'vendor';
                            @endphp

                            {!! view('product::vendor.search', compact('products', 'statuses', 'route')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup type="vendor" />
    <x-product::product-image-modal/>
@endsection

@section('script')
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <x-product::table.status-js type="vendor" />
    <x-product::table.bulk-action-js :url="route('vendor.products.bulk.destroy')" />
    <x-product::product-image-js :route="route('vendor.products.update-image')" />
    <x-media.js type="vendor" />
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

            /*
            ========================================
                Row Remove Click Delete
            ========================================
            */
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

                            if(data){
                                setTimeout(() => {
                                    product.remove();
                                    $(".tenant_info").load(location.href +
                                        " .tenant_info");
                                    ajax_toastr_success_message("Successfully moved to trash");
                                }, 800)
                            }

                        }, (data) => {
                            prepare_errors(data);
                        })
                    }
                });
            });
        });
    </script>
@endsection
