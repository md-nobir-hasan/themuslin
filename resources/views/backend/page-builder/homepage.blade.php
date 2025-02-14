@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/spectrum.min.css') }}">
    <style>
        .nice-select .option {
            min-height: 30px;
            padding: 0px 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .nice-select .option:hover,
        .nice-select .option.focus,
        .nice-select .option.selected.focus {
            font-weight: 700;
        }
    </style>
    <x-summernote.css />
    <x-media.css />
@endsection
@section('site-title')
    {{ __('Home Page Builder') }}
@endsection
@section('content')
    @php
        $location = 'homepage';
    @endphp

    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Page Builder') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div id="page-builder-wrap" class="page-builder-content-wrap">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="page-builder-area-wrapper">
                                        <ul id="sortable"
                                            class="sortable available-form-field main-fields sortable_widget_location">
                                            {!! \App\PageBuilder\PageBuilderSetup::get_saved_addons_by_location($location) !!}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="search-wrap">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="search_addon_field"
                                                placeholder="{{ __('Search Addon') }}" name="s">
                                        </div>
                                    </div>
                                    <div class="all-addons-wrapper">
                                        <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
                                            {!! \App\PageBuilder\PageBuilderSetup::get_admin_panel_widgets() !!}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
@endsection

@section('script')
    @include('backend.partials.page-builder.scripts')
    <x-media.js />
    <x-summernote.js />
@endsection
