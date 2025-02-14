@extends('frontend.frontend-master')

@section('content')
    <div class="breadcrumb-area breadcrumb-padding bg-item-badge">
        <div class="breadcrumb-shapes">
            <img src="{{ asset('assets/img/shop/badge-s1.png') }}" alt="">
            <img src="{{ asset('assets/img/shop/badge-s2.png') }}" alt="">
            <img src="{{ asset('assets/img/shop/badge-s3.png') }}" alt="">
        </div>
        <div class="container container-one">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-contents">
                        <h2 class="breadcrumb-title"> {{ __('Vendor Registration') }} </h2>
                        <ul class="breadcrumb-list">
                            <li class="list"> <a href="{{ route('homepage') }}"> {{ __('Home') }} </a> </li>
                            <li class="list"> {{ __('Registration') }} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb area end -->

    <!-- Vendor Registration area Starts -->
    <section class="vendor-registration-area padding-top-100 padding-bottom-100">
        <div class="container container-one">
            <div class="row justify-content-center flex-lg-row flex-column-reverse">
                <div class="col-lg-5">
                    <x-error-msg />
                    <x-msg.success />

                    <form action="{{ route('vendor.vendor_registration') }}" method="post">
                        @csrf
                        <div class="dashboard__card mt-4">
                            <h4 class="dashboard__card__title"> {{ __('Basic Info*') }} </h4>
                            <div class="dashboard__card__body custom__form mt-4 single-reg-form">
                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Owner Name') }} </label>
                                    <input value="{{ old('owner_name') }}" name="owner_name" type="text"
                                        class="form--control radius-10" placeholder="{{ __('Owner Name') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Business Name') }} </label>
                                    <input value="{{ old('business_name') }}" name="business_name" type="text"
                                        class="form--control radius-10" placeholder="{{ __('Business Name') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Email') }} </label>
                                    <input value="{{ old('email') }}" name="email" type="text"
                                        class="form--control radius-10" placeholder="{{ __('Email') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Username') }} </label>
                                    <input value="{{ old('username') }}" name="username" type="text"
                                        class="form--control radius-10" placeholder="{{ __('Username') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Password') }} </label>
                                    <input value="{{ old('password') }}" name="password" type="text"
                                        class="form--control radius-10" placeholder="{{ __('Password') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Confirm Password') }} </label>
                                    <input name="password_confirmation" type="text" class="form--control radius-10"
                                        placeholder="{{ __('Confirm Password') }}">
                                </div>

                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Business Category') }} </label>
                                    <div class="nice-select-two">
                                        <select name="business_type" class="form-control">
                                            <option value="">{{ __('Select business type') }}</option>
                                            @foreach ($business_type as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label-title color-light mb-2"> {{ __('Description') }} </label>
                                    <textarea name="message" class="form--control form--message radius-10">{{ old('message') }}{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="box-wrap form-check">
                                <div class="left">
                                    <input type="checkbox" class="form-check-input" id="toc_and_privacy"
                                           name="agree_terms" required>
                                    <label class="form-check-label" for="toc_and_privacy">
                                        {{ __('Accept all') }}
                                        <a href="{{ url(get_static_option('toc_page_link')) }}"
                                           class="text-active">{{ __('Terms and Conditions') }}</a> &amp;
                                        <a href="{{ url(get_static_option('privacy_policy_link')) }}"
                                           class="text-active">{{ __('Privacy Policy') }}</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="vendor-btn-submit">
                            <div class="btn-wrapper">
                                <button type="submit" class="btn-submit w-100 radius-5 dashboard-bg">
                                    {{ __('Submit For Registration') }} </button>
                            </div>
                            
                            <div class="already-have-account account-bottom justify-content-center align-items-center mt-3">
                                <div class="d-flex gap-2">
                                    <p>{{ __("Already have account?") }}</p>
                                    <a href="{{ route("vendor.login") }}" class="signup-login">{{ __("Login") }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
