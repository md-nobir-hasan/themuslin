@extends('backend.admin-master')

@section('site-title')
    {{ __('Navbar Category Dropdown Settings') }}
@endsection

@section('style')
    <x-niceselect.css />
@endsection

@section('content')
    @can('general-settings-navbar-category-dropdown')
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-lg-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title mb-4">{{ __('Navbar Category Dropdown Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.general.navbar.category.dropdown') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="product_id">{{ __('Select Categories') }}</label>
                                            <select id="product_id" name="navbar_categories[]" class="form-control wide"
                                                multiple>
                                                @php $selected_category_ids = array_keys($navbar_categories); @endphp
                                                @foreach ($all_categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        @if (in_array($category->id, $selected_category_ids)) selected @endif>
                                                        {{ $category->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <span
                                                class="d-block">{{ __('Save settings after selecting category to find more setting options') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($navbar_categories)
                                            <h5 class="mb-4">{{ __('Selected category options') }}</h5>
                                            <div id="accordion">
                                                @foreach ($navbar_categories as $category_id => $navbar_category)
                                                    <div class="card">
                                                        <div class="card-header" id="heading_{{ $category_id }}">
                                                            <h5 class="mb-0">
                                                                <button type="button" class="btn btn-link"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse_{{ $category_id }}"
                                                                    aria-expanded="@if ($loop->first) true @endif"
                                                                    aria-controls="collapse_{{ $category_id }}">
                                                                    {{ $all_categories->where('id', $category_id)->first()->title ?? '' }}
                                                                </button>
                                                            </h5>
                                                        </div>

                                                        <div id="collapse_{{ $category_id }}"
                                                            class="collapse @if ($loop->first) show @endif"
                                                            aria-labelledby="heading_{{ $category_id }}"
                                                            data-parent="#accordion">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="navbar_sub_categories_{{ $category_id }}">{{ __('Select Subcategory') }}</label>
                                                                            @php
                                                                                $subcategories = $all_sub_categories->where('category_id', $category_id);
                                                                                $selected_sub_category_arr = !empty($navbar_category['subcategories']) ? $navbar_category['subcategories'] : [];
                                                                            @endphp
                                                                            <select class="form-control wide"
                                                                                name="navbar_sub_categories[{{ $category_id }}][]"
                                                                                id="navbar_sub_categories_{{ $category_id }}"
                                                                                multiple>
                                                                                @foreach ($subcategories as $subcategory)
                                                                                    <option value="{{ $subcategory->id }}"
                                                                                        @if (in_array($subcategory->id, $selected_sub_category_arr)) selected @endif>
                                                                                        {{ $subcategory->title }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="navbar_sub_category_styles_{{ $category_id }}">{{ __('Select Dropdown Style') }}</label>
                                                                            <select class="form-control"
                                                                                name="navbar_sub_category_styles[{{ $category_id }}]"
                                                                                id="navbar_sub_category_styles_{{ $category_id }}">
                                                                                @php $selected_style = !empty($navbar_category['style']) ? $navbar_category['style'] : ''; @endphp
                                                                                <option value="list"
                                                                                    @if ('list' == $selected_style) selected @endif>
                                                                                    {{ __('List') }}</option>
                                                                                <option value="thumbnail"
                                                                                    @if ('thumbnail' == $selected_style) selected @endif>
                                                                                    {{ __('Thumbnail') }}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" id="update"
                                    class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <x-niceselect.js />
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('click', '#update', function() {
                    $(this).addClass("disabled")
                    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __('Updating') }}');
                });

                if ($('.nice-select').length > 0) {
                    $('.nice-select').niceSelect();
                }
            });
        }(jQuery));
    </script>
@endsection
