@extends('backend.admin-master')
@section('site-title')
    {{ __('Shipping Zones') }}
@endsection
@section('site-title')
    {{ __('Shipping Zones') }}
@endsection

@section('style')
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12" id="shipping-zone-wrapper-box">
        <div class="row g-4">
            <div class="col-md-12">
                <x-flash-msg />
                <x-error-msg />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Update Shipping Zone') }}</h4>

                        @can('shipping-zone')
                            <a href="{{ route('admin.shipping.zone.all') }}"
                                class="cmn_btn btn_bg_profile">{{ __('Shipping Zone List') }}</a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form id="shipping-zone-create-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}" />
                            <div class="form-group">
                                <label>{{ __('Zone Name') }}</label>
                                <input class="form-control" name="zone_name" value="{{ $zone->name }}"
                                    placeholder="{{ __('Write shipping zone.') }}" />
                            </div>
                            <div class="table-wrap">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Country') }}</th>
                                            <th>{{ __('States') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($zone->country as $zoneCountry)
                                            @php
                                                // this variable is needed for rendering tr block hare and initialize select two
                                                $rand = random_int(9999999, 11111111);
                                            @endphp

                                            @include('shippingmodule::admin.shipping-zone-tr')
                                        @empty
                                            @php
                                                // this variable is needed for rendering tr block hare and initialize select two
                                                $rand = random_int(9999999, 11111111);
                                            @endphp

                                            @include('shippingmodule::admin.shipping-zone-tr')
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __('Update Shipping Zone') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on("change", "#country_select", function() {
            let val = $(this).val();
            let urlToRequest = "{{ route('frontend.get-states') }}" + "/" + $(this).val();

            send_ajax_request("get", null, urlToRequest, () => {
                $(this).parent().parent().find("#states_select").html(
                    `<option value="">{{ __("Please wait we're finding states") }}</option>`);
            }, (data) => {
                if (data.success) {
                    $(this).parent().parent().find("#states_select").parent().find(".multiple-options")
                        .html("");
                    $(this).parent().parent().find("#states_select").attr("name", "states[" + val + "][]");
                    $(this).parent().parent().find("#states_select").html(data.data);
                    $(this).parent().parent().find("#states_select").parent().find("ul").html(data.list);
                }
            }, (err) => {
                ajax_toastr_error_message(err)
            })
        });

        $(document).on("submit", "#shipping-zone-create-form", function(e) {
            e.preventDefault();

            send_ajax_request("POST", new FormData(e.target),
                "{{ route('admin.shipping.zone.update', $zone->id) }}", () => {}, (data) => {
                    ajax_toastr_success_message(data)
                }, (err) => {
                    ajax_toastr_error_message(err)
                })
        });

        $(document).on("click", "#shipping_zone_plus_btn", function() {
            // this variable is needed for rendering tr block hare and initialize select two
            // let random = Math.floor((Math.random() * 99999999) + 1);

            let data = `@include('shippingmodule::admin.shipping-zone-tr')`;
            $(this).parent().parent().parent().append(data);

            let row = $("#shipping-zone-create-form tbody tr")[$("#shipping-zone-create-form tbody tr").length - 1];
            let x = $(row).find('#states_select').attr("data-current-data");

            // $(row).find('#states_select').attr("data-current-data",x)
            // $(row).find('#states_select').attr("name",'states['+ $(row).find('#states_select').attr("name") +'][]')

            $("#shipping-zone-wrapper-box select").niceSelect();
        });

        $(document).on("click", "#shipping_zone_minus_btn", function() {
            let tr = $(this).parent().parent();

            if (tr.parent().find("tr").length > 1) {
                tr.remove();
            }
        });

        $(document).ready(function() {
            $("#shipping-zone-wrapper-box select").niceSelect();
        })
    </script>
@endsection
