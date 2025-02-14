@php
    if (!isset($product)) {
        $product = null;
    }
@endphp

<div class="general-info-wrapper dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title"> {{ __('Product Settings') }} </h4>
    </div>
    <div class="dashboard__card__body custom__form">
        <div class="row g-3 mt-2">

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="min_purchase">{{ __('Sort Order') }}</label>
                    <input name="sort_order" class="form--control"
                        value="{{ $product?->sort_order }}" placeholder="{{ __('Product Order') }}">
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="min_purchase">{{ __('Minimum quantity of Purchase') }}</label>
                    <input id="min_purchase" name="min_purchase" class="form--control"
                        value="{{ $product?->min_purchase }}" placeholder="{{ __('Minimum quantity of purchase') }}">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="max_purchase">{{ __('Maximum quantity of Purchase') }}</label>
                    <input id="max_purchase" name="max_purchase" class="form--control"
                        value="{{ $product?->max_purchase }}" placeholder="{{ __('Maximum quantity of Purchase') }}">
                </div>
            </div>
            <div class="col-sm-12">
                <!-- <div class="vendor-coupon-switch d-flex align-items-center mt-3">
                    <label for="coupon-switch4">{{ __('Refundable') }}</label>
                    <input name="is_refundable" class="custom-switch" type="checkbox" id="coupon-switch4"
                        {{ $product?->is_refundable ? 'checked' : '' }} />
                    <label class="switch-label" for="coupon-switch4">{{ __('Create') }}</label>
                </div> -->
            </div>
            <!-- <div class="col-sm-12">
                <div class="vendor-coupon-switch d-flex align-items-center">
                    <label for="coupon-switch5">{{ __('Inventory Warning') }}</label>
                    <input name="is_inventory_warning" class="custom-switch" type="checkbox" id="coupon-switch5"
                        {{ $product?->is_inventory_warn_able ? 'checked' : '' }} />
                    <label class="switch-label" for="coupon-switch5"></label>
                </div>
            </div> -->
        </div>
    </div>
</div>
