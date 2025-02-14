@extends('backend.admin-master')

@section('site-title')
    {{ __('Shipping Charge Settings') }}
@endsection

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
                        <h4 class="dashboard__card__title">{{ __('Shipping Charge Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.shipping-charge-settings') }}" method="post">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            {{ __('Select Shipping Charge Type') }}
                                            <select name="shipping_charge_type"
                                                class="form-control mt-2 shipping-charge-type">
                                                <option value="">{{ __('Select Type') }}</option>
                                                <option
                                                    {{ get_static_option('shipping_charge_type') == 'global' ? 'selected' : '' }}
                                                    value="global">{{ __('Global') }}</option>
                                                <option
                                                    {{ get_static_option('shipping_charge_type') == 'vendor' ? 'selected' : '' }}
                                                    value="vendor">{{ __('Vendor') }}</option>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group shipping-charge-wrapper"
                                        style="{{ get_static_option('shipping_charge_type') !== 'global' ? 'display: none' : '' }}">
                                        <label>
                                            {{ __('Select Shipping Charge Type') }}
                                            <input value="{{ get_static_option('global_shipping_charge_amount') }}"
                                                name="global_shipping_charge_amount" type="number" class="form-control" />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="cmn_btn btn_bg_profile">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on("change", ".shipping-charge-type", function() {
            // check value if value is global then open shipping-charge-wrapper if not then hide shipping-charge-wrapper
            if ($(this).val() === "global") {
                $(".shipping-charge-wrapper").fadeIn();
            } else {
                $(".shipping-charge-wrapper").fadeOut();
            }
        });
    </script>
@endsection
