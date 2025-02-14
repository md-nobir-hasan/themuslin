<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>
            The Muslin - Admin Panel
        </title>
        
        <link rel="shortcut icon" href="{{ asset('assets/muslin/images/static/fav.png') }}"/>

        <!-- favicon -->
        <!-- <link rel=icon href="{{ asset('assets/favicon-dashboard.png') }}" sizes="16x16" type="icon/png"> -->
        <!-- bootstrap -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap5.min.css') }}">
        <!-- animate -->
        <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
        <!-- slick carousel  -->
        <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
        <!-- LineAwesome -->
        <link rel="stylesheet" href="{{ asset('assets/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
        <link href="{{ asset('assets/backend/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
        <!-- Plugins css -->
        <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
        <!-- Main Stylesheet -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="{{ asset('assets/backend/css/custom-style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/backend/css/toastr.css') }}">
        <script>
            window.appUrl = "{{ url('/') }}";
        </script>
        <style>
            :root {
                --app-url: "{{ url('/') }}"
            }

            .note-codable { 
                min-height: 300px !important; 
            }
            
        </style>

        @yield('style')
        @yield('pwa-header')
    </head>

    <body>
        <!-- Dashboard area Starts -->
        <div class="body-overlay"></div>

        <div class="dashboard-area dashboard-padding">
            <div class="container-fluid p-0">
                <div class="dashboard-contents-wrapper">
                    <div class="dashboard-icon">
                        <div class="sidebar-icon">
                            <i class="las la-bars"></i>
                        </div>
                    </div>
                    @include('layouts.backend.sidebar')
                    <div class="dashboard-right-contents mt-4 mt-lg-0">
                        @include('layouts.backend.top-header')
                        <div class="wrapper-container">
                            @yield('content')
                        </div>
                        <div class="wrapper-container d-flex justify-content-between py-3 mt-3 bg-white px-4 radius-5">
                            <div class="copyright-block">
                                Developed by <a href="https://dcastalia.com/" target="_blank">Dcastalia</a> 
                            </div>
                            <div class="version-code-wrapper">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard area end -->

        <!-- jquery -->
        <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
        <!-- jquery Migrate -->
        <script src="{{ asset('assets/js/jquery-migrate.min.js') }}"></script>
        <!-- bootstrap -->
        <script src="{{ asset('assets/js/bootstrap5.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/backend/js/sweetalert2.js') }}"></script>
        <script src="{{ asset('assets/backend/js/toastr.min.js') }}"></script>
        <!-- Lazy Load Js -->
        <script src="{{ asset('assets/js/jquery.lazy.min.js') }}"></script>
        <!-- Slick Slider -->
        <script src="{{ asset('assets/js/slick.js') }}"></script>
        <!-- All Plugins js -->
        <script src="{{ asset('assets/js/plugins.js') }}"></script>
        <!-- Range Slider -->
        <script src="{{ asset('assets/js/nouislider-8.5.1.min.js') }}"></script>
        <!-- All Plugins two js -->
        <script src="{{ asset('assets/js/plugin-two.js') }}"></script>
        <!-- Nice Scroll -->
        <script src="{{ asset('assets/js/jquery.nicescroll.min.js') }}"></script>
        <!-- Calendar js -->
        <script src="{{ asset('assets/js/calendar-bundle.js') }}"></script>
        <!-- Chart Js -->
        <script src="{{ asset('assets/js/chart.js') }}"></script>
        <script src="{{ asset('assets/backend/js/fontawesome-iconpicker.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>
        <!-- main js -->
        <script src="{{ asset('assets/js/main.js') }}"></script>

        {!! Toastr::message() !!}

        <!-- Javascript Helpers -->
        <script src="{{ asset('assets/js/helpers.js') }}"></script>
        <!-- Javascript Helpers -->
        <script src="{{ asset('assets/frontend/js/jquery-ui.js') }}"></script>

        <x-notification.js />

        <script>
            $(document).on('click', '.swal_delete_button', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{ __('Are you sure?') }}',
                    text: '{{ __('You would not be able to revert this item!') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });

            // Calendar js
            // https://www.cssscript.com/demo/event-calendar-color

            if($('#custom-color-calendar').length > 0){
                new Calendar({
                    id: '#custom-color-calendar',
                })
            }

            function convertToSlug(text) {
                return text
                    .toLowerCase()
                    .replace(/ /g, '-')
                    .replace(/[^\w-]+/g, '');
            }



            $(document).on('click','.swal_change_language_button',function(e){
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to make this language as a default language?")}}',
                    text: '{{__("Languages will be turn changed as default")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{__('Yes, Change it!')}}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                });
            });
        </script>

        @yield('script')
        @yield('pwa-footer')
    </body>
</html>
