@extends('backend.admin-master')

@section('site-title', __('Vendor settings page'))

@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <x-msg.error />
        <x-msg.flash />
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Global Vendor commission Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        <form action="{{ route('admin.vendor.commission-settings') }}" class="action" method="post"
                            id="global_vendor_commission_settings">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="system_type">{{ __('Select system type') }}</label>
                                <select name="system_type" id="system_type" class="form-control">
                                    <option {{ get_static_option('system_type') == 'commission' ? 'selected' : '' }}
                                        value="commission">{{ __('Commission') }}</option>
                                    <option disabled
                                        {{ get_static_option('system_type') == 'subscription' ? 'selected' : '' }}
                                        value="subscription">{{ __('Subscription') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="commission_type">{{ __('Select commission type') }}</label>
                                <select name="commission_type" id="commission_type" class="form-control">
                                    <option value="">{{ __('Select an option') }}</option>
                                    <option {{ get_static_option('commission_type') == 'fixed_amount' ? 'selected' : '' }}
                                        value="fixed_amount">{{ __('Fixed amount') }}</option>
                                    <option {{ get_static_option('commission_type') == 'percentage' ? 'selected' : '' }}
                                        value="percentage">{{ __('Percentage') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">{{ __('Write amount.') }}</label>
                                <input value="{{ get_static_option('commission_amount') }}" type="number"
                                    class="form-control form-control-sm" name="commission_amount" id="amount"
                                    placeholder="{{ __('Write amount hare.') }}" />
                            </div>

                            <div class="form-group">
                                <input type="submit" value="{{ __('Update vendor settings') }}"
                                    class="cmn_btn btn_bg_profile">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Individual Vendor commission Settings') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        <form action="{{ route('admin.vendor.individual-commission-settings') }}" method="POST"
                            id="individual_vendor_commission_settings">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="commission_type">{{ __('Select a vendor') }}</label>
                                <select name="vendor_id" id="vendor_id" class="form-control select2">
                                    <option value="">{{ __('Select an vendor') }}</option>
                                    @foreach ($vendor as $v_item)
                                        <option value="{{ $v_item->id }}">
                                            {{ $v_item->owner_name . ' , ' . $v_item->username }}</option>
                                    @endforeach
                                </select>
                            </div>

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
    </div>
@endsection

@section('script')
    <script>
        $(".select2").select2();

        $(document).on("submit", "#global_vendor_commission_settings", function(e) {
            e.preventDefault();

            send_ajax_request("post", new FormData(e.target), $(this).attr("action"), () => {
                toastr.warning("{{ __('Global settings updating request is sent.') }}");
            }, (response) => {
                ajax_toastr_success_message(response)
            }, (errors) => {
                ajax_toastr_error_message(errors)
            });
        });

        $(document).on("change", "#vendor_id", function() {
            let vendor_id = $(this).val();

            send_ajax_request("GET", null, "{{ route('admin.vendor.get-vendor-commission-information') }}/" +
                vendor_id, () => {

                }, (response) => {
                    $(this).parent().parent().find("#commission_type option[value=" + response.commission_type +
                        "]").attr("selected", true);
                    $(this).parent().parent().find("#amount").val(response.commission_amount);
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
