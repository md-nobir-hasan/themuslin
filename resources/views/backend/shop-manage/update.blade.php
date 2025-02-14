@extends('backend.admin-master')
@section('site-title')
    {{ __('Shop Manage') }}
@endsection

@section('style')
    <x-media.css />
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Admin Shop Manage') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        <form action="{{ route('admin.shop-manage.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <div class="row g-4">
                                        <div class="col-sm-12">
                                            <div class="single-input">
                                                <label class="label-title">{{ __('Store Name') }} </label>
                                                <input value="{{ $shopManage->store_name ?? null }}" name="store_name"
                                                    type="text" class="form--control radius-10"
                                                    placeholder="{{ __('Business Name') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Email') }} </label>
                                                <input value="{{ $shopManage->email ?? null }}" name="email"
                                                    type="email" class="form--control radius-10"
                                                    placeholder="{{ __('Type Email') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Country') }} </label>
                                                <div class="nice-select-two country_wrapper">
                                                    <select id="country_id" class="form--control" name="country_id">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach ($country as $item)
                                                            <option
                                                                {{ $shopManage->country_id ?? null == $item->id ? 'selected' : '' }}
                                                                value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('State') }} </label>
                                                <div class="nice-select-two state_wrapper">
                                                    <select id="state_id" class="form-control" name="state_id">
                                                        <option value="">{{ __('Select State') }}</option>
                                                        @php
                                                            $states = \Modules\CountryManage\Entities\State::where('country_id', $shopManage->country_id)->get();
                                                        @endphp
                                                        @foreach ($states as $state)
                                                            <option value="">{{ __('Select State') }}</option>
                                                            <option
                                                                {{ $state->id == $shopManage->state_id ? 'selected' : '' }}
                                                                value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('City') }} </label>
                                                <div class="nice-select-two city_wrapper">
                                                    <input value="{{ $shopManage->city ?? null }}" type="text"
                                                        name="city" class="form-control form-control-sm" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Zip Code') }} </label>
                                                <input value="{{ $shopManage->zipcode }}" type="text" name="zipcode"
                                                    class="form--control radius-10" placeholder="{{ __('Zip Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Number') }} </label>
                                                <input value="{{ $shopManage->number ?? null }}" name="number"
                                                    type="tel" class="form--control radius-10"
                                                    placeholder="{{ __('Type Number') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Address') }} </label>
                                                <textarea value="{{ $shopManage->address ?? null }}" name="address" type="text" class="form--control radius-10"
                                                    placeholder="{{ __('Type Address') }}"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Location') }} </label>
                                                <input value="{{ $shopManage->location ?? null }}" name="location"
                                                    type="text" class="form--control radius-10"
                                                    placeholder="{{ __('Set Location From Map') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="single-input">
                                                <label class="label-title"> {{ __('Facebook Link') }} </label>
                                                <input value="{{ $shopManage->facebook_url ?? null }}" type="url"
                                                    name="facebook_url" class="form--control radius-10"
                                                    placeholder="{{ __('Type Facebook Link') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <x-media.media-upload :oldImage="$shopManage->logo ?? null" :title="__('Logo')" :name="'logo_id'"
                                        :dimentions="'200x200'" />
                                    <x-media.media-upload :oldImage="$shopManage->cover_photo ?? null" :title="__('Cover Photo')" :name="'cover_photo_id'"
                                        :dimentions="'200x200'" />
                                </div>
                                <div class="btn-wrapper mt-4">
                                    <button type="submit"
                                        class="cmn_btn btn_bg_profile w-100">{{ __('Update Shop') }}</button>
                                </div>
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
    <script>
        $(document).on("change", "#country_id", function() {
            let data = new FormData();

            data.append("country_id", $(this).val());
            data.append("_token", "{{ csrf_token() }}");

            send_ajax_request("post", data, "{{ route('admin.vendor.get.state') }}", function() {}, (data) => {
                $("#state_id").html(data.option);
                $(".state_wrapper .list").html(data.li);
            }, (data) => {
                prepare_errors(data);
            })
        });

        $(document).on("change", "#state_id", function() {
            let data = new FormData();

            data.append("country_id", $("#country_id").val());
            data.append("state_id", $(this).val());
            data.append("_token", "{{ csrf_token() }}");

            send_ajax_request("post", data, "{{ route('admin.vendor.get.city') }}", function() {}, (data) => {
                $("#city_id").html(data.option);
                $(".city_wrapper .list").html(data.li);
            }, (data) => {
                prepare_errors(data);
            })
        });
    </script>
@endsection
