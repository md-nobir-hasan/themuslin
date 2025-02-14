@extends('backend.admin-master')
@section('site-title')
    {{ __('Currency Rate') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">

                    <div class="dashboard__card__body custom__form mt-4">
                        @can('currency-rate')
                            <form action="{{ route('admin.currency-rate.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="currency_code" value="USD">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="cost">{{ __('BDT to USD rate') }}</label>
                                            <input type="number" step="1" id="cost" name="rate" class="form-control"
                                                placeholder="{{ __('BDT to USD rate') }}" value="{{ $currency_rate->rate }}">

                                            @error('rate')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <button type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            $(document).ready(function() {
                $('#setting_preset').on('change', function() {
                    let presets = ['min_order', 'min_order_or_coupon', 'min_order_and_coupon'];
                    let selected_value = $('#setting_preset').val();
                    let allOptions = ['min_order_or_coupon', 'min_order_and_coupon'];

                    if (presets.includes(selected_value)) {
                        $('#minimum_order_amount').parent().fadeIn();
                        if (allOptions.includes(selected_value)) {
                            $('#coupon_code').parent().fadeIn();
                        } else {
                            $('#coupon_code').parent().fadeOut();
                        }
                    } else {
                        $('#minimum_order_amount').parent().fadeOut();
                        $('#coupon_code').parent().fadeOut();
                    }
                });
            });
        })(jQuery)
    </script>
@endsection
