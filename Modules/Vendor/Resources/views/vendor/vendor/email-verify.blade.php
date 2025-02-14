@extends('frontend.frontend-master')

@section('site-title')
    {{ __('Verify your account') }}
@endsection

@section('content')
    <section class="sign-in-area-wrapper padding-top-100 padding-bottom-50">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-6 py-5">
                    <div class="sign-in register">
                        <h4 class="single-title">{{ __('Verify Your Account') }}</h4>
                        <div class="form-wrapper custom__form mt-4">
                            <div class="error-container">
                                <x-msg.error />
                                <x-msg.success />
                            </div>
                            <form action="{{ route('vendor.email.verify') }}" method="post" enctype="multipart/form-data"
                                class="register-form verify-mail">
                                @csrf

                                <div class="form-group">
                                    <input type="text" name="verification_code" class="form-control"
                                        placeholder="{{ __('Verify Code') }}">
                                </div>

                                <div class="btn-wrapper">
                                    <button type="submit"
                                        class="btn-info rounded-btn btn">{{ __('Verify Email') }}</button>
                                </div>

                                <div class="btn-pair btn-wrapper btn-top">
                                    <div class="text-center mt-3">
                                        <a href="{{ route('vendor.resend.verify.mail') }}"
                                            class="forgot-btn btn btn-link">{{ __('Send verify code again?') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
