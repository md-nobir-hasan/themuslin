@extends('backend.admin-master')
@section('site-title')
    {{ __('Payment Settings') }}
@endsection
@section('style')
    <x-summernote.css />
    <x-media.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-flash-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Payment Gateway Settings1') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <x-error-msg />
                        <form action="{{ route('admin.general.payment.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    @include('backend.general-settings.payment-settings.payment-common-settings')
                                    @include('backend.general-settings.payment-settings.payment-credential-settings')
                                </div>
                            </div>
                            <div class="btn-wrapper mt-4">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-media.markup />
@endsection
@section('script')
    <x-summernote.js />
    <x-media.js />
    <script>
        (function($) {
            "use strict";
            $(document).ready(function($) {
                $('.summernote').summernote({
                    height: 200, //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        },
                        onPaste: function (e) {
                            let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/plain');
                            e.preventDefault();
                            document.execCommand('insertText', false, bufferText);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function(index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });
        })(jQuery);
    </script>
@endsection
