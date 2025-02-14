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
    @php
        $subCat = $product?->subCategory?->id ?? null;
        $childCat = $product?->childCategory?->pluck('id')->toArray() ?? null;
        $cat = $product?->category?->id ?? null;
        $selectedDeliveryOption = $product?->delivery_option?->pluck('delivery_option_id')?->toArray() ?? [];
    @endphp
    <div class="dashboard-top-contents">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-inner-contents search-area top-searchbar-wrapper">
                    <div class="dashboard-flex-contetns w-100">
                        <div class="dashboard-flex-contetns w-100">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <h3 class="heading-three fw-500"> {{ __('Update Product') }} </h3>
                                <div class="button-wrappers">
                                    <a href="{{ route('vendor.products.all') }}" class="btn btn-info">{{ __("Product List") }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-products-add bg-white radius-20 mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex align-items-start">
                    <div class="col-md-2">
                        <div class="nav flex-column nav-pills border-1 radius-10 me-3" id="v-pills-tab" role="tablist"
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

                    <div class="col-md-10">
                        <form data-request-route="{{ route('vendor.products.edit', $product->id) }}" method="post"
                            id="product-create-form">
                            @csrf
                            <input name="id" type="hidden" value="{{ $product?->id }}">

                            <div class="form-button">
                                <button class="btn-sm btn btn-info">{{ __('Save Changes') }}</button>
                            </div>

                            <div class="tab-content margin-top-10" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-general-info-tab" role="tabpanel"
                                    aria-labelledby="v-general-info-tab">
                                    <x-product::general-info :brands="$data['brands']" :product="$product" />
                                </div>
                                <div class="tab-pane fade" id="v-price-tab" role="tabpanel"
                                    aria-labelledby="v-price-tab">
                                    <x-product::product-price :product="$product" />
                                </div>
                                <div class="tab-pane fade" id="v-inventory-tab" role="tabpanel"
                                    aria-labelledby="v-inventory-tab">
                                    <x-product::product-inventory :units="$data['units']" :inventory="$product?->inventory" :uom="$product?->uom" />
                                </div>
                                <div class="tab-pane fade" id="v-images-tab" role="tabpanel"
                                    aria-labelledby="v-images-tab">
                                    <x-product::product-image :product="$product" />
                                </div>
                                <div class="tab-pane fade" id="v-tags-and-label" role="tabpanel"
                                    aria-labelledby="v-tags-and-label">
                                    <x-product::tags-and-badge :badges="$data['badges']" :tag="$product?->tag" :singlebadge="$product?->badge_id" />
                                </div>
                                <div class="tab-pane fade" id="v-attributes-tab" role="tabpanel"
                                    aria-labelledby="v-attributes-tab">
                                    <x-product::product-attribute :inventorydetails="$product?->inventory?->inventoryDetails" :colors="$data['product_colors']" :sizes="$data['product_sizes']"
                                        :allAttributes="$data['all_attribute']" />
                                </div>
                                <div class="tab-pane fade" id="v-categories-tab" role="tabpanel"
                                    aria-labelledby="v-categories-tab">
                                    <x-product::categories :sub_categories="$sub_categories" :categories="$data['categories']" :child_categories="$child_categories"
                                        :selected_child_cat="$childCat" :selected_sub_cat="$subCat" :selectedcat="$cat" />
                                </div>
                                <div class="tab-pane fade" id="v-delivery-option-tab" role="tabpanel"
                                    aria-labelledby="v-delivery-option-tab">
                                    <x-product::delivery-option :selected_delivery_option="$selectedDeliveryOption" :deliveryOptions="$data['deliveryOptions']" />
                                </div>
                                <div class="tab-pane fade" id="v-meta-tag-tab" role="tabpanel"
                                    aria-labelledby="v-meta-tag-tab">
                                    <x-product::meta-seo :meta_data="$product->metaData" />
                                </div>
                                <div class="tab-pane fade" id="v-settings-tab" role="tabpanel"
                                    aria-labelledby="v-settings-tab">
                                    <x-product::settings :product="$product" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup />
    @endsection
    @section('script')
        <script src="{{ asset('assets/common/js/jquery-ui.min.js') }}" rel="stylesheet"></script>
        <x-media.js />
        <x-summernote.js />
        <x-product::variant-info.js :colors="$data['product_colors']" :sizes="$data['product_sizes']" :all-attributes="$data['all_attribute']" />

        <script>
            $('#product-slug').on('keyup', function() {
                let title_text = $(this).val();
                $('#product-slug').val(convertToSlug(title_text))
            });

            $(document).on("submit", "#product-create-form", function(e) {
                e.preventDefault();

                send_ajax_request("post", new FormData(e.target), $(this).attr("data-request-route"), function() {
                    toastr.warning("Request sent successfully ");
                }, function(data) {
                    if (data.success) {
                        toastr.success("Product updated Successfully");
                    }
                }, function(xhr) {
                    ajax_toastr_error_message(xhr);
                });
            })

            let inventory_item_id = 0;
            $(document).on("click", ".delivery-item", function() {
                $(this).toggleClass("active");
                $(this).effect("shake", {
                    direction: "up",
                    times: 1,
                    distance: 2
                }, 500);

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

                send_ajax_request("post", data, '{{ route('vendor.product.category.sub-category') }}', function() {
                    $("#sub_category").html("<option value=''>{{ __("Select Sub Category")}}</option>");
                    $("#child_category").html("<option value=''>{{ __("Select Child Category") }}</option>");
                    $("#select2-child_category-container").html('');
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
                    $("#select2-child_category-container").html('');
                }, function(data) {
                    $("#child_category").html(data.html);
                }, function(xhr) {
                    ajax_toastr_error_message(xhr);
                });
            });

            $(document).on('click', '.badge-item', function(e) {
                $(".badge-item").removeClass("active");
                // $(this).effect( "shake", { direction: "up", times: 1, distance: 2}, 500 );
                $(this).addClass("active");
                $("#badge_id_input").val($(this).attr("data-badge-id"));
            });

            $(document).on("click", ".close-icon", function() {
                $('#media_upload_modal').modal('hide');
            });
        </script>
    @endsection
