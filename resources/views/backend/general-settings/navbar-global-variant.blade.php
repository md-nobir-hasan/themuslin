@extends('backend.admin-master')

@section('site-title')
    {{ __('Navbar Global Variant Settings') }}
@endsection

@section('content')
    @can('general-settings-global-navbar-settings')
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-12">
                    <x-msg.success />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Navbar Global Variant Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.general.global.variant.navbar') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="dashboard__card">
                                    <div class="dashboard__card__body">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" id="global_navbar_variant"
                                                value="{{ get_static_option('global_navbar_variant') }}"
                                                name="global_navbar_variant">
                                        </div>
                                        <div class="row">
                                            @for ($i = 1; $i <= 3; $i++)
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="img-select selected">
                                                        <div class="img-wrap">
                                                            <img src="{{ asset('assets/frontend/navbar-variant/0' . $i . '.jpg') }}"
                                                                data-home_id="0{{ $i }}" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <button type="submit" id="update"
                                        class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let iconpicker_selector = '.icp-dd';
                $(iconpicker_selector).iconpicker();
                $(iconpicker_selector).on('iconpickerSelected', function(e) {
                    let selectedIcon = e.iconpickerValue;
                    $(this).parent().parent().children('input').val(selectedIcon);
                });

                $(document).on('click', '#update', function() {
                    $(this).addClass("disabled")
                    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> {{ __('Updating') }}');
                });

                //For Navbar
                let imgSelect = $('.img-select');
                let id = $('#global_navbar_variant').val();
                imgSelect.removeClass('selected');
                $('img[data-home_id="' + id + '"]').parent().parent().addClass('selected');
                $(document).on('click', '.img-select img', function(e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#global_navbar_variant').val($(this).data('home_id'));
                });
            });
        }(jQuery));
    </script>
@endsection
