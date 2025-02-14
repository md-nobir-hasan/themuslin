<?php
if (!isset($product)) {
    $product = null;
}

$taxClasses = $taxClasses ?? [];
?>

<div>
    <style>
        .dashboard-products-add .nav-pills .currency-tab.active {
            background: #05cd99 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(10px);
        }
        
        .currency-tab {
            color: #475569 !important;
            transition: all 0.2s ease-in-out;
            font-weight: 600;
            letter-spacing: 0.3px;
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            backdrop-filter: blur(10px);
            min-width: 120px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .currency-tab span {
            position: relative;
            z-index: 2;
        }
      
    </style>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link currency-tab position-relative px-4 py-2.5 rounded-pill me-3 active" 
                  id="pills-home-tab" 
                  data-bs-toggle="pill" 
                  data-bs-target="#pills-home" 
                  type="button" 
                  role="tab" 
                  aria-controls="pills-home" 
                  aria-selected="true">
            <span class="fw-medium">For BDT</span>
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link currency-tab position-relative px-4 py-2.5 rounded-pill" 
                  id="pills-profile-tab" 
                  data-bs-toggle="pill" 
                  data-bs-target="#pills-profile" 
                  type="button" 
                  role="tab" 
                  aria-controls="pills-profile" 
                  aria-selected="false">
            <span class="fw-medium">For USD</span>
          </button>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <!-- BD Price (BDT) -->
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="general-info-wrapper dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title"> {{ __('Price Manage') }} </h4>
                </div>
                <div class="general-info-form dashboard__card__body custom__form">
                    <div class="row g-3 mt-2">
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Base Cost') }} </label>
                                <input type="text" class="form--control radius-10" value="{{ $product?->getRawOriginal('cost') }}" name="cost"
                                    placeholder="{{ __('Base Cost...') }}">
                                <p>{{ __('Purchase price of this product.') }}</p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Regular Price') }} </label>
                                <input type="text" class="form--control radius-10" value="{{ $product?->getRawOriginal('price') }}" name="price"
                                    placeholder="{{ __('Enter Regular Price...') }}">
                                <small>{{ __('This price will display like this') }} <del>( {{ site_currency_symbol() }}
                                        10)</del></small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Sale Price') }} </label>
                                <input type="text" class="form--control radius-10" value="{{ $product?->getRawOriginal('sale_price') }}"
                                    name="sale_price" placeholder="{{ __('Enter Sale Price...') }}">
                                <small>{{ __('This will be your product selling price') }}</small>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Increasing % for USD price') }} </label>
                                <input type="number" step='1' min="0" class="form--control radius-10" value="{{ $product?->increase_percentage_usd }}"
                                    name="increase_percentage_usd" placeholder="{{ __('Enter Increasing %...') }}">
                                <small>{{ __('This will be the percentage of the price that will be added to the price of the product in USD') }}</small>
                            </div>
                        </div>

            
                        @if (get_static_option('tax_system') == 'advance_tax_system')
                            <!-- <div class="col-sm-6">
                                <div class="dashboard-input">
                                    <label class="dashboard-label color-light mb-2"> {{ __('Is Taxable') }} </label>
                                    <select class="form--control radius-10" name="is_taxable">
                                        <option value="">{{ __('Select is taxable') }}</option>
                                        <option {{ $product?->is_taxable == 1 ? 'selected' : '' }} value="1">
                                            {{ __('Taxable') }}</option>
                                        <option {{ $product?->is_taxable == 0 ? 'selected' : '' }} value="0">
                                            {{ __('Non-Taxable') }}</option>
                                    </select>
                                    <small>{{ __('If you designate your product as taxable, it implies that applicable taxes will be levied on the product.') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dashboard-input">
                                    <label class="dashboard-label color-light mb-2"> {{ __('Tax class') }} </label>
            
                                    <select class="form--control radius-10" name="tax_class_id">
                                        <option value="">{{ __('Select a tax class for this product') }}</option>
                                        @foreach ($taxClasses as $tax_class)
                                            <option {{ $product?->tax_class_id == $tax_class->id ? 'selected' : '' }}
                                                value="{{ $tax_class->id }}">{{ $tax_class->name }}</option>
                                        @endforeach
                                    </select>
            
                                    <small>{{ __('If you select taxable then you need to select tax class') }}</small>
                                </div>
                            </div> -->
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- US Price (USD) -->
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="general-info-wrapper dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title"> {{ __('Price Manage (USD)') }} </h4>
                </div>
                <div class="general-info-form dashboard__card__body custom__form">
                    <div class="row g-3 mt-2">
                        <!-- Base cost -->
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Base Cost (USD)') }} </label>
                                <input type="text" class="form--control radius-10" value="{{ $product?->cost_usd }}" name="cost_usd"
                                    placeholder="{{ __('Base Cost...') }}">
                                <p>{{ __('Purchase price of this product.') }}</p>
                            </div>
                        </div>

                        <!-- Regular price -->
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Regular Price (USD)') }} </label>
                                    <input type="text" class="form--control radius-10" value="{{ $product?->price_usd }}" name="price_usd"
                                    placeholder="{{ __('Enter Regular Price...') }}">
                                <small>{{ __('This price will display like this') }} <del>( {{ site_currency_symbol() }}
                                        10)</del></small>
                            </div>
                        </div>

                        <!-- Sale price -->
                        <div class="col-sm-12">
                            <div class="dashboard-input">
                                <label class="dashboard-label color-light mb-2"> {{ __('Sale Price (USD)') }} </label>
                                <input type="text" class="form--control radius-10" value="{{ $product?->sale_price_usd }}"
                                    name="sale_price_usd" placeholder="{{ __('Enter Sale Price...') }}">
                                <small>{{ __('This will be your product selling price') }}</small>
                            </div>
                        </div>
            
                        @if (get_static_option('tax_system') == 'advance_tax_system')
                            <!-- <div class="col-sm-6">
                                <div class="dashboard-input">
                                    <label class="dashboard-label color-light mb-2"> {{ __('Is Taxable') }} </label>
                                    <select class="form--control radius-10" name="is_taxable">
                                        <option value="">{{ __('Select is taxable') }}</option>
                                        <option {{ $product?->is_taxable == 1 ? 'selected' : '' }} value="1">
                                            {{ __('Taxable') }}</option>
                                        <option {{ $product?->is_taxable == 0 ? 'selected' : '' }} value="0">
                                            {{ __('Non-Taxable') }}</option>
                                    </select>
                                    <small>{{ __('If you designate your product as taxable, it implies that applicable taxes will be levied on the product.') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dashboard-input">
                                    <label class="dashboard-label color-light mb-2"> {{ __('Tax class') }} </label>
            
                                    <select class="form--control radius-10" name="tax_class_id">
                                        <option value="">{{ __('Select a tax class for this product') }}</option>
                                        @foreach ($taxClasses as $tax_class)
                                            <option {{ $product?->tax_class_id == $tax_class->id ? 'selected' : '' }}
                                                value="{{ $tax_class->id }}">{{ $tax_class->name }}</option>
                                        @endforeach
                                    </select>
            
                                    <small>{{ __('If you select taxable then you need to select tax class') }}</small>
                                </div>
                            </div> -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
      </div>

    
</div>


