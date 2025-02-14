@extends('backend.admin-master')
@section('site-title')
    {{ __('Google Mp Section') }}
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
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Google Map Section Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.contact.page.map') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="contact_page_map_section_location">{{ __('Map Location') }}</label>
                                <input type="text" name="contact_page_map_section_location"
                                    value="{{ get_static_option('contact_page_map_section_location') }}"
                                    class="form-control" id="contact_page_map_section_location">
                            </div>
                            <div class="form-group">
                                <label for="contact_page_map_section_zoom">{{ __('Map Zoom') }}</label>
                                <input type="text" name="contact_page_map_section_zoom"
                                    value="{{ get_static_option('contact_page_map_section_zoom') }}" class="form-control"
                                    id="contact_page_map_section_zoom">
                            </div>
                            <button id="update" type="submit"
                                class="cmn_btn btn_bg_profile">{{ __('Update Settings') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        < x - btn.update / >
    </script>
@endsection
