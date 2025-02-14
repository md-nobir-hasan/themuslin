@extends('backend.layout.master')
@section('title', __('User Identity Decline'))
@section('style')
    <x-summernote.summernote-css />
@endsection
@section('content')
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-12">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title">{{ __('User Identity Decline') }}</h4>
                        </div>
                        <div class="search_delete_wrapper">
                            <h4><a class="btn-profile btn-bg-1" href="{{ route('admin.email.template.all') }}">{{ __('All Templates') }}</a></h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="{{route('admin.user.identity.decline')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text
                                    :title="__('Email Subject')"
                                    :type="__('text')"
                                    :name="'user_identity_decline_subject'"
                                    :id="'user_identity_decline_subject'"
                                    :value="get_static_option('user_identity_decline_subject') ?? __('User Identity Decline')"
                                />
                                <x-form.summernote
                                    :title="__('Email Message')"
                                    :name="'user_identity_decline_message'"
                                    :id="'user_identity_decline_message'"
                                    :value="get_static_option('user_identity_decline_message') ?? ''"
                                />
                                <small class="form-text text-muted text-danger margin-top-20"><code>@name</code> {{__('will be replaced by dynamically with  name.')}}</small><br>
                                <small class="form-text text-muted text-danger"><code>@username</code> {{__('will be replaced by dynamically with username.')}}</small><br>
                                <small class="form-text text-muted text-danger"><code>@email</code> {{__('will be replaced by dynamically with email.')}}</small><br>
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
    <x-summernote.summernote-js />
@endsection
