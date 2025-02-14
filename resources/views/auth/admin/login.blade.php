@extends('layouts.login-screens')


@section('content')
    <div class="login-area">
        <div class="container">
            <div class="login-box-wrapper ptb--100">
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="login-form-header text-center mb-4">
                        <div class="logo-wrapper" style="margin-bottom: 20px;">
                            <img src="{{ asset('assets/muslin/images/static/logo-black.svg') }}" alt="">
                        </div>
                        <h4 class="main-title center-text fw-500 mt-3">{{ __('Admin Login') }}</h4>
                        <p class="main-para mt-2">{{ __('Hello there, Sign in and start managing your website') }}</p>
                    </div>
                    @include('backend.partials.message')
                    <div class="error-message"></div>
                    <div class="login-form-wrap mt-4">
                        <div class="dashboard-input">
                            <label for="username" class="dashboard-label">{{ __('Username') }}</label>
                            <input type="text" class="form--control" id="username" name="username">
                        </div>

                        <div class="dashboard-input mt-4">
                            <label for="password" class="dashboard-label">{{ __('Password') }}</label>
                            <input type="password" class="form--control" id="password" name="password">
                        </div>
                        <div class="row mb-4 rmber-area mt-4">
                            <div class="col-6">
                                <div class="dashboard-checkbox">
                                    <input type="checkbox" name="remember" class="check-input" id="remember">
                                    <label class="checkbox-label" for="remember">{{ __('Remember Me') }}</label>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('admin.forget.password') }}"
                                    class="forgot-password">{{ __('Forgot Password?') }}</a>
                            </div>
                        </div>
                        <div class="dashboard-btn-wrapper mt-4">
                            <button id="form_submit" type="submit"
                                class="btn-submit dashboard-bg w-100">{{ __('Login') }}</button>
                        </div>
                        @if (preg_match('/(xgenious)/', url('/')))
                            <div class="adminlogin-info">
                                <table class="table-default">
                                    <th>{{ __('Username') }}</th>
                                    <th>{{ __('Password') }}</th>
                                    <tbody>
                                        <tr>
                                            <td>super_admin</td>
                                            <td>12345678</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function($) {

                $(document).on('click', '#form_submit', function(e) {
                    e.preventDefault();
                    var el = $(this);
                    var erContainer = $(".error-message");
                    erContainer.html('');
                    el.text('{{ __('Please Wait..') }}');
                    $.ajax({
                        url: "{{ route('admin.login') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            username: $('#username').val(),
                            password: $('#password').val(),
                            remember: $('#remember').val(),
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            erContainer.html('<div class="alert alert-danger"></div>');
                            $.each(errors.errors, function(index, value) {
                                erContainer.find('.alert.alert-danger').append(
                                    '<p>' + value + '</p>');
                            });
                            el.text('{{ __('Login') }}');
                        },
                        success: function(data) {
                            $('.alert.alert-danger').remove();
                            if (data.status == 'ok') {
                                el.text('{{ __('Redirecting') }}..');
                                erContainer.html('<div class="alert alert-' + data.type +
                                    '">' + data.msg + '</div>');
                                location.reload();
                            } else {
                                erContainer.html('<div class="alert alert-' + data.type +
                                    '">' + data.msg + '</div>');
                                el.text('{{ __('Login') }}');
                            }
                        }
                    });
                });

            });
        })(jQuery);
    </script>
@endsection
