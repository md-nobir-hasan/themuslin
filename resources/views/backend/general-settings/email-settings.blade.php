@extends('backend.admin-master')
@section('site-title')
    {{ __('Email Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Email Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <form action="{{ route('admin.general.email.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="contact_mail_success_message">{{ __('Contact Mail Success Message') }}</label>
                                <input type="text" name="contact_mail_success_message" class="form-control"
                                    value="{{ get_static_option('contact_mail_success_message') }}"
                                    id="contact_mail_success_message">
                                <small
                                    class="form-text text-muted">{{ __('this message will show when any one contact you via contact page form.') }}</small>
                            </div>
                            <div class="form-group">
                                <label
                                    for="get_in_touch_mail_success_message">{{ __('Get In Touch Form Success Message') }}</label>
                                <input type="text" name="get_in_touch_mail_success_message" class="form-control"
                                    value="{{ get_static_option('get_in_touch_mail_success_message') }}"
                                    id="get_in_touch_mail_success_message">
                                <small
                                    class="form-text text-muted">{{ __('this message will show when any one contact you via get in touch form.') }}</small>
                            </div>

                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
