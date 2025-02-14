@extends('backend.admin-master')
@section('site-title')
    {{ __('Order Page Settings') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
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
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Order Page Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.package.order.page') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($all_languages as $key => $lang)
                                        <a class="nav-item nav-link @if ($key == 0) active @endif"
                                            data-bs-toggle="tab" href="#nav-home-{{ $lang->slug }}" role="tab"
                                            aria-selected="true">{{ $lang->name }}</a>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content margin-top-30" id="nav-tabContent">
                                @foreach ($all_languages as $key => $lang)
                                    <div class="tab-pane fade @if ($key == 0) show active @endif"
                                        id="nav-home-{{ $lang->slug }}" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="form-group">
                                            <label
                                                for="order_page_{{ $lang->slug }}_form_title">{{ __('Order Form Title') }}</label>
                                            <input type="text" name="order_page_{{ $lang->slug }}_form_title"
                                                value="{{ get_static_option('order_page_' . $lang->slug . '_form_title') }}"
                                                class="form-control" id="order_page_{{ $lang->slug }}_form_title">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="order_page_form_mail">{{ __('Email Address For Order Message') }}</label>
                                <input type="text" name="order_page_form_mail"
                                    value="{{ get_static_option('order_page_form_mail') }}" class="form-control"
                                    id="order_page_form_mail">
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
