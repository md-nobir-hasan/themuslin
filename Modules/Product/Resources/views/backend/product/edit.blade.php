@extends('backend.admin-master')
@section('site-title')
    {{ __('Edit Product') }}
@endsection
@section('style')
    <x-media.css />
    <x-product.more-info.css />
    <x-product.variant-info.css />
    <x-summernote.css />
    <x-niceselect.css />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/bootstrap-tagsinput.css') }}">
    <style>
        #attribute_price_container {
            /* display: none; */
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30" id="app">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-msg.error />
                    <x-msg.flash />
                </div>
            </div>
            <div class="col-lg-12">
                <div class="text-right mb-5">
                    @can('product-view')
                        <a href="{{ route('frontend.products.single', $product->slug) }}" class="btn btn-info" target="_blank">
                            <i class="ti-arrow-top-right"></i>
                        </a>
                    @endcan
                    <a href="{{ route('admin.products.all') }}" class="btn btn-primary">{{ __('All Products') }}</a>
                </div>
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body p-5">
                                    <h5 class="mb-5">{{ __('Product Information') }}</h5>
                                    <div class="form-group">
                                        <label for="title">{{ __('Name') }}</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ $product->title }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="slug">{{ __('Slug') }}</label>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            value="{{ $product->slug }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="summary">{{ __('Summary') }}</label>
                                        <textarea class="form-control" name="summary" id="summary" cols="30" rows="3">{{ $product->summary }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">{{ __('Description') }}</label>
                                        <textarea class="form-control summernote" name="description" id="description" cols="30" rows="10">{{ $product->description }}</textarea>
                                    </div>
                                    <div class="form-group " id="blog_tag_list">
                                        @php
                                            $tags = $product->tags;
                                            $tags_str = '';
                                            if ($tags) {
                                                foreach ($tags as $key => $product_tag) {
                                                    $seperator = $key != count($tags) - 1 ? ',' : '';
                                                    $tags_str .= $product_tag->tag . $seperator;
                                                }
                                            }
                                        @endphp
                                        <label for="title">{{ __('Tag') }}</label>
                                        <input type="text" class="form-control tags_filed" data-role="tagsinput"
                                            name="tags" value=" {{ $tags_str }}">

                                        <div id="show-autocomplete" style="display: none;">
                                            <ul class="autocomplete-warp"></ul>
                                        </div>
                                    </div>
                                    <div id="attribute_price_container" class="d-none">
                                        <h5 class="my-3">{{ __('Attributes') }}</h5>
                                        @php
                                            $all_attributes = json_decode($product->attributes, true);
                                        @endphp
                                        @if (is_array($all_attributes))
                                            @forelse ($all_attributes as $attribute_id => $attributes)
                                                @foreach ($attributes as $attribute)
                                                    <div class="row attribute_row">
                                                        <div class="col">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <label for="">{{ __('Attribute') }}</label>
                                                                        <input type="hidden" name="attribute_id[]"
                                                                            value="{{ $attribute_id }}" />
                                                                        <input type="hidden" name="attribute_selected[]"
                                                                            value="{{ $attribute['name'] }}" />
                                                                        <input type="hidden" name="attribute_name[]"
                                                                            value="{{ $attribute['type'] }}" />
                                                                        <input type="text"
                                                                            class="form-control font-weight-bold"
                                                                            value="{{ $attribute['type'] }}: {{ $attribute['name'] }}"
                                                                            disabled="">
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="">{{ __('Additional price amount') }}</label>
                                                                        <input type="number" class="form-control"
                                                                            name="attr_additional_price[]"
                                                                            value="{{ $attribute['additional_price'] }}"
                                                                            step="0.01">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto product_image">
                                                            <x-media-upload :title="__('Attribute Image')" :id="$attribute['attribute_image'] ?? ''"
                                                                :name="'attribute_image[]'" :dimentions="'1280x1280'" />
                                                        </div>
                                                        <div class="col-auto">
                                                            <button
                                                                class="btn btn-sm btn-danger margin-top-30 remove_attribute"
                                                                data-id="{{ optional($product)->id ?? '' }}"
                                                                data-type="{{ $attribute['type'] ?? '' }}"
                                                                data-value="{{ $attribute['name'] ?? '' }}">-</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @empty
                                                <span id="no_attributes">{{ __('No Attributes') }}</span>
                                            @endforelse
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body px-5 pb-5">
                                    <div class="additional_info_container">
                                        <h5 class="mb-3">{{ __('Additional Information') }}</h5>
                                        <p class="mb-4">
                                            {{ __('This additional info will display between description and reviews.') }}
                                        </p>
                                        <div class="additional_info">
                                            @if ($product->additionalInfo)
                                                @foreach ($product->additionalInfo as $additional_info)
                                                    <x-product.more-info.repeater :infoTitle="$additional_info->title" :infoText="$additional_info->text" />
                                                @endforeach
                                            @endif
                                            <x-product.more-info.repeater :isFirst="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-body px-5 pb-5">
                                    <h5 class="mb-4">{{ __('Inventory') }}</h5>
                                    <div class="inventory_items_container">
                                        <p class="mb-4">
                                            {{ __('Inventory will be variant of this product.') }} <br>
                                            {{ __('All inventory stock count will be merged and replace to main stock of
                                                                                        this product.') }}<br>
                                            {{ __('Stock count filed is required.') }}
                                        </p>
                                        @if (!is_null($product->inventoryDetails) && $product->inventoryDetails->count())
                                            @foreach ($product->inventoryDetails as $key => $inventoryDetail)
                                                <x-product.variant-info.repeater :key="$key" :colors="$product_colors"
                                                    :sizes="$product_sizes" :selected-size="$inventoryDetail->productSize" :selected-color="$inventoryDetail->productColor"
                                                    :all-available-attributes="$all_attribute" :inventoryDetail="$inventoryDetail" :loop="$loop->iteration" />
                                            @endforeach
                                        @else
                                            <x-product.variant-info.repeater :is-first="true" :colors="$product_colors"
                                                :sizes="$product_sizes" :all-available-attributes="$all_attribute" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="mb-5 mt-3">{{ __('Stock Information') }}</h5>
                                    <div class="form-group">
                                        <label for="sku">{{ __('Product SKU') }}</label>
                                        <input type="text" id="sku" name="sku" class="form-control"
                                            value="{{ optional($product->inventory)->sku ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="stock_count">{{ __('Items in Stock') }}</label>
                                        <input type="number" id="stock_count" name="stock_count" class="form-control"
                                            required value="{{ optional($product->inventory)->stock_count ?? 0 }}">
                                        <small>{{ __('This will be replaced with the sum of inventory items if any inventory item is registered.') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-5 mt-3">{{ __('More Information') }}</h5>
                                    <div class="form-row mb-3">
                                        <div class="col">
                                            <label for="price">{{ __('Price') }}</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                value="{{ $product->price }}" step="0.01">
                                        </div>
                                        <div class="col">
                                            <label for="sale_price">{{ __('Sale Price') }}</label>
                                            <input type="number" name="sale_price" id="sale_price" class="form-control"
                                                value="{{ $product->sale_price }}" step="0.01">
                                        </div>
                                    </div>
                                    <div class="form-row mb-3 d-none">
                                        <div class="col">
                                            <label for="tax_percentage">{{ __('Tax Percentage') }}</label>
                                            <input type="number" name="tax_percentage" id="tax_percentage"
                                                class="form-control" value="{{ $product->tax_percentage }}"
                                                step="0.01">
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col">
                                            <label for="unit">{{ __('Quantity') }}</label>
                                            <input type="number" class="form-control" name="unit" id="unit"
                                                step="0.01" value="{{ $product->uom?->quantity }}">
                                        </div>
                                        <div class="col">
                                            <label for="uom">{{ __('Unit') }}</label>
                                            <select name="uom" id="uom" class="form-control">
                                                <option value="">{{ __('Select unit of measurement') }}</option>
                                                @foreach ($all_measurement_units as $unit)
                                                    <option value="{{ $unit->name }}"
                                                        @if ($product->uom?->unit?->name == $unit->name) selected @endif>
                                                        {{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group d-none">
                                        <label for="attributes_options">{{ __('Attributes') }}</label>
                                        <div class="form-row">
                                            <div class="col">
                                                <select class="form-control" name="attributes_options"
                                                    id="attributes_options">
                                                    <option value="">{{ __('Select Attribute') }}</option>
                                                    @foreach ($all_attribute as $attribute)
                                                        <option value="{{ $attribute->id }}"
                                                            data-terms="{{ $attribute->terms }}">{{ $attribute->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="attribute_container"></div>
                                    <div class="form-group">
                                        <label for="category_id">{{ __('Category') }}</label>
                                        <select class="form-control" name="category_id" id="category_id">
                                            @foreach ($all_category as $category)
                                                <option value="{{ $category->id }}"
                                                    @if ($product->category_id == $category->id) selected @endif>
                                                    {{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sub_category_id">{{ __('Sub-category') }}</label>
                                        @php
                                            $subcategories = json_decode($product->sub_category_id, true);
                                        @endphp
                                        @if ($subcategories)
                                            <select class="form-control wide" name="sub_category_id[]"
                                                id="sub_category_id" multiple>
                                                @foreach ($all_sub_category as $subcategory)
                                                    <option value="{{ $subcategory->id }}"
                                                        @if (in_array($subcategory->id, $subcategories)) selected @endif>
                                                        {{ $subcategory->title }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select class="form-control wide" name="sub_category_id[]"
                                                id="sub_category_id" multiple>
                                                <option value="">{{ __('Select Sub-category') }}</option>
                                                @foreach ($all_sub_category as $subcategory)
                                                    <option value="{{ $subcategory->id }}">{{ $subcategory->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <span class="text-secondary">({{ __('Press ') }} <kbd>{{ __('Ctrl') }}</kbd>
                                            {{ __(' and Click to select multiple options') }})</span>
                                    </div>
                                    <x-media-upload :title="__('Image')" :id="$product->image" :name="'image'"
                                        :dimentions="'1280x1280'" :multiple="true" :galleryImages="$product->product_image_gallery" />
                                    <div class="form-group">
                                        <label for="badge">{{ __('Badge') }}</label>
                                        <input type="text" name="badge" id="badge" class="form-control"
                                            value="{{ $product->badge }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="draft" @if ($product->status == 'draft') selected @endif>
                                                {{ __('Draft') }}</option>
                                            <option value="publish" @if ($product->status == 'publish') selected @endif>
                                                {{ __('Publish') }}</option>
                                        </select>
                                    </div>
                                    <div class="text-center mt-5">
                                        <button class="btn btn-primary">{{ __('Update Product') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <x-media.js />
    <x-product.more-info.js />
    <x-product.variant-info.js :colors="$product_colors" :sizes="$product_sizes" :all-attributes="$all_attribute" />
    <x-niceselect.js />
    <x-summernote.js />
    <script src="{{ asset('assets/backend/js/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('assets/common/js/typeahead.bundle.min.js') }}"></script>
    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {
                let inventory_item_id = 0;

                $('.summernote').summernote({
                    height: 500, //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        },
                        onPaste: function (e) {
                            let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/plain');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });

                if ($('.nice-select').length) {
                    $('.nice-select').niceSelect();
                }

                $('#attributes_options').on('change', function() {
                    let title = $('#attributes_options').find(':selected').text();
                    let title_id = $('#attributes_options').val();
                    let terms = $('#attributes_options').find(':selected').data('terms');
                    let options = '';

                    terms.forEach(e => {
                        options += `<option value="${e}">${e}</option>`;
                    });

                    let html = `<div class="form-group">
                               <label>${title}</label>
                               <select class="form-control" data-attr-id="${title_id}" data-attr-name="${title}" multiple>
                                   ${options}
                               </select>
                               <small class="text-secondary">{{ __('Double click on an option to add') }}</small>
                            </div>`;

                    $('#attribute_container').html(html);
                });

               
                $(document).on('click', '.add_item_attribute', function(e) {
                    let container = $(this).closest('.inventory_item');
                    let attribute_name_field = container.find('.item_attribute_name');
                    let attribute_value_field = container.find('.item_attribute_value');
                    let attribute_name = attribute_name_field.find('option:selected').text();
                    let attribute_value = attribute_value_field.find('option:selected').text();

                    let container_id = container.data('id');

                    if (!container_id) {
                        container_id = 0;
                    }

                    if (attribute_name_field.val().length && attribute_value_field.val().length) {
                        let attribute_repeater = '';
                        attribute_repeater += '<div class="form-row">';
                        attribute_repeater += '<input type="hidden" name="item_attribute_id[' +
                            container_id + '][]" value="">';
                        attribute_repeater += '<div class="col">';
                        attribute_repeater += '<div class="form-group">';
                        attribute_repeater +=
                            '<input type="text" class="form-control" name="item_attribute_name[' +
                            container_id + '][]" value="' + attribute_name + '" readonly />';
                        attribute_repeater += '</div>';
                        attribute_repeater += '</div>';
                        attribute_repeater += '<div class="col">';
                        attribute_repeater += '<div class="form-group">';
                        attribute_repeater +=
                            '<input type="text" class="form-control" name="item_attribute_value[' +
                            container_id + '][]" value="' + attribute_value + '" readonly />';
                        attribute_repeater += '</div>';
                        attribute_repeater += '</div>';
                        attribute_repeater += '<div class="col-auto">';
                        attribute_repeater +=
                            '<button class="btn btn-danger remove_details_attribute"> x </button>';
                        attribute_repeater += '</div>';
                        attribute_repeater += '</div>';

                        container.find('.item_selected_attributes').append(attribute_repeater);

                        attribute_name_field.val('');
                        attribute_value_field.val('');
                    } else {
                        toastr.warning('{{ __('Select both attribute name and value') }}');
                    }
                });

                $(document).on('click', '.repeater_button .add', function(e) {
                    let inventory_item =
                        `<x-product.variant-info.repeater :colors="$product_colors" :sizes="$product_sizes" :all-available-attributes="$all_attribute" />`;

                    if (inventory_item_id < 1) {
                        inventory_item_id = $('.inventory_items_container .inventory_item').length;
                    }

                    $('.inventory_items_container').append(inventory_item);
                    $('.inventory_items_container .inventory_item:last-child').data('id',
                        inventory_item_id + 1);
                });

                $(document).on('click', '.repeater_button .remove', function(e) {
                    if ($(".repeater_button .remove").length != 1) {
                        $(this).closest('.inventory_item').remove();
                    }
                });

                $(document).on('click', '.remove_attribute', function(e) {
                    function removeRow(context) {
                        $(context).closest('.row').remove();
                    }

                    function setContainerVisibility() {
                        if ($('#attribute_price_container .row').length < 1) {
                            $('#attribute_price_container').hide();
                        }
                    }

                    e.preventDefault();
                    let id = $(this).data('id');
                    let type = $(this).data('type');
                    let value = $(this).data('value');

                    if (id) {
                        $.post('{{ route('admin.products.inventory.attribute.delete') }}', {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            type: type,
                            value: value,
                        }).then(function(data) {
                            toastr[data['status']](data['msg']);

                            if (data['status']) {
                                removeRow(this);
                                setContainerVisibility();
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    } else {
                        removeRow(this);
                        setContainerVisibility();
                    }
                });

                $(document).on('change', '.item_attribute_name', function() {
                    let terms = $(this).find('option:selected').data('terms');
                    let terms_html = '<option value="">{{ __('Select attribute value') }}</option>';
                    terms.map(function(term) {
                        terms_html += '<option value="' + term + '">' + term + '</option>';
                    });
                    $(this).closest('.inventory_item').find('.item_attribute_value').html(terms_html);
                })

                $(document).on('click', '.remove_details_attribute', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');

                    function remove_row(context) {
                        $(context).closest('.form-row').remove();
                    }

                    if (!id) {
                        remove_row(this);
                    } else {
                        $.post('{{ route('admin.products.inventory.details.attribute.delete') }}', {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        }).then(function(data) {
                            toastr[data['type']](data['msg']);
                            if (data.msg) {
                                remove_row(this);
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            }
                        });
                    }
                })

                $(document).on('dblclick', '#attribute_container select option', function(e) {
                    let attribute_title = $(e.target).parent().data('attrName');
                    let attribute_id = $(e.target).parent().data('attrId');
                    let selected_attribute_value = e.target.value;

                    if ($('#no_attributes').length) {
                        $('#no_attributes').remove();
                    }

                    $('#attribute_price_container').append(
                        `<div class="row attribute_row">
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">{{ __('Attribute') }}</label>
                                        <input type="hidden" name="attribute_id[]" value="${attribute_id}" />
                                        <input type="hidden" name="attribute_selected[]" value="${selected_attribute_value}" />
                                        <input type="hidden" name="attribute_name[]" value="${attribute_title}" />
                                        <input type="text" class="form-control font-weight-bold" value="${attribute_title}: ${selected_attribute_value}" disabled="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">{{ __('Additional price amount') }}</label>
                                        <input type="number" class="form-control" name="attr_additional_price[]" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto product_image">
                            <x-media-upload :title="__('Attribute Image')" :name="'attribute_image[]'" :dimentions="'1280x1280'" />
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-danger margin-top-30 remove_attribute">-</button>
                        </div>
                    </div>`
                    );

                    if ($('#attribute_price_container .row').length > 0) {
                        $('#attribute_price_container').show();
                    }
                });

                let all_tags = {!! json_encode($all_tags->pluck('tag_text')) !!};

                let bindCarList = function() {
                    // Call TagsInput on the input, and set the typeahead source to our data
                    $('#tags').tagsinput({
                        typeahead: {
                            source: all_tags
                        }
                    });

                    $('#tags').on('itemAdded', function(event) {
                        // Hide the suggestions menu
                        $('.typeahead.dropdown-menu').css('display', 'none')
                        // Clear the typed text after a tag is added
                        $('.bootstrap-tagsinput > input').val("");
                    });
                }

                function convertToSlug(text) {
                    return text
                        .toLowerCase()
                        .replace(/ /g, '-')
                        .replace(/[^\w-]+/g, '');
                }

                // bindCarList();
                var blogTagInput = $('#blog_tag_list .tags_filed');
                var oldTag = '';
                blogTagInput.tagsinput();
                //For Tags
                $(document).on('keyup', '#blog_tag_list .bootstrap-tagsinput input[type="text"]', function(e) {
                    e.preventDefault();
                    var el = $(this);
                    var inputValue = $(this).val();
                    $.ajax({
                        type: 'get',
                        url: "{{ route('admin.products.tag.get.ajax') }}",
                        async: false,
                        data: {
                            query: inputValue
                        },

                        success: function(data) {
                            oldTag = inputValue;
                            let html = '';
                            var showAutocomplete = '';
                            $('#show-autocomplete').html(
                                '<ul class="autocomplete-warp"></ul>');
                            if (el.val() != '' && data.markup != '') {


                                data.result.map(function(tag, key) {
                                    html += '<li class="tag_option" data-id="' + tag
                                        .id + '" data-val="' + tag.tag + '">' + tag
                                        .tag + '</li>'
                                })

                                $('#show-autocomplete ul').html(html);
                                $('#show-autocomplete').show();


                            } else {
                                $('#show-autocomplete').hide();
                                oldTag = '';
                            }

                        },
                        error: function(res) {

                        }
                    });
                });

                $(document).on('click', '.tag_option', function(e) {
                    e.preventDefault();

                    let id = $(this).data('id');
                    let tag = $(this).data('val');
                    blogTagInput.tagsinput('add', tag);
                    $(this).parent().remove();
                    blogTagInput.tagsinput('remove', oldTag);
                });

                $(document).on('click', '.remove_this_variant_info_btn', function(e) {
                    e.preventDefault();
                    let context = this;

                    function removeVariantRow(context) {
                        $(context).closest('.variant_variant_info_repeater').remove();
                    }

                    if ($(this).hasClass('remove_variant')) {
                        let variant_id = $(context).closest('.variant_variant_info_repeater').find(
                            '.variant_id').val()
                        $.post('{{ route('admin.remove.inventory.variant') }}', {
                            _token: '{{ csrf_token() }}',
                            variant_id: variant_id
                        }).then(function(data) {
                            toastr[data['type']](data['msg']);

                            if (data['type'] === 'success') {
                                removeVariantRow(context);
                            }
                        });
                    } else {
                        removeVariantRow(context);
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
