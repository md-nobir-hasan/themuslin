@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Inventory') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/nice-select.css') }}">
    <x-media.css />
    <x-product::variant-info.css />
@endsection



@php
    $inventory_details = true;
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <!-- Single -->
                <div class="dashboard__card singleFlexitem social">
                    <div class="dashboard__card__productWrap">
                        <div class="dashboard__card__product listCap">
                            <div class="dashboard__card__product__thumb recentImg">
                                {!! render_image($product->image) !!}
                            </div>
                            <div class="dashboard__card__product__details recentCaption w-100">
                                <div class="w-100">
                                    <h5>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="dashboard__card__product__title featureTittle">{{ $product->name }}</a>
                                    </h5>
                                    <p class="dashboard__card__product__para featureCap">{{ $product?->summary }}</p>
                                </div>

                                <div class="dashboard__card__product__price mt-2">
                                    <h5 class="dashboard__card__product__price__title">
                                        <b class="text-dark">{{ amount_with_currency_symbol($product->sale_price) }}</b>
                                        <del>{{ amount_with_currency_symbol($product->price) }}</del>
                                    </h5>
                                </div>

                                <div class="dashboard__card__product__cate mt-3">
                                    <div class="dashboard__card__product__cate__name cat d-flex flex-column gap-1">
                                        <label class="dashboard__card__product__cate__title">{{ __('Category') }}</label>
                                        <div class="dashboard__card__product__cate__inner">
                                            @if ($product?->category)
                                                <span
                                                    class="dashboard__card__product__cate__tag pro-btn1">{{ $product?->category?->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="dashboard__card__product__cate mt-3">
                                    <label class="dashboard__card__product__cate__title">{{ __('Sub Category') }}</label>
                                    <div class="dashboard__card__product__cate__inner">
                                        @if ($product?->subCategory)
                                            <span
                                                class="dashboard__card__product__cate__tag pro-btn1">{{ $product?->subCategory?->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="dashboard__card__product__cate mt-3">
                                    <label class="dashboard__card__product__cate__title">{{ __('Child Category') }}</label>
                                    <div class="dashboard__card__product__cate__inner">
                                        @if ($product?->childCategory)
                                            @foreach ($product->childCategory as $childCategory)
                                                <span
                                                    class="dashboard__card__product__cate__tag pro-btn1">{{ $childCategory?->name }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="btn-wrapper" style="width: 150px">
                            <div class="mb-3">
                                <strong
                                    class="dashboard__card__product__month subCap">{{ $product->created_at->diffForHumans() }}</strong>
                            </div>

                            
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary"><i
                                    class="las la-pencil-alt icon"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom__form">
                <form action="{{ route('admin.products.inventory.update') }}" method="POST" id="update-inventory-form">
                    @csrf

                    <input value="{{ $product->id }}" name="product_id" type="hidden">

                    <div class="col-lg-12">
                        <x-product::product-inventory :inventory_page="true" :units="$data['units']" :inventory="$product?->inventory"
                            :uom="$product?->uom" />
                    </div>

                    @can('product-category-edit')
                        <div class="col-lg-12">
                            <x-product::product-attribute :inventorydetails="$inventory?->inventoryDetails" :colors="$product_colors" :sizes="$product_sizes"
                                :allAttributes="$all_attributes" />
                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __('Update Inventory') }}</button>
                            </div>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <x-product::variant-info.js :colors="$product_colors" :sizes="$product_sizes" :all-attributes="$all_attributes" />
    <x-datatable.js />
    <x-table.btn.swal.js />
    <script src="{{ asset('assets/backend/js/jquery.nice-select.min.js') }}"></script>
    <x-media.js />
    <script>
        (function($) {
            'use script'

            $(document).on("submit", "#update-inventory-form", function(e) {
                e.preventDefault();
                let data = new FormData(e.target);

                send_ajax_request("post", data, '{{ route('admin.products.inventory.update') }}', function() {

                }, function(data) {
                    if (data.type == 'success') {
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }

                }, function() {

                });
            });

            $(document).ready(function() {
                let nice_select_el = $('.nice-select');
                if (nice_select_el.length > 0) {
                    nice_select_el.niceSelect();
                }
            });
        })(jQuery)
    </script>
@endsection
