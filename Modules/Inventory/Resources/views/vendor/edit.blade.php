@extends('vendor.vendor-master')
@section('site-title')
    {{__('Product Inventory')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/nice-select.css')}}">
    <x-media.css />
    <x-product::variant-info.css />
    <style>
        .singleFlexitem {
            background: #FFFFFF;
            -webkit-box-shadow: 0px 1px 80px 12px rgba(26, 40, 68, 0.06);
            box-shadow: 0px 1px 80px 12px rgba(26, 40, 68, 0.06);
            padding: 20px;
            padding-bottom: 0;
            border-radius: 12px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            -webkit-transition: 0.4s;
            transition: 0.4s;
            cursor: pointer;
        }
        @media (max-width: 1199px) {
            .singleFlexitem {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
            }
        }
        .singleFlexitem .listCap {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            /*-webkit-box-align: center;*/
            /*-ms-flex-align: center;*/
            /*align-items: center;*/
            -webkit-transition: 0.4s;
            transition: 0.4s;
            margin-bottom: 20px;
            cursor: pointer;
            left: auto;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap {
                padding: 10px;
            }
        }
        @media (max-width: 991px) {
            .singleFlexitem .listCap {
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-bottom: 10px;
            }
        }
        .singleFlexitem .listCap .recentImg {
            margin-right: 20px;
        }
        @media (max-width: 575px) {
            .singleFlexitem .listCap .recentImg {
                margin-bottom: 15px;
            }
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentImg {
                width: 29%;
                margin-right: 9px;
            }
        }
        .singleFlexitem .listCap .recentImg img {
            border-radius: 12px;
            margin-bottom: 16px;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentImg img {
                width: 100%;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureTittle {
            font-family: var(--heading-font);
            margin-bottom: 9px;
            line-height: 1.5;
            color: var(--heading-color);
            font-weight: 500;
            font-size: 20px;
            display: block;
        }
        .singleFlexitem .listCap .recentCaption .featureTittle:hover {
            color: var(--heading-color);
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 15px;
            }
        }
        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 21px;
            }
        }
        @media only screen and (min-width: 768px) and (max-width: 991px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 18px;
            }
        }
        @media (max-width: 575px) {
            .singleFlexitem .listCap .recentCaption .featureTittle {
                font-size: 18px;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureCap {
            font-family: var(--heading-font);
            font-size: 15px;
            color: var(--heading-font);
            margin-bottom: 11px;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureCap {
                font-size: 12px;
                margin-bottom: 7px;
            }
        }
        .singleFlexitem .listCap .recentCaption .featureCap .subCap {
            font-family: var(--heading-font);
            font-family: var(--heading-font);
            color: var(--main-color-two);
            font-weight: 400;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .listCap .recentCaption .featureCap .subCap {
                font-size: 12px;
            }
        }
        .cat label {
            font-weight: 500;
            color: var(--heading-color);
        }
        .singleFlexitem .featurePricing {
            margin-bottom: 18px;
            font-family: var(--heading-font);
            color: var(--heading-color);
            font-weight: 500;
            font-size: 22px;
            display: block;
            text-align: center;
        }
        .singleFlexitem .featurePricing del{
            font-weight: 400;
            font-size: 18px;
        }
        @media only screen and (min-width: 1200px) and (max-width: 1399.99px) {
            .singleFlexitem .featurePricing {
                font-size: 17px;
                margin-bottom: 7px;
            }
        }
        .singleFlexitem:hover .cat-caption .product-price i {
            color: var(--main-color-two);
            font-size: 16px;
        }



        .pro-btn1 {
            font-family: var(--heading-font);
            -webkit-transition: 0.4s;
            -moz-transition: 0.4s;
            transition: 0.4s;
            border: 1px solid transparent;
            background: rgba(var(--customer-profile-rgb), 0.1);
            color: var(--customer-profile);
            text-transform: capitalize;
            padding: 1px 8px;
            font-size: 15px;
            font-weight: 400;
            display: inline-block;
            border-radius: 6px;
            margin-right: 6px;
            margin-bottom: 10px;

        }

        .pro-btn2 {
            font-family: var(--heading-font);
            -webkit-transition: 0.4s;
            -moz-transition: 0.4s;
            transition: 0.4s;
            border: 1px solid transparent;
            background: rgba(82, 78, 183, 0.1);
            color: var(--customer-profile);
            text-transform: uppercase;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 400;
            display: inline-block;
            border-radius: 6px;
            margin-bottom: 10px;

        }

        .pro-btn2:hover {
            background: var(--customer-profile);
            color: #fff;
        }
        .pro-btn1:hover {
            background: var(--customer-profile);
            color: #fff;
        }

        .recentImg{
            width: 360px;
        }
    </style>
@endsection



@php
    $inventory_details = true;
//    dd($product->category);
@endphp

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
{{--            <div class="col-lg-12">--}}
{{--                <div class="margin-top-40">--}}
{{--                    <x-msg.error />--}}
{{--                    <x-msg.flash />--}}
{{--                </div>--}}
{{--            </div>--}}


            <div class="col-lg-12 mt-3">
                <!-- Single -->
                <div class="singleFlexitem social">
                    <div class="listCap">
                        <div class="recentImg">
                            {!! render_image($product->image) !!}
                            <span class="featurePricing">{{ amount_with_currency_symbol($product->price) }} <del>{{ amount_with_currency_symbol($product->sale_price) }}</del></span>
                        </div>
                        <div class="recentCaption">
                            <div class="d-flex justify-content-between">
                                <h5><a href="{{ route('frontend.products.single', $product->slug) }}" class="featureTittle">{{ $product->name }}</a></h5>
                                <div class="btn-wrapper mb-20">
                                    <a href="{{ route('frontend.products.single', $product->slug) }}" class="btn btn-info">
                                        <i class="lar la-eye icon"></i><span>{{ __("View") }}</span>
                                    </a>
                                    <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-primary">
                                        {{ __("Edit") }}
                                    </a>
                                </div>
                            </div>

                            <p class="featureCap">{{ $product?->summary }}</p>

                            <div class="d-flex">
                                  <div class="cat d-flex flex-column gap-1">
                                      <label>{{ __("Category") }}</label>
                                      @if($product?->category)
                                          <span class="pro-btn1">{{$product?->category?->name}}</span>
                                      @endif
                                  </div>
                                <div class="cat d-flex flex-column gap-1">
                                    <label>{{ __("Sub Category") }}</label>
                                    @if($product?->subCategory)
                                        <span class="pro-btn1">{{$product?->subCategory?->name}}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="cat">
                                <label>{{ __("Child Category") }}</label>
                                @if($product?->childCategory)
                                    @foreach($product->childCategory as $childCategory)
                                        <span class="pro-btn1">{{ $childCategory?->name }}</span>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('vendor.products.inventory.update') }}" method="POST" id="update-inventory-form">
                @csrf
                <input value="{{ $product->id }}" name="product_id" type="hidden">

                <div class="col-lg-12 mt-3">
                    <div class="card p-5">
                        <x-product::product-inventory :inventory_page="true" :units="$data['units']"
                              :inventory="$product?->inventory"
                              :uom="$product?->uom"
                        />
                    </div>
                </div>

                <div class="col-lg-12 mt-3">
                    <x-product::product-attribute
                        :inventorydetails="$inventory?->inventoryDetails" :colors="$product_colors"
                        :sizes="$product_sizes"
                        :allAttributes="$all_attributes"
                    />
                </div>

                <div class="form-group">
                    <button class="btn btn-sm btn-info">{{ __("Update Inventory") }}</button>
                </div>
            </form>
        </div>
    </div>
    <x-media.markup  type="vendor"/>
@endsection
@section('script')

    <x-product::variant-info.js :colors="$product_colors" :sizes="$product_sizes"
                                :all-attributes="$all_attributes" />
    <x-datatable.js />
    <x-table.btn.swal.js />
    <script src="{{ asset('assets/backend/js/jquery.nice-select.min.js') }}"></script>
    <x-media.js type="vendor"/>
    <script>
        (function ($) {
            'use script'

            $(document).on("submit", "#update-inventory-form", function (e) {
                e.preventDefault();
                let data = new FormData(e.target);

                send_ajax_request("post", data, '{{ route('vendor.products.inventory.update') }}', function () {

                }, function (data) {
                    if(data.type == 'success'){
                        toastr.success(data.msg);
                    }else{
                        toastr.error(data.msg);
                    }

                }, function () {

                });
            });

            $(document).ready(function () {
                let nice_select_el = $('.nice-select');
                if (nice_select_el.length > 0) {
                    nice_select_el.niceSelect();
                }
            });
        })(jQuery)
    </script>

@endsection
