@extends('backend.admin-master')
@section('site-title', __('Tax Class'))

@section('style')

@endsection

@section('content')
    <div class="message-wrapper">
        <x-msg.flash />
        <x-msg.error />
    </div>
    <div class="dashboard__card">
        <div class="dashboard__card__header">
            <h5 class="dashboard__card__title">{{ __('Tax Class Options') }}</h5>
            <div class="dashboard__card__header__right tax-class-options">
                <button class="cmn_btn btn_bg_profile btn_sm add-tax-option">{{ __('Add') }}</button>
                <button class="cmn_btn btn_bg_2 btn_sm remove-tax-option">{{ __('Delete') }}</button>
                <button class="cmn_btn btn_bg_3 btn_sm store-tax-option">{{ __('Update') }}</button>
            </div>
        </div>

        <div class="dashboard__card__body mt-4">
            <ol class="mb-3">
                <li>{{ __('The tax will be applied to all countries if you do not select any') }}</li>
                <li>{{ __('The "Name" and "Priority" field is a required entry for data storage in the database. If the name is not provided, the corresponding field data will not be stored.') }}
                </li>
            </ol>
            <form id="tax-class-option-form" action="{{ route('admin.tax-module.tax-class-option', $taxClass->id) }}"
                method="post">
                @csrf
                <div class="table-wrap">
                    <table class="table table-responsive" id="tax-option-table">
                        <thead>
                            <tr>
                                <th class="d-flex justify-content-center align-items-center">
                                    <input type="checkbox" name="select-all-text-class-option"
                                        id="select-all-text-class-option" class="form-check">
                                </th>
                                <th>* {{ __('Name') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('State') }}</th>
                                <th>{{ __('City') }}</th>
                                <th>{{ __('Postal Code') }}</th>
                                <th>{{ __('Rate') }}</th>
                                <th class="d-none">{{ __('Compound') }}</th>
                                <th>{{ __('Shipping') }}</th>
                                <th>* {{ __('Priority') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($taxClass->classOption as $classOption)
                                <x-taxmodule::tax-class-option-row :$countries :$classOption />
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // if user clicked on this button then trigger tax class option form to submit
        $(document).on("click", ".store-tax-option", function() {
            $("#tax-class-option-form").trigger("submit");
        })

        $(document).on("click", "#select-all-text-class-option", function() {
            let isSelected = $(this).is(":checked");

            $(".tax-option-row-check").each(function() {
                if (isSelected) {
                    $(this).attr("checked", true);
                } else {
                    $(this).attr("checked", false);
                }
            });
        })

        // fetch all states according to selected country
        $(document).on("change", "#country_id", function() {
            let el = $(this);
            let country_id = el.val();

            // send request for fetching tax class option data
            send_ajax_request("get", '', "{{ route('country.state.info.ajax') }}?id=" + country_id, () => {}, (
                data) => {
                el.parent().parent().find("#state_id").html(data);
            }, (errors) => prepare_errors(errors))
        });

        // fetch all cities according to selected state
        $(document).on("change", "#state_id", function() {
            let el = $(this);
            let state_id = el.val();

            // send request for fetching tax class option data
            send_ajax_request("get", '', "{{ route('state.city.info.ajax') }}?id=" + state_id, () => {}, (
                data) => {
                el.parent().parent().find("#city_id").html(data);
            }, (errors) => prepare_errors(errors))
        });


        // this method will add new row on tax class option
        $(document).on("click", ".add-tax-option", function() {
            let tr = `<x-taxmodule::tax-class-option-row :$countries />`;

            $('#tax-option-table tbody').append(tr);
        });

        // this method will remove a row from table tbody
        $(document).on("click", ".remove-tax-option", function() {
            // first need to get all selected tax option first
            $("#tax-option-row-check:checked").each(function() {
                $(this).parent().parent().remove();
            });
        });
    </script>
@endsection
