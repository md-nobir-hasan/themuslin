@extends('backend.admin-master')
@section('site-title')
    {{ __('Vendor List') }}
@endsection
@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Vendor List') }}</h4>
                    </div>
                    <div class="dashboard__card__body dashboard-recent-order mt-4">
                        <div class="table-wrap dashboard-table">
                            <div class="table-responsive table-responsive--md">
                                <table class="custom--table pt-4" id="myTable">
                                    <thead class="head-bg">
                                        <tr>
                                            <th> {{ __('SL NO:') }} </th>
                                            <th class="min-width-100"> {{ __('Vendor Info') }} </th>
                                            <th class="min-width-250"> {{ __('Shop Info') }} </th>
                                            <th class="min-width-100"> {{ __('Status') }} </th>
                                            <th> {{ __('Actions') }} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vendors as $vendor)
                                            <tr class="table-cart-row">
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="price-td" data-label="Name">
                                                    <div class="vendorList__item">
                                                        <span class="vendorList__label vendor-label">{{ __('Name:') }}
                                                        </span>
                                                        <span class="vendorList__value vendor-value">
                                                            {{ $vendor->owner_name }}</span>
                                                    </div>
                                                    <div class="vendorList__item">
                                                        <span class="vendorList__label vendor-label">{{ __('Email:') }}
                                                        </span>
                                                        <span class="vendorList__value vendor-value">
                                                            {{ $vendor->vendor_shop_info?->email }}</span>
                                                    </div>
                                                    <div class="vendorList__item">
                                                        <span
                                                            class="vendorList__label vendor-label">{{ __('Business Type:') }}
                                                        </span>
                                                        <span class="vendorList__value vendor-value">
                                                            {{ $vendor->business_type?->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="price-td" data-label="Owner Name">
                                                    <div class="vendorList__flex">
                                                        <div class="vendorList__thumb">
                                                            {!! \App\Http\Services\Media::render_image($vendor?->vendor_shop_info?->logo, attribute: "style='width:80px'") !!}
                                                        </div>
                                                        <div class="vendorList__inner">
                                                            <div class="vendorList__item">
                                                                <span
                                                                    class="vendorList__label vendor-label">{{ __('Shop Name:') }}
                                                                </span>
                                                                <span class="vendorList__value vendor-value">
                                                                    {{ $vendor->business_name }}</span>
                                                            </div>
                                                            <div class="vendorList__item">
                                                                <span
                                                                    class="vendorList__label vendor-label">{{ __('Shop Number:') }}
                                                                </span>
                                                                <span class="vendorList__value vendor-value">
                                                                    {{ $vendor->vendor_shop_info?->number }}</span>
                                                            </div>
                                                            @if (!empty($vendor->commission_type))
                                                                <div class="vendorList__item">
                                                                    <b class="vendorList__label vendor-label">{{ __('Commission Type:') }}
                                                                    </b>
                                                                    <b class="vendorList__value vendor-value">
                                                                        {{ $vendor->commission_type }}</b>
                                                                </div>
                                                                <div class="vendorList__item">
                                                                    <b class="vendorList__label vendor-label">{{ __('Commission Amount:') }}
                                                                    </b>
                                                                    <b class="vendorList__value vendor-value">
                                                                        {{ $vendor->commission_amount }}</b>
                                                                </div>
                                                                <div class="vendorList__item">
                                                                    <b class="vendorList__label vendor-label">{{ __('Update Commission:') }}
                                                                    </b>
                                                                    <button data-vendor-id="{{ $vendor->id }}"
                                                                        class="btn btn-sm btn-info update-individual-commission"
                                                                        data-bs-target="#vendor-commission"
                                                                        data-bs-toggle="modal">
                                                                        <i class="las la-pen"></i>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-label="Status">
                                                    <div class="status-dropdown">
                                                        <select data-vendor-id="{{ $vendor->id }}" name="status"
                                                            id="vendor-status" class="form-control form-control-sm">
                                                            {!! status_option() !!}
                                                        </select>
                                                    </div>
                                                </td>

                                                <td data-label="Actions">
                                                    <div class="action-icon">
                                                        @can('vendor-details')
                                                            <a href="#1" data-id="{{ $vendor->id }}"
                                                                class="icon vendor-detail" data-bs-toggle="modal"
                                                                data-bs-target="#vendor-details">
                                                                <i class="las la-eye"></i>
                                                            </a>
                                                        @endcan

                                                        @can('vendor-edit')
                                                            <a href="{{ route('admin.vendor.edit', $vendor->id) }}"
                                                                class="icon"> <i class="las la-pen-alt"></i> </a>
                                                        @endcan

                                                        @can('vendor-delete')
                                                            <a data-vendor-url="{{ route('admin.vendor.delete', $vendor->id) }}"
                                                                href="#1" class="icon delete-row"> <i
                                                                    class="las la-trash-alt"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="pagination">
                                    {{ $vendors->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('vendor-details')
        <!-- Modal -->
        <div class="modal fade" id="vendor-details" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Vendor Details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('vendor-individual-commission-settings')
        <!-- Modal -->
        <div class="modal fade" id="vendor-commission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content custom__form">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorCommission">{{ __('Vendor Commission') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.vendor.individual-commission-settings') }}"
                            id="individual_vendor_commission_settings" method="post">
                            @csrf
                            @method('PUT')
                            <input type="hidden" value="" name="vendor_id" id="vendor_id" />

                            <div class="form-group">
                                <label for="commission_type">{{ __('Select commission type') }}</label>
                                <select name="commission_type" id="commission_type" class="form-control">
                                    <option value="">{{ __('Select an option') }}</option>
                                    <option value="fixed_amount">{{ __('Fixed amount') }}</option>
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">{{ __('Write percentage.') }}</label>
                                <input class="form-control form-control-sm" type="number" name="commission_amount"
                                    id="amount" placeholder="{{ __('Write percentage hare.') }}" />
                            </div>

                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __('Update vendor settings') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="body-overlay-desktop"></div>
@endsection
@section('script')
    <script>
        $(document).on("click", ".vendor-detail", function(e) {
            let data = new FormData(),
                id = $(this).data("id");
            data.append("id", id);
            data.append("_token", "{{ csrf_token() }}");

            send_ajax_request("post", data, "{{ route('admin.vendor.show') }}", () => {
                // before send request
            }, (data) => {
                // receive success response
                $("#vendor-details .modal-body").html(data);
            }, (data) => {
                prepare_errors(data);
            })
        });

        $(document).on("click", ".status-dropdown .list li", function() {
            if (confirm("Are you sure to change this vendor status?")) {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("status_id", $(this).parent().parent().parent().find("#vendor-status").val());
                data.append("vendor_id", $(this).parent().parent().parent().find("#vendor-status").data(
                    "vendor-id"));

                send_ajax_request("post", data, "{{ route('admin.vendor.update-status') }}", () => {
                    // before send request
                    toastr.warning("Request send please wait while");
                }, (data) => {
                    // receive success response
                    toastr.success("Vendor Status Changed Successfully");
                }, (data) => {
                    prepare_errors(data);
                })
            } else {
                return "";
            }
        });

        $(document).on("submit", "#individual_vendor_commission_settings", function(e) {
            e.preventDefault();
            let data = new FormData(e.target);

            send_ajax_request("post", data, $(this).attr("action"), () => {
                toastr.warning('{{ __('Individual commission updating request is sent.') }}');
            }, (response) => {
                ajax_toastr_success_message(response)
            }, (errors) => {
                ajax_toastr_error_message(errors)
            });
        });

        $(document).on("click", ".update-individual-commission", function() {
            let vendor_id = $(this).attr("data-vendor-id");
            $("#individual_vendor_commission_settings  #vendor_id").val(vendor_id)

            send_ajax_request("GET", null, "{{ route('admin.vendor.get-vendor-commission-information') }}/" +
                vendor_id, () => {

                }, (response) => {
                    $("#individual_vendor_commission_settings #commission_type option[value=" + response
                        .commission_type + "]").attr("selected", true);
                    $("#individual_vendor_commission_settings  #amount").val(response.commission_amount);
                }, (errors) => {
                    ajax_toastr_error_message(errors)
                });
        });

        $(document).on("submit", "#individual_vendor_commission_settings", function(e) {
            e.preventDefault();

            send_ajax_request("post", new FormData(e.target), $(this).attr("action"), () => {
                toastr.warning('{{ __('Individual commission updating request is sent.') }}');
            }, (response) => {
                ajax_toastr_success_message(response)
            }, (errors) => {
                ajax_toastr_error_message(errors)
            });
        });
    </script>
@endsection
