@extends('backend.admin-master')

@section('site-title', __('Vendor settings page'))

@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Vendor Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        <form action="{{ route('admin.vendor.settings') }}" class="action" method="post">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="row">
                                    <span class="col-md-2">
                                        {{ __('Enable vendor registration') }}
                                    </span>

                                    <span class="col-md-9">
                                        <input type="checkbox"
                                            {{ get_static_option('enable_vendor_registration') == 'on' ? 'checked' : '' }}
                                            class="form--checkbox-1" name="enable_vendor_registration" />
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="row">
                                    <span class="col-md-2">
                                        {{ __('Disable vendor email verification') }}
                                    </span>
                                    <span class="col-md-9">
                                        <input type="checkbox"
                                            {{ get_static_option('disable_vendor_email_verify') == 'on' ? 'checked' : '' }}
                                            class="form--checkbox-1" name="disable_vendor_email_verify" />
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="row">
                                    <span class="col-md-2">
                                        {{ __('Vendor order items list item limit') }}
                                    </span>

                                    <span class="col-md-9">
                                        <input value="{{ get_static_option('order_vendor_list') }}" type="number"
                                           {{ get_static_option('order_vendor_list') == 'on' ? 'checked' : '' }}
                                           class="form-control w-50" name="order_vendor_list"
                                               placeholder="{{ __("Order vendor list limit") }}..." />
                                    </span>
                                </label>
                            </div>


                            <div class="form-group">
                                <label class="row">
                                    <span class="col-md-2">
                                        {{ __("Firebase Server key") }}
                                    </span>
                                    <span class="col-md-9">
                                        <input type="text" name="vendor_firebase_server_key"
                                           value="{{ get_static_option("vendor_firebase_server_key") }}"
                                               placeholder="{{ __("Vendor firebase server key....") }}"
                                           class="form-control w-50"
                                        />
                                    </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="cmn_btn btn_bg_profile">{{ __('Update vendor settings') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
