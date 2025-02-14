@extends('backend.admin-master')
@section('site-title')
    {{ __('Popup Settings') }}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Popup Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.general.popup.settings') }}" method="Post"
                            enctype="multipart/form-data">
                            @csrf
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @foreach ($all_languages as $key => $lang)
                                        <a class="nav-item nav-link @if ($key == 0) active @endif"
                                            id="nav-home-tab" data-bs-toggle="tab" href="#nav-home-{{ $lang->slug }}"
                                            role="tab" aria-controls="nav-home"
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
                                                for="popup_selected_{{ $lang->slug }}_id">{{ __('Select Popup') }}</label>
                                            <select name="popup_selected_{{ $lang->slug }}_id" class="form-control"
                                                id="popup_selected_{{ $lang->slug }}_id">
                                                @if (isset($all_popup[$lang->slug]))
                                                    @foreach ($all_popup[$lang->slug] as $item)
                                                        <option @if (get_static_option('popup_selected_' . $lang->slug . '_id') == $item->id) selected @endif
                                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="popup_enable_status"><strong>{{ __('Popup Enable/Disable') }}</strong></label>
                                <label class="switch">
                                    <input type="checkbox" name="popup_enable_status"
                                        @if (!empty(get_static_option('popup_enable_status'))) checked @endif id="popup_enable_status">
                                    <span class="slider onff"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="popup_delay_time">{{ __('Popup Delay Time') }}</label>
                                <input type="text" class="form-control" name="popup_delay_time" id="popup_delay_time"
                                    value="{{ get_static_option('popup_delay_time') }}">
                                <p class="info-text">{{ __('put number in milliseconds') }}</p>
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile"
                                id="db_backup_btn">{{ __('Save Changes') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
