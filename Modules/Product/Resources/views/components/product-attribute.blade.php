@php
    if (!isset($inventorydetails)) {
        $inventorydetails = [];
    }
@endphp
<div class="dashboard__card mt-4">
    <div class="dashboard__card__header">
        <div class="dashboard__card__header__left">
            <h5 class="dashboard__card__title">{{ __('Custom Inventory variant') }}</h5>
            <p class="dashboard__card__para mt-3">
                {{ __('Inventory will be variant of this product.') }}
            </p>
            <p class="dashboard__card__para mt-1">
                {{ __('All inventory stock count will be merged and replace to main stock of this product.') }}
            </p>
            <p class="dashboard__card__para mt-1">
                {{ __('Stock count filed is required.') }}
            </p>
        </div>
    </div>
    <div class="dashboard__card__body mt-4">
        <div class="inventory_items_container">
            @forelse($inventorydetails as $key => $detail)
                <x-product::variant-info.repeater :detail="$detail" :is-first="false" :colors="$colors"
                    :sizes="$sizes" :all-available-attributes="$allAttributes" :key="$key" />
            @empty
                <x-product::variant-info.repeater :is-first="true" :colors="$colors" :sizes="$sizes"
                    :all-available-attributes="$allAttributes" />
            @endforelse
        </div>
    </div>
</div>
