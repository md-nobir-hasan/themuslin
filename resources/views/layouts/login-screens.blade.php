<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ __('Admin Login') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/muslin/images/static/fav.png') }}"
        type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/common/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/themify-icons.css') }}">
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/responsive.css') }}">


    <style>
        .adminlogin-info {
            margin-top: 40px;
            display: block;
            width: 100%;
        }

        .adminlogin-info table {
            width: 100%;
        }

        .adminlogin-info table th,
        .adminlogin-info table td {
            font-size: 14px;
            font-weight: 700;
            padding: 10px;
        }

        /* New Form style */
        .login-box-wrapper form {
            margin: auto;
            width: 450px;
            max-width: 100%;
            background: #fff;
            border-radius: 3px;
            padding: 30px;
        }
        @media screen and (max-width: 375px) {
            .login-box-wrapper form {
                padding: 20px;
            }
        }

        .login-form-header .logo-wrapper {
            max-width: 220px;
            margin-inline: auto
        }

        .main-title {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
            color: #111;
        }

        .main-para {
            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            color: #777;
        }

        .dashboard-input {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .dashboard-label {
            font-size: 14px;
            font-weight: 400;
            line-height: 20px;
            color: var(--heading-color);
            margin-bottom: 8px;
        }

        .dashboard-input .form--control {
            font-size: 15px;
            width: 100%;
            height: 50px;
            border: 1px solid rgba(221, 221, 221, 0.4);
            -webkit-box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
            font-size: 15px;
            padding: 0 15px;
        }

        .dashboard-input .form--control:focus {
            -webkit-box-shadow: 0 0 10px rgba(5, 205, 153, 0.1);
            box-shadow: 0 0 10px rgba(5, 205, 153, 0.1);
            border-color: rgba(5, 205, 153, 0.3);
        }

        .dashboard_checkbox,
        .dashboard-checkbox {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            cursor: pointer;
            gap: 10px;
        }

        .dashboard_checkbox .check_input,
        .dashboard-checkbox .check-input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            min-height: 18px;
            min-width: 18px;
            cursor: pointer;
            background: #fff;
            border: 1px solid #dddddd;
            border-radius: 0px;
            margin-top: 3px;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        .dashboard_checkbox .checkbox_label,
        .dashboard-checkbox .checkbox-label {
            cursor: pointer;
            text-align: left;
            line-height: 26px;
            font-size: 16px;
            font-weight: 400;
            color: var(--heading-color);
            margin: 0;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
        }

        .dashboard_checkbox .check_input::after,
        .dashboard-checkbox .check-input::after {
            content: "";
            font-family: "Line Awesome Free";
            font-weight: 900;
            font-size: 10px;
            color: #fff;
            visibility: hidden;
            opacity: 0;
            -webkit-transform: scale(1.6) rotate(90deg);
            transform: scale(1.6) rotate(90deg);
            -webkit-transition: all 0.2s;
            transition: all 0.2s;
        }

        .dashboard_checkbox .check_input:checked,
        .dashboard-checkbox .check-input:checked {
            background: #05cd99;
            border-color: #05cd99;
            background: #05cd99;
        }

        .dashboard_checkbox .check_input:checked::after,
        .dashboard-checkbox .check-input:checked::after {
            visibility: visible;
            opacity: 1;
            -webkit-transform: scale(1.2) rotate(0deg);
            transform: scale(1.2) rotate(0deg);
        }

        .dashboard-btn-wrapper .btn-submit {
            font-size: 18px;
            font-weight: 500;
            padding: 12px 35px;
            display: inline-block;
            text-align: center;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            background: #05cd99;
            color: var(--white);
            border: 2px solid #05cd99;
        }

        .btn-submit:hover {
            background: none;
            color: #05cd99;
        }

        .forgot-password {
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
            color: #777;
            transition: all .3s
        }

        html,body,:root {
            height: 100%;
            width: 100%;
        }

        .forgot-password:hover {
            color: #05cd99;
        }

        .login-area{
            height: inherit;
        }
        .login-box-wrapper {
            height: 100vh;
            overflow-y: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    @yield('content')

    <!-- jquery latest version -->
    <script src="{{ asset('assets/common/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/jquery-migrate-3.3.2.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('assets/common/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/jquery.slicknav.min.js') }}"></script>

    <!-- others plugins -->
    <script src="{{ asset('assets/backend/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/backend/js/scripts.js') }}"></script>
    @yield('scripts')
    @yield('script')
</body>

</html>
