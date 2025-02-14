<div class="d-flex justify-content-between">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button"
                role="tab" aria-controls="basic-info" aria-selected="true">
                {{ __('Basic') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#address" type="button"
                role="tab" aria-controls="address" aria-selected="false">
                {{ __('Address') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#shop-info" type="button"
                role="tab" aria-controls="shop-info" aria-selected="false">
                {{ __('Shop Info') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bank-info" type="button"
                role="tab" aria-controls="bank-info" aria-selected="false">{{ __('Bank Info') }}
            </button>
        </li>
    </ul>
</div>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="home-tab">
        <div class="row">
            <div class="col-lg-6">
                <div class="dashboard__card mt-4">
                    <h4 class="dashboard__card__title"> {{ __('Basic Info*') }} </h4>
                    <div class="dashboard__card__body custom__form mt-4 single-reg-form">

                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Owner Name') }} </label>
                            <input disabled name="owner_name" type="text" class="form--control radius-10"
                                value="{{ $vendor->owner_name }}">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Business Name') }} </label>
                            <input disabled name="business_name" type="text" class="form--control radius-10"
                                value="{{ $vendor->business_name }}">
                        </div>

                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Username') }}</label>
                            <input disabled name="username" type="text" class="form--control radius-10"
                                value="{{ $vendor->username }}">
                        </div>

                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Business') }}
                                Category </label>
                            <div class="nice-select-two">
                                <input class="form-control form-control-sm" disabled
                                    value="{{ $vendor?->business_type?->name }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Description') }} </label>
                            <textarea disabled name="description" class="form--control form--message radius-10" style="height: 100px">{{ $vendor->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ __('Logo') }}</label>
                    <div class="w-100">
                        {!! \App\Http\Services\Media::render_image($vendor?->vendor_shop_info?->logo, size: 'full') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ __('Cover Photo') }}</label>
                    <div class="w-100">
                        {!! \App\Http\Services\Media::render_image($vendor?->vendor_shop_info?->cover_photo, size: 'full') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card mt-4">
                    <h4 class="dashboard__card__title"> {{ __('Address') }} </h4>
                    <div class="dashboard__card__body custom__form mt-4 single-reg-form">

                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Country') }} </label>
                            <div class="nice-select-two country_wrapper">
                                <input class="form-control form-control-sm" disabled
                                    value="{{ $vendor?->vendor_address?->country?->name }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('State') }} </label>
                            <div class="nice-select-two state_wrapper">
                                <input class="form-control form-control-sm" disabled
                                    value="{{ $vendor?->vendor_address?->state?->name }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('City') }} </label>
                            <div class="nice-select-two city_wrapper">
                                <input class="form-control form-control-sm" disabled
                                    value="{{ $vendor?->vendor_address?->city?->name }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Zip Code') }} </label>
                            <input type="text" name="zip_code" disabled class="form--control radius-10"
                                value="{{ $vendor?->vendor_address?->zip_code }}">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Address') }} </label>
                            <input name="address" disabled type="text" class="form--control radius-10"
                                value="{{ $vendor?->vendor_address?->address }}">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="shop-info" role="tabpanel" aria-labelledby="contact-tab">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card mt-4">
                    <h4 class="dashboard__card__title"> {{ __('Shop Info') }} </h4>
                    <div class="dashboard__card__body custom__form mt-4 single-reg-form">
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Location') }} </label>
                            <input disabled value="{{ $vendor?->vendor_shop_info?->location }}" name="location"
                                type="url" class="form--control radius-10" placeholder="Set Location From Map">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Number') }} </label>
                            <input disabled value="{{ $vendor?->vendor_shop_info?->number }}" name="number"
                                type="tel" class="form--control radius-10" placeholder="Type Number">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Email Address') }} </label>
                            <input disabled value="{{ $vendor?->vendor_shop_info?->email }}" type="text"
                                name="email" class="form--control radius-10" placeholder="Type Email">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Facebook Link') }} </label>
                            <input disabled value="{{ $vendor?->vendor_shop_info?->facebook_url }}" type="url"
                                name="facebook_url" class="form--control radius-10" placeholder="Type Facebook Link">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Website') }} </label>
                            <input disabled value="{{ $vendor?->vendor_shop_info?->website_url }}" type="url"
                                name="website_url" class="form--control radius-10" placeholder="Type Website">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="bank-info" role="tabpanel" aria-labelledby="contact-tab">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card mt-4">
                    <h4 class="dashboard__card__title"> {{ __('Bank Info') }} </h4>
                    <div class="dashboard__card__body custom__form mt-4 single-reg-form">
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Name') }} </label>
                            <input disabled value="{{ $vendor?->vendor_bank_info?->bank_name }}" name="bank_name"
                                type="text" class="form--control radius-10" placeholder="Type Name">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Email') }} </label>
                            <input disabled value="{{ $vendor?->vendor_bank_info?->bank_email }}" name="bank_email"
                                type="text" class="form--control radius-10" placeholder="Type Email">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Bank Code') }} </label>
                            <input disabled value="{{ $vendor?->vendor_bank_info?->bank_code }}" name="bank_code"
                                type="tel" class="form--control radius-10" placeholder="Type Code">
                        </div>
                        <div class="form-group">
                            <label class="label-title color-light mb-2"> {{ __('Account Number') }} </label>
                            <input disabled value="{{ $vendor?->vendor_bank_info?->account_number }}"
                                name="account_number" type="tel" class="form--control radius-10"
                                placeholder="Type Account Number">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
