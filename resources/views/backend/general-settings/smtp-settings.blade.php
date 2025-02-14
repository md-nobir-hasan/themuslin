@extends('backend.admin-master')
@section('site-title')
    {{ __('SMTP Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12 mt-2">
                @include('backend.partials.message')
                <x-msg.error />
            </div>
            <div class="col-6">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('SMTP Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.smtp.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_mailer">{{ __('SMTP Mailer') }}</label>
                                        <select name="site_smtp_mail_mailer" class="form-control">
                                            <option value="smtp" @if (get_static_option('site_smtp_mail_mailer') == 'smtp') selected @endif>
                                                {{ __('SMTP') }}</option>
                                            <option value="sendmail" @if (get_static_option('site_smtp_mail_mailer') == 'sendmail') selected @endif>
                                                {{ __('SendMail') }}</option>
                                            <option value="mailgun" @if (get_static_option('site_smtp_mail_mailer') == 'mailgun') selected @endif>
                                                {{ __('Mailgun') }}</option>
                                            <option value="postmark" @if (get_static_option('site_smtp_mail_mailer') == 'postmark') selected @endif>
                                                {{ __('Postmark') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_host">{{ __('SMTP Mail Host') }}</label>
                                        <input type="text" name="site_smtp_mail_host" class="form-control"
                                            value="{{ get_static_option('site_smtp_mail_host') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_port">{{ __('SMTP Mail Port') }}</label>
                                        <select name="site_smtp_mail_port" class="form-control">
                                            <option value="587" @if (get_static_option('site_smtp_mail_port') == '587') selected @endif>
                                                {{ __('587') }}</option>
                                            <option value="465" @if (get_static_option('site_smtp_mail_port') == '465') selected @endif>
                                                {{ __('465') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_username">{{ __('SMTP Mail Username') }}</label>
                                        <input type="text" name="site_smtp_mail_username" class="form-control"
                                            value="{{ get_static_option('site_smtp_mail_username') }}"
                                            id="site_smtp_mail_username">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_password">{{ __('SMTP Mail Password') }}</label>
                                        <input type="password" name="site_smtp_mail_password" class="form-control"
                                            value="{{ get_static_option('site_smtp_mail_password') }}"
                                            id="site_smtp_mail_password">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_smtp_mail_encryption">{{ __('SMTP Mail Encryption') }}</label>
                                        <select name="site_smtp_mail_encryption" class="form-control">
                                            <option value="ssl" @if (get_static_option('site_smtp_mail_encryption') == 'ssl') selected @endif>
                                                {{ __('SSL') }}</option>
                                            <option value="tls" @if (get_static_option('site_smtp_mail_encryption') == 'tls') selected @endif>
                                                {{ __('TLS') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit"
                                        class="cmn_btn btn_bg_profile">{{ __('Update SMTP Settings') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('SMTP Test') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.smtp.settings.test') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="email">{{ __('Email') }}</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="subject">{{ __('Subject') }}</label>
                                        <input type="text" name="subject" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="message">{{ __('Message') }}</label>
                                        <textarea name="message" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Send Mail') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
