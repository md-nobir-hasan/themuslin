@extends('backend.admin-master')
@section('site-title')
    {{ __('Contact Page Section Manage') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                @include('backend/partials/message')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Contact Page Section Manage') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.contact.page.section.manage') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="contact_page_contact_info_section_status"><strong>{{ __('Contact Info Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="contact_page_contact_info_section_status"
                                                @if (!empty(get_static_option('contact_page_contact_info_section_status'))) checked @endif
                                                id="contact_page_contact_info_section_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label
                                            for="contact_page_contact_section_status"><strong>{{ __('Contact Section Show/Hide') }}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="contact_page_contact_section_status"
                                                @if (!empty(get_static_option('contact_page_contact_section_status'))) checked @endif
                                                id="contact_page_contact_section_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
