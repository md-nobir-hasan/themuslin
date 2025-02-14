@extends('backend.admin-master')
@section('title', __('User Identity Verify Request Email'))
@section('style')
    <x-summernote.css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('User Identity Verify Request Email') }}</h4>
                        </div>
                        <div class="search_delete_wrapper">
                            <h4><a class="btn-profile btn-bg-1" href="{{ route('admin.email.template.all') }}">{{ __('All Templates') }}</a></h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.email-template.request-send')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <x-form.text
                                    :title="__('Email Subject')"
                                    :type="__('text')"
                                    :name="'user_identity_verify_subject'"
                                    :id="'user_identity_verify_subject'"
                                    :value="get_static_option('user_identity_verify_subject') ?? __('User Identity Verify Email')"
                                />
                                <x-form.summernote
                                    :title="__('Email Message')"
                                    :name="'user_identity_verify_message'"
                                    :id="'user_identity_verify_message'"
                                    :value="get_static_option('user_identity_verify_message') ??
                                            '<p>Hello,</p></p>You have a new request for user identity verification</p>'"
                                />
                                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4 update_info'" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-summernote.js />
@endsection
