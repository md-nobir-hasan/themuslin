@php
    if (!isset($selectedDeliveryOption)) {
        $selectedDeliveryOption = [];
    }
@endphp

<div class="general-info-wrapper dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title"> {{ __('Delivery Options') }} </h4>
    </div>
    <div class="general-info-form dashboard__card__body">
        <div class="row g-3 mt-2">
            <div class="col-sm-12">
                <div class="d-flex flex-wrap gap-3 justify-content-start">
                    <input type="hidden" value="{{ implode(' , ', $selectedDeliveryOption) }}" name="delivery_option"
                        class="delivery-option-input" />

                    @foreach ($deliveryOptions as $deliveryOption)
                        <div class="delivery-item d-flex {{ in_array($deliveryOption->id, $selectedDeliveryOption) ? 'active' : '' }}"
                            data-delivery-option-id="{{ $deliveryOption->id }}">
                            <div class="icon">
                                <i class="{{ $deliveryOption->icon }}"></i>
                            </div>
                            <div class="content">
                                <h6 class="title">{{ $deliveryOption->title }}</h6>
                                <p>{{ $deliveryOption->sub_title }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
