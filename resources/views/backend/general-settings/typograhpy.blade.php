@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/nice-select.css') }}">
@endsection
@section('site-title')
    {{ __('Typography Settings') }}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Body Typography Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.typography.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="body_font_family">{{ __('Font Family') }}</label>
                                        <select class="form-control wide" name="body_font_family" id="body_font_family">
                                            @foreach ($google_fonts as $font_family => $font_variant)
                                                <option value="{{ $font_family }}"
                                                    @if ($font_family == get_static_option('body_font_family')) selected @endif>{{ $font_family }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="body_font_variant">{{ __('Font Variant') }}</label>
                                        @php
                                            $font_family_selected = get_static_option('body_font_family') ?? get_static_option('body_font_family');
                                            $get_font_family_variants = property_exists($google_fonts, $font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => ['regular']];
                                        @endphp
                                        <select class="form-control wide" multiple id="body_font_variant"
                                            name="body_font_variant[]">
                                            @foreach ($get_font_family_variants['variants'] as $variant)
                                                @php
                                                    $selected_variant = !empty(get_static_option('body_font_variant')) ? unserialize(get_static_option('body_font_variant')) : [];
                                                @endphp
                                                <option value="{{ $variant }}"
                                                    @if (in_array($variant, $selected_variant)) selected @endif>
                                                    {{ str_replace(['0,', '1,'], ['', 'i'], $variant) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <h4 class="dashboard__card__title mb-3">{{ __('Heading Typography Settings') }}</h4>
                                    <div class="form-group">
                                        <label for="heading_font">{{ __('Heading Font') }}</label>
                                        <label class="switch">
                                            <input type="checkbox" name="heading_font"
                                                @if (!empty(get_static_option('heading_font'))) checked @endif id="heading_font">
                                            <span class="slider"></span>
                                        </label>
                                        <small>{{ __('Use different font family for heading tags ( h1,h2,h3,h4,h5,h6)') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="heading_font_family">{{ __('Font Family') }}</label>
                                        <select class="form-control wide" name="heading_font_family"
                                            id="heading_font_family">
                                            @foreach ($google_fonts as $font_family => $font_variant)
                                                <option value="{{ $font_family }}"
                                                    @if ($font_family == get_static_option('heading_font_family')) selected @endif>{{ $font_family }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="heading_font_variant">{{ __('Font Variant') }}</label>
                                        @php
                                            $font_family_selected = get_static_option('heading_font_family') ?? '';
                                            $get_font_family_variants = property_exists($google_fonts, $font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => ['regular']];
                                        @endphp
                                        <select class="form-control wide" multiple name="heading_font_variant[]"
                                            id="heading_font_variant">
                                            @foreach ($get_font_family_variants['variants'] as $variant)
                                                @php
                                                    $selected_variant = !empty(get_static_option('heading_font_variant')) ? unserialize(get_static_option('heading_font_variant')) : [];
                                                @endphp
                                                <option value="{{ $variant }}"
                                                    @if (in_array($variant, $selected_variant)) selected @endif>
                                                    {{ str_replace(['0,', '1,'], ['', 'i'], $variant) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <h4 class="dashboard__card__title mb-3">{{ __('Extra Font Typography Settings') }}</h4>
                                    <div class="form-group">
                                        <label for="heading_font">{{ __('Extra Font') }}</label>
                                        <label class="switch">
                                            <input type="checkbox" name="extra_font"
                                                @if (!empty(get_static_option('extra_font'))) checked @endif id="extra_font">
                                            <span class="slider"></span>
                                        </label>
                                        <small>{{ __('Use different font family for heading tags ( h1,h2,h3,h4,h5,h6)') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="heading_font_family">{{ __('Font Family') }}</label>
                                        <select class="form-control wide" name="extra_font_family" id="extra_font_family">
                                            @foreach ($google_fonts as $font_family => $font_variant)
                                                <option value="{{ $font_family }}"
                                                    @if ($font_family == get_static_option('extra_font_family')) selected @endif>{{ $font_family }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="extra_font_variant">{{ __('Font Variant') }}</label>
                                        @php
                                            $font_family_selected = get_static_option('extra_font_family') ?? '';
                                            $get_font_family_variants = property_exists($google_fonts, $font_family_selected) ? (array) $google_fonts->$font_family_selected : ['variants' => ['regular']];
                                        @endphp
                                        <select class="form-control wide" multiple name="extra_font_variant[]"
                                            id="extra_font_variant">
                                            @foreach ($get_font_family_variants['variants'] as $variant)
                                                @php
                                                    $selected_variant = !empty(get_static_option('extra_font_variant')) ? unserialize(get_static_option('extra_font_variant')) : [];
                                                @endphp
                                                <option value="{{ $variant }}"
                                                    @if (in_array($variant, $selected_variant)) selected @endif>
                                                    {{ str_replace(['0,', '1,'], ['', 'i'], $variant) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" id="typography_submit_btn"
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
    <script src="{{ asset('assets/backend/js/jquery.nice-select.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {

                //load font variant

                $(document).on('change', '#body_font_family', function(e) {
                    e.preventDefault();
                    var fontFamily = $(this).val();

                    $.ajax({
                        url: "{{ route('admin.general.typography.single') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            font_family: fontFamily
                        },
                        success: function(data) {
                            var variantSelector = $('#body_font_variant');
                            variantSelector.html('');
                            $.each(data.variants, function(index, value) {
                                var nameval = value.replace('0,', '');
                                nameval = nameval.replace('1,', 'i');
                                variantSelector.append('<option value="' + value +
                                    '">' + nameval + '</option>');
                            });
                            variantSelector.niceSelect('update');
                        }
                    });
                });

                $(document).on('change', '#heading_font_family', function(e) {
                    e.preventDefault();
                    var fontFamily = $(this).val();

                    $.ajax({
                        url: "{{ route('admin.general.typography.single') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            font_family: fontFamily
                        },
                        success: function(data) {
                            var variantSelector = $('#heading_font_variant');
                            variantSelector.html('');
                            $.each(data.variants, function(index, value) {
                                var nameval = value.replace('0,', '');
                                nameval = nameval.replace('1,', 'i');
                                variantSelector.append('<option value="' + value +
                                    '">' + nameval + '</option>');
                            });

                            variantSelector.niceSelect('update');
                        }
                    });

                });

                $(document).on('change', '#extra_font_family', function(e) {
                    e.preventDefault();
                    var fontFamily = $(this).val();

                    $.ajax({
                        url: "{{ route('admin.general.typography.single') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            font_family: fontFamily
                        },
                        success: function(data) {
                            let variantSelector = $('#extra_font_variant');
                            variantSelector.html('');
                            $.each(data.variants, function(index, value) {
                                var nameval = value.replace('0,', '');
                                nameval = nameval.replace('1,', 'i');
                                variantSelector.append('<option value="' + value +
                                    '">' + nameval + '</option>');
                            });

                            variantSelector.niceSelect('update');
                        }
                    });

                });

                if ($('.nice-select').length > 0) {
                    $('.nice-select').niceSelect();
                }
                var dependendFields = $('select[name="heading_font_family"],#heading_font_variant');
                if (!$('input[name="heading_font"]').prop('checked')) {
                    dependendFields.parent().hide()
                }
                $(document).on('change', 'input[name="heading_font"]', function(e) {
                    if (!$(this).prop('checked')) {
                        dependendFields.parent().hide();
                    } else {
                        dependendFields.parent().show();
                    }
                });


                dependendFields = $('select[name="extra_font_family"],#extra_font_variant');
                $(document).on('change', 'input[name="extra_font"]', function(e) {
                    if (!$(this).prop('checked')) {
                        dependendFields.parent().hide();
                    } else {
                        dependendFields.parent().show();
                    }
                });
            });
        }(jQuery));
    </script>
@endsection
