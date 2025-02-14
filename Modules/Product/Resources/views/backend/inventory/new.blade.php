@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Inventory') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/nice-select.css') }}">
    <style>
        .add-btn {
            width: 20px;
            height: 20px;
            text-align: center;
            background-color: #0f477396;
            border-radius: 50%;
            padding: 3px;
            display: inline-block;
            margin-right: .5rem;
        }

        .submit-btn {
            width: 20px;
            height: 20px;
            text-align: center;
            background-color: #214dda;
            border-radius: 50%;
            padding: 3px;
            display: inline-block;
            margin-right: .5rem;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-msg.error />
                    <x-msg.flash />
                </div>
            </div>
            @can('product-category-create')
                <div class="col-lg-12">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Product Inventory') }}</h4>
                            <a href="{{ route('admin.products.inventory.all') }}" type="button"
                                class="btn btn-primary">{{ __('All Product Stock') }}</a>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4" x-data="inventoryDetails()">
                            <form>
                                @csrf
                                <div class="form-group">
                                    <label for="product">{{ __('Product Name') }}</label>
                                    <select name="product_id" id="product_id" class="form-control wide">
                                        @foreach ($all_products as $product)
                                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sku">{{ __('SKU') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">{{ __('SKU -') }}</div>
                                        </div>
                                        <input type="text" class="form-control" id="sku" name="sku"
                                            placeholder="{{ __('SKU Text') }}" x-model="inventory.sku">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stock_count">{{ __('Stock Count') }}</label>
                                    <input type="number" name="stock_count" class="form-control"
                                        placeholder="{{ __('Stock Count') }}" x-model="inventory.stock_count">
                                </div>

                                <p class="h6 mt-5" x-bind:class="inventory.details.length ? 'd-block' : 'd-none'">
                                    {{ __('Stock Details') }}</p>

                                <template x-for="[details_key, details] in Object.entries(inventory.details)">
                                    <div class="row attribute_row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute_id">{{ __('Attribute Name') }}</label>
                                                <select name="attribute_id" class="form-control attribute_name"
                                                    @change="setData($event.target.value, attributes, details_key)">
                                                    <template x-for="attribute in attributes">
                                                        <option x-value="attribute.id " x-bind:data-id="attribute.id"
                                                            x-text="attribute.title"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="attribute_values">{{ __('Attribute Value') }}</label>
                                                <select name="attribute_values" id="attribute_values"
                                                    class="form-control attribute_value">
                                                    <template x-for="value in present_attribute_value[details_key]">
                                                        <option x-value="value" x-text="value"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="stock_count">{{ __('Stock Count') }}</label>
                                                <input type="number" class="form-control stock_count" name="stock_count">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div class="text-right">
                                    <button type="button" @click="addRow()" class="btn btn-info">
                                        <i class="ti-plus add-btn"></i>
                                        {{ __('Add Inventory Details') }}
                                    </button>
                                    <button type="button" @click="submitForm()" class="btn btn-primary">
                                        <i class="ti-check-box submit-btn"></i>
                                        {{ __('Submit Inventory Details') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/jquery.nice-select.min.js') }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function inventoryDetails() {
            return {
                inventory: {
                    product_id: undefined,
                    sku: '',
                    stock_count: 0,
                    details: [],
                },
                attributes: {!! $all_attributes->toJson() !!},
                present_attribute_value: [],
                addRow() {
                    let attribute = this.attributes[0];
                    this.inventory.details.push({
                        attribute_id: attribute.id,
                        attribute_value: '',
                        stock_count: 0,
                    });
                },
                setData(attribute_value, all_attribute, key) {
                    let present_detail = Object.entries(this.inventory.details)[key];
                    selected_attribute = all_attribute.filter(e => e.title == attribute_value);
                    this.setPresentAttrVal(selected_attribute[0].terms, present_detail, key);
                },
                setPresentAttrVal(data, details, key) {
                    this.present_attribute_value[key] = JSON.parse(data);
                },
                submitForm() {
                    this.inventory.product_id = $('#product_id').val();
                    let all_attributes = $('.attribute_row');
                    let result = {
                        _token: '{{ csrf_token() }}',
                        product_id: this.inventory.product_id,
                        sku: this.inventory.sku,
                        stock_count: this.inventory.stock_count,
                        inventory_details: []
                    };

                    for (let i = 0; i < all_attributes.length; i++) {
                        result.inventory_details.push({
                            attribute_id: $($('.attribute_name')[i]).find(':selected').data('id'),
                            attribute_value: $($('.attribute_value')[i]).val(),
                            stock_count: $($('.stock_count')[i]).val(),
                        });
                    };

                    $.ajax({
                        url: `{{ route('admin.products.inventory.new') }}`,
                        method: 'POST',
                        data: result,
                        success: data => {
                            if (data.type == 'success') {
                                Swal.fire('Success!', '', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: err => {
                            if (err.responseJSON.errors) {
                                let err_msg = '';
                                Object.values(err.responseJSON.errors).map(e => {
                                    err_msg += e[0] + '<br>';
                                });
                                Swal.fire(err_msg, '', 'error');
                            }
                        }
                    });
                },
            };
        }
    </script>

    <script>
        (function($) {
            'use script'
            $(document).ready(function() {
                if ($('.nice-select').length > 0) {
                    $('.nice-select').niceSelect();
                }
            });
        })(jQuery)
    </script>
@endsection
