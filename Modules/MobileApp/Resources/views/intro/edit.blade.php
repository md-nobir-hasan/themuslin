@extends('backend.admin-master')
@section('site-title')
    {{ __('mobile intro edit') }}
@endsection
@section('style')
    <x-media.css />
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Add New Mobile intro') }}</h4>
                        <div class="btn-wrapper">
                            <a class="cmn_btn btn_bg_profile"
                                href="{{ route('admin.mobile.intro.all') }}">{{ __('List') }}</a>
                        </div>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.mobile.intro.edit', $mobileIntro->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{ __('Title') }}</label>
                                <input class="form-control" id="title" name="title"
                                    placeholder="{{ __('Mobile intro Title...') }}" value="{{ $mobileIntro->title }}" />
                            </div>
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="{{ __('Mobile intro Description...') }}">{{ $mobileIntro->description }}</textarea>
                            </div>

                            <x-media-upload :title="__('Image')" :name="'image_id'" :oldimage="$mobileIntro->image" :dimentions="'1280x1280'" />

                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __('Update') }}</button>
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
    <x-media.js />
@endsection
