@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    @include('backend.partials.media-upload.css')
@endsection
@section('site-title')
    {{__('Edit Profile')}}
@endsection

@php
    $image = auth('admin')->user()->profile_image;
@endphp

@section('content')
    <div class="main-content-inner margin-top-30">
        <div class="row">
            <div class="col-lg-12">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        @include('backend.partials.error')
                        @can("profile-update")
                            <form action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                        @endcan
                                <div class="form-group">
                                    <label for="username">{{__('Username')}}</label>
                                    <input type="text" class="form-control" readonly value="{{auth()->user()->username}} ">
                                </div>
                                <div class="form-group">
                                    <label for="name">{{__('Name')}}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{auth()->user()->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="email">{{__('Email')}}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{auth('admin')->user()->email}} ">
                                </div>
                                <div class="form-group">
                                    @php $image_upload_btn_label = __('Upload Image'); @endphp
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap">
                                            @php
                                                $profile_img = \App\Http\Services\Media::render_image($image,render_type: 'path',size: 'thumb');
                                            @endphp
                                            @if (!empty($profile_img))
                                                <div class="attachment-preview">
                                                    <div class="thumbnail">
                                                        <div class="centered">
                                                            <img class="avatar user-thumb" src="{{ $profile_img }}" alt="{{ auth()->user()->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $image_upload_btn_label = __('Change Image'); @endphp
                                            @endif
                                        </div>
                                        <input type="hidden" name="image" value="{{auth()->user()->image}}">
                                        <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Profile Picture')}}" data-modaltitle="{{__('Upload Profile Picture')}}" data-imgid="{{auth()->user()->image}}" data-bs-toggle="modal" data-bs-target="#media_upload_modal">
                                            {{__($image_upload_btn_label)}}
                                        </button>
                                    </div>
                                    <small class="info-text">{{__('Recommended Image Size 100x100. Only Accept: jpg,png.jpeg. Size less than 2MB')}}</small>
                                </div>
                        @can("profile-update")
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">{{__('Save changes')}}</button>
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    @include('backend.partials.media-upload.js')
{{--    @include('backend.partials.media-upload.media-js')--}}
@endsection
