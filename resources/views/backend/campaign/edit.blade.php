@extends('backend.admin-master')
@section('site-title')
    {{ __('Update Campaign') }}
@endsection
@section('style')
    <x-media.css />
    <x-niceselect.css />
    <link rel="stylesheet" href="{{ asset('assets/backend/css/flatpickr.min.css') }}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Update Campaign') }}</h4>
                        @can('campaign-list')
                            <div class="btn-wrapper">
                                <a href="{{ route('admin.campaigns.all') }}"
                                    class="cmn_btn btn_bg_profile">{{ __('All Campaigns') }}</a>
                                <a href="{{ route('frontend.products.campaign', $campaign->id) }}" target="_blank"
                                    class="cmn_btn btn-primary">{{ __('View Campaign') }}</a>
                            </div>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        @can('campaign-edit')
                            <form action="{{ route('admin.campaigns.update') }}" method="POST">
                                @csrf
                                <div class="row g-4 new_campaign mt-3">
                                    <div class="col-md-4">
                                        <div class="dashboard__card">
                                            <div class="dashboard__card__header">
                                                <h4 class="dashboard__card__title">{{ __('Update Info') }}</h4>
                                            </div>
                                            <div class="dashboard__card__body custom__form mt-4">
                                                <div class="form-group">
                                                    <input type="hidden" name="id" value="{{ $campaign->id }}">
                                                    <label for="campaign_name">{{ __('Campaign Name') }}</label>
                                                    <input type="text" class="form-control" id="campaign_name"
                                                        name="campaign_name" placeholder="Campaign Name"
                                                        value="{{ $campaign->title }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="campaign_subtitle">{{ __('Campaign Subtitle') }}</label>
                                                    <input type="text" class="form-control" id="campaign_subtitle"
                                                        name="campaign_subtitle" placeholder="Campaign Subtitle"
                                                        value="{{ html_entity_decode($campaign->subtitle) }}">
                                                </div>

                                                <x-media-upload :title="__('Campaign Image')" :id="$campaign->image" :name="'image'"
                                                    :dimentions="'1920x1080'" />

                                                <div class="form-group">
                                                    <label for="campaign_status">{{ __('Campaign Status') }}</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="draft"
                                                            @if ($campaign->status == 'draft') selected @endif>
                                                            {{ __('Draft') }}</option>
                                                        <option value="publish"
                                                            @if ($campaign->status == 'publish') selected @endif>
                                                            {{ __('Publish') }}</option>
                                                    </select>
                                                </div>

                                                <div class="form-group mt-5">
                                                    <input type="checkbox" id="set_fixed_percentage">
                                                    <label for="set_fixed_percentage">{{ __('Set Fixed Percentage') }}</label>
                                                    <p class="text-small">
                                                        {{ __('when you set fixed percentage, you have to click on sync price button, to sync price selection with all prodcuts') }}
                                                    </p>
                                                    <div id="fixe_price_cut_container" style="display: none">
                                                        <input type="number" id="fixed_percentage_amount"
                                                            class="form-control mb-2"
                                                            placeholder="{{ __('Price Cut Percentage') }}">
                                                        <button type="button" class="btn btn-sm btn-primary mb-2"
                                                            id="fixed_price_sync_all">{{ __('Sync Price') }}</button>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <input type="checkbox" id="set_fixed_date"
                                                        @if ($campaign->start_date || $campaign->end_date) checked @endif>
                                                    <label for="set_fixed_date">{{ __('Set Fixed Date') }}</label>
                                                    <p class="text-small">
                                                        {{ __('when you set fixed date, you have to click on sync date button, to sync date selection with all prodcuts') }}
                                                    </p>
                                                    <div id="fixed_date_container"
                                                        @if (!$campaign->start_date && !$campaign->end_date) style="display: none" @endif>
                                                        <input type="text" name="campaign_start_date" id="fixed_from_date"
                                                            class="form-control mb-2 flatpickr"
                                                            placeholder="{{ __('From Date') }}"
                                                            value="{{ $campaign->start_date }}">
                                                        <input type="text" name="campaign_end_date" id="fixed_to_date"
                                                            class="form-control mb-2 flatpickr"
                                                            placeholder="{{ __('To Date') }}"
                                                            value="{{ $campaign->end_date }}">
                                                        <button type="button" class="cmn_btn btn_bg_profile"
                                                            id="fixed_date_sync_all">{{ __('Sync Date') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div id="product_repeater_container">
                                                    @if ($campaign->products->count() == 0)
                                                        <?php
                                                        $remove_btn = true;
                                                        ?>
                                                        @include('backend.campaign.add_new_campaign_product')
                                                    @endif

                                                    @if ($campaign->products)
                                                        @foreach ($campaign->products as $campaign_product)
                                                            <div class="dashboard__card">
                                                                <div class="dashboard__card__header">
                                                                    <h4 class="dashboard__card__title">
                                                                        {{ __('Campaign Product') }}</h4>
                                                                    <span class="delete-campaign"><i
                                                                            class="las la-times-circle"></i></span>
                                                                </div>
                                                                <div class="dashboard__card__body custom__form mt-4">
                                                                    <div class="form-group select_product">
                                                                        <label
                                                                            for="product_id">{{ __('Select Product') }}</label>
                                                                        <input type="hidden" name="campaign_product_id[]"
                                                                            class="campaign_product_id"
                                                                            value="{{ $campaign_product->id }}">
                                                                        <input type="hidden" name="product_id[]"
                                                                            class="product_id"
                                                                            value="{{ $campaign_product->product_id }}">
                                                                        <select id="product_id" class="form-control wide">
                                                                            @foreach ($all_products as $product)
                                                                                <option value="{{ $product->id }}"
                                                                                    data-price="{{ $product->price }}"
                                                                                    data-sale_price="{{ $product->sale_price }}"
                                                                                    data-stock="{{ optional($product->inventory)->stock_count ?? 0 }}"
                                                                                    @if ($campaign_product->product_id == $product->id) selected @endif>
                                                                                    {{ $product->title }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="product_original_price">{{ __('Product Original Price') }}</label>
                                                                        <input type="number"
                                                                            class="form-control product_original_price"
                                                                            disabled
                                                                            value="{{ optional($campaign_product->product)->sale_price }}"
                                                                            step="0.01">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="campaign_price">{{ __('Price for Campaign') }}</label>
                                                                        <input type="number" name="campaign_price[]"
                                                                            class="form-control campaign_price"
                                                                            value="{{ $campaign_product->campaign_price }}"
                                                                            step="0.01">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="available_num_of_units">{{ __('No. of Units Available') }}</label>
                                                                        <input type="number"
                                                                            class="form-control available_num_of_units"
                                                                            disabled
                                                                            value="{{ optional(optional($campaign_product->product)->inventory)->stock_count ?? 0 }}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="units_for_sale">{{ __('No. of Units for Sale') }}</label>
                                                                        <input type="number" name="units_for_sale[]"
                                                                            class="form-control units_for_sale"
                                                                            value="{{ $campaign_product->units_for_sale }}">
                                                                    </div>

                                                                    @if (!$campaign->start_date || !$campaign->end_date)
                                                                        style="display: none"
                                                                    @endif>

                                                                    <div class="form-group">
                                                                        <label for="start_date">{{ __('Start Date') }}</label>
                                                                        <input type="text" name="start_date[]"
                                                                            class="form-control start_date flatpickr"
                                                                            value="{{ $campaign_product->start_date ?? $campaign->start_date }}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="end_date">{{ __('End Date') }}</label>
                                                                        <input type="text" name="end_date[]"
                                                                            class="form-control end_date flatpickr"
                                                                            value="{{ $campaign_product->end_date ?? $campaign->end_date }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="btn-wrapper">
                                                    <button type="button" class="cmn_btn btn_bg_profile"
                                                        id="add_product_btn">{{ __('Add Product') }}</button>
                                                </div>
                                            </div>
                                            <div class="co-sm-12">
                                                <button type="submit"
                                                    class="cmn_btn btn_bg_profile">{{ __('Update Campaign') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup />
    <div class="d-none">
        <input type="hidden" id="fixed_percentage">
        <input type="hidden" id="fixed_campaign_start_date">
        <input type="hidden" id="fixed_campaign_end_date">
    </div>
@endsection
@section('script')
    <x-media.js />
    <script src="{{ asset('assets/backend/js/flatpickr.js') }}"></script>
    <x-niceselect.js />
    <script>
        (function($) {
            $(document).ready(function() {
                flatpickr(".flatpickr", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                });

                if ($('.nice-select').length > 0) {
                    $('.nice-select').niceSelect();
                }

                $(document).on('click', '.cross-btn', function() {
                    let container = $(this).closest('.card');
                    container.slideUp('slow');
                    setTimeout(() => container.remove(), 1000);
                });

                $('#set_fixed_percentage').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#fixe_price_cut_container').slideDown('500')
                    } else {
                        $('#fixe_price_cut_container').slideUp('500');
                        setTimeout(function() {
                            $('#fixed_percentage_amount').val('');
                        }, 500);
                    }
                });

                $('#set_fixed_date').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#fixed_date_container').slideDown(500);
                    } else {
                        $('#fixed_date_container').slideUp(500);
                        setTimeout(function() {
                            $('#fixed_date_container input').val('');
                        }, 500);
                    }
                });

                $('#fixed_price_sync_all').on('click', function() {
                    let fixed_percentage = $('#fixed_percentage_amount').val().trim();

                    if (!fixed_percentage.length) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: '{{ __('Set percentage first') }}',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }

                    let all_prices = $('.product_original_price');
                    for (let i = 0; i < all_prices.length; i++) {
                        let price_container = $(all_prices[i]).closest('.col');
                        let final_price_container = price_container.next();
                        let product_price = $(all_prices[i]).val().trim();
                        let price_after_percentage = product_price - (product_price / 100 *
                            fixed_percentage);
                        price_after_percentage = price_after_percentage.toFixed(0);
                        final_price_container.find('.campaign_price').val(price_after_percentage);
                    }
                });

                $('#fixed_date_sync_all').on('click', function() {
                    if ($('#set_fixed_date').is(':checked')) {
                        let from_date = $('#fixed_from_date').val();
                        let to_date = $('#fixed_to_date').val();

                        $('.start_date.flatpickr-input').val(from_date);
                        $('.end_date.flatpickr-input').val(to_date);

                        flatpickr(".flatpickr", {
                            altInput: true,
                            altFormat: "F j, Y",
                            dateFormat: "Y-m-d",
                        });
                    } else {
                        Swal.fire({
                            position: 'top-start',
                            icon: 'warning',
                            title: '{{ __('Set fixed date first') }}',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });

                $('.delete-campaign').on('click', function() {
                    let container = $(this).closest('.card');
                    let campaign_id = container.find('input.campaign_product_id').val();

                    Swal.fire({
                        title: "{{ __('Do you want to delete this campaign?') }}",
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        confirmButtonColor: '#dd3333',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('{{ route('admin.campaigns.delete.product') }}', {
                                _token: '{{ csrf_token() }}',
                                id: campaign_id
                            }).then(function(data) {
                                if (data) {
                                    Swal.fire('Deleted!', '', 'success');
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            });
                        }
                    });
                });

                $('#add_product_btn').on('click', function() {
                    let product_repeater_container = $('#product_repeater_container');
                    let remove_button_selector = '.delete-campaign';
                    let from_date = undefined;
                    let to_date = undefined;
                    let new_element = product_repeater_container.find('.card').last().clone();

                    if ($('#set_fixed_date').is(':checked')) {
                        from_date = $('#fixed_from_date').val();
                        to_date = $('#fixed_to_date').val();
                    }

                    if (from_date) {
                        new_element.find('.start_date.input').val(from_date);
                    }

                    if (to_date) {
                        new_element.find('.end_date.input').val(to_date);
                    }

                    let remove_btn = new_element.find(remove_button_selector);

                    remove_btn.removeClass(remove_button_selector);
                    remove_btn.addClass('cross-btn');

                    new_element.find('.start_date.input').remove();
                    new_element.find('.end_date.input').remove();

                    new_element.find('.campaign_price').val('');
                    new_element.find('.units_for_sale').val('');

                    product_repeater_container.append(new_element.hide());
                    new_element.slideDown('slow');

                    flatpickr(".flatpickr", {
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                    });

                    product_repeater_container.find('.nice-select').niceSelect('destroy');
                    product_repeater_container.find('.nice-select').niceSelect();
                });
            });



            $(document).on('change', '.select_product select', function() {
                let selected_product_id = $(this).val();
                let container = $(this).closest('.card');
                let original_price_field = container.find('.original_price');
                // let campaign_price_field = container.find('.product_original_price');
                $(this).prev().val(selected_product_id);
                let data = $(this).find('option:checked').data();
                let product_price = data['sale_price'];

                $(this).closest('.card-body').find('.available_num_of_units').val(data['stock']);

                $(this).closest('.card-body').find('.product_original_price').val(product_price);

                if ($('#set_fixed_percentage').is(':checked')) {
                    let percentage = $('#fixed_percentage_amount').val().trim();
                    let price_after_percentage = product_price - (product_price / 100 * percentage);

                    campaign_price_field.val(price_after_percentage);
                }
            });
        })(jQuery)
    </script>
@endsection
