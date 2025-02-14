@extends('backend.admin-master')
@section('site-title')
    {{ __('About Page Section Manage') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                @include('backend/partials/message')
                @include('backend/partials/error')
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('About Page Section Manage') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.about.page.section.manage') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @php
                                    $section_list = ['about_us'];
                                @endphp
                                @foreach ($section_list as $section)
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="about_page_{{ $section }}_section_status"><strong>{{ ucfirst(str_replace('_', ' ', $section)) }}
                                                    {{ __('Section Show/Hide') }}</strong></label>
                                            <label class="switch">
                                                <input type="checkbox" name="about_page_{{ $section }}_section_status"
                                                    @if (!empty(get_static_option('about_page_' . $section . '_section_status'))) checked @endif>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
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
