@extends('backend.admin-master')
@section('style')
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Wishlist Page Settings') }}
@endsection
@section('content')
    @can('page-settings-wishlist-page')
        <div class="col-lg-12 col-ml-12">
            <div class="row g-4">
                <div class="col-lg-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Wishlist Page Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.page.settings.wishlist') }}" method="POST">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="empty_wishlist_text">{{ __('Empty Wishlist Text') }}</label>
                                            <input type="text" class="form-control" id="empty_wishlist_text"
                                                name="empty_wishlist_text"
                                                value="{{ get_static_option('empty_wishlist_text') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <x-media-upload :oldimage="get_static_option('empty_wishlist_image')" :name="'empty_wishlist_image'" :dimentions="'465X465'"
                                                :title="__('Empty Wishlist Image')" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="send_to_cart_text">{{ __('Send To Cart Text') }}</label>
                                            <input type="text" class="form-control" id="send_to_cart_text"
                                                name="send_to_cart_text" value="{{ get_static_option('send_to_cart_text') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="clear_wishlist_text">{{ __('Clear Wishlist Text') }}</label>
                                            <input type="text" class="form-control" id="clear_wishlist_text"
                                                name="clear_wishlist_text"
                                                value="{{ get_static_option('clear_wishlist_text') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button class="cmn_btn btn_bg_profile">{{ __('Save Settings') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup />
    @endcan
@endsection
@section('script')
    <x-media.js />
@endsection
