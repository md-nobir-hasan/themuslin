@extends('backend.layout.master')
@section('title', __('Job Edit Email'))
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
                            <h4 class="customMarkup__single__title">{{ __('Job Edit Email') }}</h4>
                        </div>
                        <div class="search_delete_wrapper">
                            <h4><a class="btn-profile btn-bg-1" href="{{ route('admin.email.template.all') }}">{{ __('All Templates') }}</a></h4>
                            <x-search.search-in-table :id="'string_search'" />
                        </div>
                        <div class="customMarkup__single__inner mt-4">
                            <x-validation.error />
                            <form action="{{route('admin.user.job.edit')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <x-form.text
                                    :title="__('Email Subject')"
                                    :type="__('text')"
                                    :name="'job_edit_email_subject'"
                                    :id="'job_edit_email_subject'"
                                    :value="get_static_option('job_edit_email_subject') ?? __('Job Edit Email')"
                                />
                                <x-form.summernote
                                    :title="__('Email Message')"
                                    :name="'job_edit_email_message'"
                                    :id="'job_edit_email_message'"
                                    :value="get_static_option('job_edit_email_message') ?? '' "
                                />
                                <small class="form-text text-muted text-danger margin-top-20"><code>@job_id</code> {{__('will be replaced by dynamically with job id.')}}</small><br>
                                <x-btn.submit :title="__('Save')" :class="'btn-profile btn-bg-1 mt-4 pr-4 pl-4'" />
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
