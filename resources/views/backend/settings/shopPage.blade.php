@extends('backend.admin-master')
@section('site-title')
    {{ __('Shop Page Settings') }}
@endsection
@section('content')
    @can('page-settings-shop-page')
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-lg-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Shop Page Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.page.settings.shop.page') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label
                                                for="default_item_count">{{ __('Number of Products to show by default') }}</label>
                                            <input type="text" name="default_item_count" class="form-control"
                                                value="{{ get_static_option('default_item_count') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="shop_column_count">{{ __('Number of Columns') }}</label>
                                            <input type="text" name="shop_column_count" class="form-control"
                                                value="{{ get_static_option('shop_column_count') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-col">
                                            <label for="sidebar_visibility">{{ __('Sidebar Visibility') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="sidebar_visibility" name="sidebar_visibility"
                                                    @if (!empty(get_static_option('sidebar_visibility'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="sidebar_position">{{ __('Sidebar Position') }}</label>
                                            @php $sidebar_position = get_static_option('sidebar_position'); @endphp
                                            <select name="sidebar_position" id="sidebar_position" class="form-control">
                                                <option value="right" @if ($sidebar_position == 'right') selected @endif>
                                                    {{ __('Right') }}</option>
                                                <option value="left" @if ($sidebar_position == 'left') selected @endif>
                                                    {{ __('Left') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-4 mt-2">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="shop_product_search">{{ __('Search Product') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="shop_product_search" name="shop_product_search"
                                                    @if (!empty(get_static_option('shop_product_search'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="shop_filter_by_average_rating">{{ __('Filter by average rating') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="shop_filter_by_average_rating"
                                                    name="shop_filter_by_average_rating"
                                                    @if (!empty(get_static_option('shop_filter_by_average_rating'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="shop_filter_by_category">{{ __('Filter by category') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="shop_filter_by_category"
                                                    name="shop_filter_by_category"
                                                    @if (!empty(get_static_option('shop_filter_by_category'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="shop_filter_by_price">{{ __('Filter by price') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="shop_filter_by_price" name="shop_filter_by_price"
                                                    @if (!empty(get_static_option('shop_filter_by_price'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="shop_filter_by_tags">{{ __('Filter by tags') }}</label>
                                            <label class="switch">
                                                <input type="checkbox" id="shop_filter_by_tags" name="shop_filter_by_tags"
                                                    @if (!empty(get_static_option('shop_filter_by_tags'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <button class="cmn_btn btn_bg_profile">{{ __('Save Settings') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
