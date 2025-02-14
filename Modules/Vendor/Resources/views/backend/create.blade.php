@extends('backend.admin-master')
@section('site-title')
    {{ __('Vendor Create') }}
@endsection
@section('style')
    <x-media.css />
    <x-datatable.css />
    <x-bulk-action.css />
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <x-msg.error />
        <x-msg.flash />
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Create Vendor') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form id="vendor-create-form" data-action-url="{{ route('admin.vendor.create') }}">
                            <div class="toast toast-success"></div>
                            @csrf
                            <div class="d-flex justify-content-between">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#basic-info" type="button" role="tab"
                                            aria-controls="basic-info" aria-selected="true">{{ __("Basic") }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#address" type="button" role="tab" aria-controls="address"
                                            aria-selected="false">{{ __("Address") }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#shop-info" type="button" role="tab"
                                            aria-controls="shop-info" aria-selected="false">{{ __("Shop Info") }}
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                            data-bs-target="#bank-info" type="button" role="tab"
                                            aria-controls="bank-info" aria-selected="false">{{ __("Bank Info") }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="row">
                                        <div class="col-lg-6 mt-4">
                                            <div class="dashboard__card">
                                                <div class="dashboard__card__header">
                                                    <h4 class="dashboard__card__title">{{ __('Basic Info*') }}</h4>
                                                </div>
                                                <div class="dashboard__card__body mt-4">
                                                    <div class="row g-4">
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Owner Name") }} </label>
                                                                <input name="owner_name" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Owner Name") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Business Name") }} </label>
                                                                <input name="business_name" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Business Name") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title">{{ __("Email") }}</label>
                                                                <input name="email" type="text"
                                                                    class="form--control radius-10" placeholder="{{ __("Email") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Username") }}</label>
                                                                <input name="username" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Business Name") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Password") }}</label>
                                                                <input name="password" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Password") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title">{{ __("Confirmed Password") }}</label>
                                                                <input name="password_confirmation" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Confirm Password") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Business
                                                                    Category") }} </label>
                                                                <div class="nice-select-two">
                                                                    <select id="business_type" name="business_type_id"
                                                                        style="display: none;">
                                                                        @foreach ($business_type as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Description") }} </label>
                                                                <textarea name="description" class="form--control form--message radius-10" style="height: 100px"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <x-media-upload :title="__('Logo')" name="logo_id" dimentions="200x200" />
                                            <x-media-upload :title="__('Cover Photo')" name="cover_photo_id" dimentions="200x200" />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-4" id="address" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="dashboard__card">
                                                <div class="dashboard__card__header">
                                                    <h4 class="dashboard__card__title"> {{ __("Address") }} </h4>
                                                </div>
                                                <div class="dashboard__card__body mt-4">
                                                    <div class="row g-4">
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Country") }} </label>
                                                                <div class="nice-select-two country_wrapper">
                                                                    <select id="country_id" name="country_id"
                                                                        style="display: none;">
                                                                        <option value="">{{ __("Select Country") }}</option>
                                                                        @foreach ($country as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("State") }} </label>
                                                                <div class="nice-select-two state_wrapper">
                                                                    <select id="state_id" name="state_id"
                                                                        style="display: none;">
                                                                        <option value="">{{ __("Select State") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("City") }} </label>
                                                                <div class="nice-select-two city_wrapper">
                                                                    <select id="city_id" name="city_id"
                                                                        style="display: none;">
                                                                        <option value="">{{ __("Select City") }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Zip Code") }} </label>
                                                                <input type="text" name="zip_code"
                                                                    class="form--control radius-10"
                                                                    placeholder="Zip Code">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Address") }} </label>
                                                                <textarea name="address" type="text" class="form--control radius-10" placeholder="Type Address"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-4" id="shop-info" role="tabpanel"
                                    aria-labelledby="contact-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="dashboard__card">
                                                <div class="dashboard__card__header">
                                                    <h4 class="dashboard__card__title">{{ __('Shop Info') }}</h4>
                                                </div>
                                                <div class="dashboard__card__body mt-4">
                                                    <div class="row g-4">
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Location") }} </label>
                                                                <input name="location" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Set Location From Map") }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Number") }} </label>
                                                                <input name="number" type="tel"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Number") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Email Address") }} </label>
                                                                <input type="text" name="shop_email"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Email") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Facebook Link") }} </label>
                                                                <input type="url" name="facebook_url"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Facebook Link") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Website") }} </label>
                                                                <input type="url" name="website_url"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Website") }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade mt-4" id="bank-info" role="tabpanel"
                                    aria-labelledby="contact-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="dashboard__card">
                                                <div class="dashboard__card__header">
                                                    <h4 class="dashboard__card__title">{{ __('Bank Info') }}</h4>
                                                </div>
                                                <div class="dashboard__card__body mt-4">
                                                    <div class="row g-4">
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Name") }} </label>
                                                                <input name="bank_name" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Name") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Email") }} </label>
                                                                <input name="bank_email" type="text"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Email") }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Bank Code") }} </label>
                                                                <input name="bank_code" type="tel"
                                                                    class="form--control radius-10"
                                                                    placeholder="Type Code">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="single-input">
                                                                <label class="label-title"> {{ __("Account Number") }}
                                                                </label>
                                                                <input name="account_number" type="tel"
                                                                    class="form--control radius-10"
                                                                    placeholder="{{ __("Type Account Number") }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit_button mt-4">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __("Submit") }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="body-overlay-desktop"></div>
    <x-media.markup />
@endsection
@section('script')
    <x-datatable.js />
    <x-media.js />
    <x-table.btn.swal.js />
    <script>
        $(document).ready(function (){
            $("#business_type").select2();
            $("#country_id").select2();
            $("#state_id").select2();
            $("#city_id").select2();
        });

        $(document).on("submit", "#vendor-create-form", function(e) {
            e.preventDefault();
            let url = $(this).data("action-url"),
                data = new FormData(e.target);

            send_ajax_request("post", data, url, () => {
                // write some code for preloader
                $(".submit_button button").append('<i class="las la-spinner"></i>');
                toastr.warning("Request Send.. Please Wait...");
            }, (data) => {
                $("#state_id").html(data.option);
                $(".state_wrapper .list").html(data.li);
                $(".submit_button button i").remove()
                toastr.success("Successfully Created Vendor Account....");
            }, (data) => {
                prepare_errors(data);
                $(".submit_button button i").remove()
            })
        })

        $(document).on("change", "#country_id", function() {
            let data = new FormData();

            data.append("country_id", $(this).val());
            data.append("_token", "{{ csrf_token() }}");

            send_ajax_request("post", data, "{{ route('admin.vendor.get.state') }}", function() {}, (data) => {
                $("#state_id").html("<option value=''>{{ __("Select an state") }}</option>" + data.option);
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
                $("#city_id").html("<option value=''>{{ __("Select an city") }}</option>" + data.option);
                $(".city_wrapper .list").html(data.li);
            }, (data) => {
                prepare_errors(data);
            })
        });

        $(document).on("keyup keydown click change", "input[name=username]", function() {
            $(this).val(convertToSlug($(this).val()))
        });
    </script>
@endsection
