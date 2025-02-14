@extends('backend.admin-master')
@section('site-title')
    {{ __('404 Error Page Settings') }}
@endsection
@section('style')
    <x-media.css />
@endsection
@section('content')
    <section>
        <div class="col-lg-12 col-ml-12">
            <div class="row">
                <div class="col-lg-12">
                    <x-msg.flash />
                    <x-msg.error />
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('404 Error Pagte Settings') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.404.page.settings') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="error_404_page_title">{{ __('Title') }}</label>
                                            <input type="text" name="error_404_page_title" class="form-control"
                                                value="{{ get_static_option('error_404_page_title') }}"
                                                id="error_404_page_title">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="error_404_page_button_text">{{ __('Button Text') }}</label>
                                            <input type="text" name="error_404_page_button_text" class="form-control"
                                                value="{{ get_static_option('error_404_page_button_text') }}"
                                                id="error_404_page_button_text">
                                            <x-media-upload :title="__('Error image')" :name="'error_404_page_image'" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-media.markup />
    </section>
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                let imgSelect = $('.img-select');
                let id = $('#header_type').val();
                imgSelect.removeClass('selected');
                $('img[data-header_type="' + id + '"]').parent().parent().addClass('selected');
                $(document).on('click', '.img-select img', function(e) {
                    e.preventDefault();
                    imgSelect.removeClass('selected');
                    $(this).parent().parent().addClass('selected').siblings();
                    $('#header_type').val($(this).data('header_type'));
                });

            })

        })(jQuery);
    </script>
    <x-media.js />
@endsection
