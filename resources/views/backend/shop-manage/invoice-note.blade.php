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
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Invoice Note') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order">
                        <form action="{{ route('admin.shop-manage.invoice-note') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="single-input">
                                        <label class="label-title">{{ __('Invoice Note') }} </label>
                                        <textarea rows="6" name="invoice_note" placeholder="{{ __('Write Invoice Note') }}">{{ get_static_option('admin_invoice_note') }}</textarea>
                                        <small>{{ __('Adding a line break is as easy as adding') }}
                                            <b>&#64;break</b></small>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-wrapper mt-4">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Update Shop') }}</button>
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
