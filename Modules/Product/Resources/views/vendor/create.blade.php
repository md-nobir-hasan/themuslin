@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Add new Product') }}
@endsection
@section('style')
    <x-media.css />
    <x-summernote.css />
    <x-product::variant-info.css />
@endsection
@section('content')
    <div class="dashboard-top-contents">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-inner-contents search-area top-searchbar-wrapper">
                    <div class="dashboard-flex-contetns">
                        <div class="dashboard-left-flex">
                            <h3 class="dashboard__card__title"> {{ __('Add Products') }} </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-products-add bg-white radius-20 mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="row g-4">
                    <div class="col-sm-4 col-md-3 col-lg-4 col-xl-3 col-xxl-2">
                        <div class="nav flex-column nav-pills border-1 radius-10" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-general-info-tab" data-bs-toggle="pill"
                                data-bs-target="#v-general-info-tab" type="button" role="tab"
                                aria-controls="v-general-info-tab" aria-selected="true"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('General Info') }}
                            </button>
                            <button class="nav-link" id="v-pills-price-tab" data-bs-toggle="pill"
                                data-bs-target="#v-price-tab" type="button" role="tab" aria-controls="v-price-tab"
                                aria-selected="false"><span style='font-size:15px; padding-right: 7px;'>&#9679;</span>
                                {{ __('Price') }}
                            </button>
                            <button class="nav-link" id="v-pills-images-tab-tab" data-bs-toggle="pill"
                                data-bs-target="#v-images-tab" type="button" role="tab" aria-controls="v-images-tab"
                                aria-selected="false"><span style='font-size:15px; padding-right: 7px;'>&#9679;</span>
                                {{ __('Images') }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-inventory-tab" type="button" role="tab"
                                aria-controls="v-inventory-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Inventory') }}
                            </button>
                            <button class="nav-link" id="v-pills-tags-and-label" data-bs-toggle="pill"
                                data-bs-target="#v-tags-and-label" type="button" role="tab"
                                aria-controls="v-tags-and-label" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Tags & Label') }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-attributes-tab" type="button" role="tab"
                                aria-controls="v-attributes-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Attributes') }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-categories-tab" type="button" role="tab"
                                aria-controls="v-categories-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Categories') }}
                            </button>
                            <button class="nav-link" id="v-pills-delivery-option-tab" data-bs-toggle="pill"
                                data-bs-target="#v-delivery-option-tab" type="button" role="tab"
                                aria-controls="v-delivery-option-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Delivery Option') }}
                            </button>
                            <button class="nav-link" id="v-pills-meta-tag-tab" data-bs-toggle="pill"
                                data-bs-target="#v-meta-tag-tab" type="button" role="tab"
                                aria-controls="v-meta-tag-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span> {{ __('Product Meta') }}
                            </button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-settings-tab" type="button" role="tab"
                                aria-controls="v-settings-tab" aria-selected="false"><span
                                    style='font-size:15px; padding-right: 7px;'>&#9679;</span>
                                {{ __('Product Settings') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-8 col-md-9 col-lg-8 col-xl-9 col-xxl-10">
                        <form data-request-route="{{ route('vendor.products.create') }}" method="post"
                            id="product-create-form">
                            @csrf
                            <div class="form-button">
                                <button class="cmn_btn btn_bg_profile">{{ __('Create Product') }}</button>
                            </div>

                            <div class="tab-content margin-top-10" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-general-info-tab" role="tabpanel"
                                    aria-labelledby="v-general-info-tab">
                                    <x-product::general-info :brands="$data['brands']" />
                                </div>
                                <div class="tab-pane fade" id="v-price-tab" role="tabpanel"
                                    aria-labelledby="v-price-tab">
                                    <x-product::product-price />
                                </div>
                                <div class="tab-pane fade" id="v-inventory-tab" role="tabpanel"
                                    aria-labelledby="v-inventory-tab">
                                    <x-product::product-inventory :units="$data['units']" />
                                </div>
                                <div class="tab-pane fade" id="v-images-tab" role="tabpanel"
                                    aria-labelledby="v-images-tab">
                                    <x-product::product-image />
                                </div>
                                <div class="tab-pane fade" id="v-tags-and-label" role="tabpanel"
                                    aria-labelledby="v-tags-and-label">
                                    <x-product::tags-and-badge :badges="$data['badges']" />
                                </div>
                                <div class="tab-pane fade" id="v-attributes-tab" role="tabpanel"
                                    aria-labelledby="v-attributes-tab">
                                    <x-product::product-attribute :is-first="true" :colors="$data['product_colors']" :sizes="$data['product_sizes']"
                                        :allAttributes="$data['all_attribute']" />
                                </div>
                                <div class="tab-pane fade" id="v-categories-tab" role="tabpanel"
                                    aria-labelledby="v-categories-tab">
                                    <x-product::categories :categories="$data['categories']" />
                                </div>
                                <div class="tab-pane fade" id="v-delivery-option-tab" role="tabpanel"
                                    aria-labelledby="v-delivery-option-tab">
                                    <x-product::delivery-option :deliveryOptions="$data['deliveryOptions']" />
                                </div>
                                <div class="tab-pane fade" id="v-meta-tag-tab" role="tabpanel"
                                    aria-labelledby="v-meta-tag-tab">
                                    <x-product::meta-seo />
                                </div>
                                <div class="tab-pane fade" id="v-settings-tab" role="tabpanel"
                                    aria-labelledby="v-settings-tab">
                                    <x-product::settings />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup type="vendor" />
    @endsection
    @section('script')
        <script src="{{ asset('assets/common/js/jquery-ui.min.js') }}" rel="stylesheet"></script>
        <x-media.js type="vendor" />
        <x-summernote.js />
        <x-product::variant-info.js :colors="$data['product_colors']" :sizes="$data['product_sizes']" :all-attributes="$data['all_attribute']" />

        <script>
            $(document).ready(function (){
                $("#country_id").select2()
                $("#state_id").select2();
                $("#city_id").select2();
            })
            $('#product-name , #product-slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#product-slug').val(convertToSlug(title_text))
            });

            $(document).on('change', '.item_attribute_name', function() {
                let terms = $(this).find('option:selected').data('terms');
                let terms_html = '<option value=""><?php echo e(__('Select attribute value')); ?></option>';
                terms.map(function(term) {
                    terms_html += '<option value="' + term + '">' + term + '</option>';
                });
                $(this).closest('.inventory_item').find('.item_attribute_value').html(terms_html);
            })

            $(document).on("submit", "#product-create-form", function(e) {
                e.preventDefault();

                send_ajax_request("post", new FormData(e.target), $(this).attr("data-request-route"), function() {
                    toastr.warning("Request sent successfully ");
                }, function(data) {
                    if (data.success) {
                        toastr.success("Product Created Successfully");
                        toastr.success("You are redirected to product list page");
                        setTimeout(function() {
                            window.location.href = "{{ route('vendor.products.create') }}";
                        })
                    }
                }, function(xhr) {
                    ajax_toastr_error_message(xhr);
                });
            })

            $(document).on("change", "#category", function() {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("category_id", $(this).val());

                send_ajax_request("post", data, '{{ route('vendor.product.category.sub-category') }}', function() {
                    $("#sub_category").html("<option value=''>{{ __("Select Sub Category")}}</option>");
                    $("#child_category").html("<option value=''>{{ __("Select Child Category") }}</option>");
                }, function(data) {
                    $("#sub_category").html(data.html);
                }, function(xhr) {
                    ajax_toastr_error_message(xhr);
                });
            });

            $(document).on("change", "#sub_category", function() {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("sub_category_id", $(this).val());

                send_ajax_request("post", data, '{{ route('vendor.product.category.child-category') }}', function() {
                    $("#child_category").html("<option value=''>{{ __("Select Child Category")}}</option>");
                }, function(data) {
                    $("#child_category").html(data.html);
                }, function(xhr) {
                    ajax_toastr_error_message(xhr);
                });
            });

            $(document).on("click", ".delivery-item", function() {
                $(this).toggleClass("active");
                $(this).effect("shake", {
                    direction: "up",
                    times: 1,
                    distance: 2
                }, 'fast');
                let delivery_option = "";

                $.each($(".delivery-item.active"), function() {
                    delivery_option += $(this).data("delivery-option-id") + " , ";
                })

                delivery_option = delivery_option.slice(0, -3)

                $(".delivery-option-input").val(delivery_option);
            });

            $(document).on("change", "#category", function() {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("category_id", $(this).val());

                send_ajax_request("post", data, '{{ route('admin.product.category.sub-category') }}', function() {
                    $("#sub_category").html("<option value=''>Select Sub Category</option>");
                    $("#child_category").html("<option value=''>Select Child Category</option>");
                    $("#select2-child_category-container").html('');
                }, function(data) {
                    $("#sub_category").html(data.html);
                }, function() {

                });
            });

            $(document).on("change", "#sub_category", function() {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("sub_category_id", $(this).val());

                send_ajax_request("post", data, '{{ route('admin.product.category.child-category') }}', function() {
                    $("#child_category").html("<option value=''>Select Child Category</option>");
                    $("#select2-child_category-container").html('');
                }, function(data) {
                    $("#child_category").html(data.html);
                }, function() {

                });
            });

            $(document).on('click', '.badge-item', function(e) {
                $(".badge-item").removeClass("active");
                $(this).effect("shake", {
                    direction: "up",
                    times: 1,
                    distance: 2
                }, 'fast');
                $(this).addClass("active");
                $("#badge_id_input").val($(this).attr("data-badge-id"));
            });

            $(document).on("click", ".close-icon", function() {
                $('#media_upload_modal').modal('hide');
            });
        </script>
    @endsection
