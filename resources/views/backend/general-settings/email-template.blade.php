@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/summernote-bs4.css') }}">
@endsection
@section('site-title')
    {{ __('Email Template') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Email Template') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <form action="{{ route('admin.general.email.template') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_global_email">{{ __('Global Email') }}</label>
                                        <input type="text" name="site_global_email" class="form-control"
                                            value="{{ get_static_option('site_global_email') }}" id="site_global_email">
                                        <small class="form-text text-muted">use your web mail here</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="site_global_email_template">{{ __('Email Template') }}</label>
                                        <input type="hidden" name="site_global_email_template" class="form-control"
                                            value="{{ get_static_option('site_global_email_template') }}"
                                            id="site_global_email_template">
                                        <div class="summernote"
                                            data-content='{{ get_static_option('site_global_email_template') }}'></div>
                                        <small class="form-text text-muted">@username
                                            {{ __('Will replace by username of user and') }} @company
                                            {{ __('will be replaced by site title also') }} @message
                                            {{ __('will be replaced by dynamically with message.') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit"
                                        class="cmn_btn btn_bg_profile">{{ __('Update Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/backend/js/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 150, //set editable area's height
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
            if ($('.summernote').length) {
                $('.summernote').each(function(index, value) {
                    $(this).summernote('code', $(this).data('content'));
                });
            }
        })
    </script>
@endsection
