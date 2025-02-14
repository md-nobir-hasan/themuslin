<!DOCTYPE html>
<html class="no-js" lang="{{ get_default_language() }}" dir="{{ get_default_language_direction() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        {!! load_google_fonts() !!}
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

        @yield('style')

        <script>
            let siteurl = "{{ url('/') }}";
        </script>
        {!! get_static_option('site_third_party_tracking_code') !!}

        <style>
            .error-area-wrapper{
                height: 100vh;
            }
        </style>
    </head>
    <body>
        <div class="error-area-wrapper d-flex align-items-center justify-content-center">
            <div class="error-wrapper desktop-center">
                <div class="img-box">
                    {!! render_image_markup_by_attachment_id(get_static_option('error_404_page_image')) !!}
                </div>
                <div class="error-wrapper-contents content">
                    <h2 class="error-wrapper-title d-none">{{ get_static_option('error_404_page_title') }}</h2>
                    <h4 class="error-wrapper-error-subtitle mt-3">{{ __("Oops! This Page Could Not Be Found") }}</h4>
                    <p class="error-wrapper-error-para mt-3">
                        {{ __("Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily unavailable") }}
                    </p>
                    <div class="btn-wrapper mt-4 mt-lg-5">
                        <a href="{{ route('homepage') }}"
                           class="btn-default rounded-btn">{{ get_static_option('error_404_page_button_text') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
