@extends('backend.admin-master')
@section('site-title')
    {{ __('Regenerate Image Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Regenerate Image Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                        @endif
                        <form action="{{ route('admin.general.regenerate.thumbnail') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Regenerate Images') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
