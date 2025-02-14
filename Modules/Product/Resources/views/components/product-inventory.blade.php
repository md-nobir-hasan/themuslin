@php
    if (!isset($inventory)) {
        $inventory = null;
    }

    if (!isset($uom)) {
        $uom = null;
    }
@endphp
<div class="dashboard__card">
    <div class="dashboard__card__header">
        <h4 class="dashboard__card__title">{{ __('Product Inventory') }}</h4>
    </div>
    <div class="dashboard__card__body custom__form mt-4">
        @if (isset($inventoryPage))
            <div class="row">
        @endif
        <div class="@if (isset($inventoryPage)) col-md-4 @else dashboard-input @endif">
            <label class="dashboard-label"> {{ __('Sku') }} </label>
            <input type="text" class="form--control radius-10" name="sku" value="{{ $inventory?->sku }}">
            <small class="mt-2 mb-0 d-block">{{ __('Custom Unique Code for this product.') }}</small>
        </div>

        <div class="@if (isset($inventoryPage)) col-md-4 @else dashboard-input @endif">
            <label class="dashboard-label"> {{ __('Quantity') }} </label>
            <input type="tel" class="form--control radius-10" name="quantity"
                value="{{ $inventory?->stock_count }}">
            <small
                class="mt-2 mb-0 d-block">{{ __('This will be replaced with the sum of inventory items. if any inventory  item is registered..') }}
            </small>
        </div>

        <div class="@if (isset($inventoryPage)) col-md-2 @else dashboard-input @endif">
            <label class="dashboard-label"> {{ __('Unit') }} </label>

            <div class="nice-select-two">
                <select class="select2 form-control" name="unit_id">
                    <option value="">{{ __('Select Unit') }}</option>
                    @foreach ($units as $unit)
                        <option {{ $unit->id === $uom?->unit_id ? 'selected' : '' }} value="{{ $unit->id }}">
                            {{ $unit->name }}</option>
                    @endforeach
                </select>
                <small class="mt-2 mb-0 d-block">{{ __('Select Unit') }}</small>
            </div>
        </div>

        <div class="@if (isset($inventoryPage)) col-md-2 @else dashboard-input @endif">
            <label class="dashboard-label"> {{ __('Unit Of Measurement') }} </label>
            <input type="number" name="uom" class="form--control radius-10" value="{{ $uom?->quantity }}"
                placeholder="{{ __('Enter Unit Of Measurement') }}">
            <small class="mt-2 mb-0 d-block">{{ __('Enter the number here') }}</small>
        </div>
        @if (isset($inventoryPage))
    </div>
    @endif
</div>
</div>
