@extends('backend.admin-master')
@section('site-title', __(' Coupon'))
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-niceselect.css />
    <style>
        #form_category,
        #edit_#form_category,
        #form_subcategory,
        #edit_#form_subcategory,
        #form_childcategory,
        #edit_#form_childcategory,
        #form_products,
        #edit_#form_products {
            display: none;
        }

        .lds-ellipsis {
            position: fixed;
            width: 80px;
            height: 80px;
            left: 50vw;
            top: 40vh;
            z-index: 50;
            display: none;
        }

        .lds-ellipsis div {
            position: absolute;
            top: 33px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: {{ get_static_option('site_color') }};
            animation-timing-function: cubic-bezier(0, 1, 1, 0);
        }

        .lds-ellipsis div:nth-child(1) {
            left: 8px;
            animation: lds-ellipsis1 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(2) {
            left: 8px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(3) {
            left: 32px;
            animation: lds-ellipsis2 0.6s infinite;
        }

        .lds-ellipsis div:nth-child(4) {
            left: 56px;
            animation: lds-ellipsis3 0.6s infinite;
        }

        @keyframes lds-ellipsis1 {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes lds-ellipsis3 {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(0);
            }
        }

        @keyframes lds-ellipsis2 {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(24px, 0);
            }
        }

        /*.select2-dropdown ,*/
        .select2-container {
            z-index: 1072;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-7">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All  Coupon') }}</h4>
                        @can('product-coupon-delete')
                            <x-bulk-action.dropdown />
                        @endcan
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th>{{ __('Expire Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_product_coupon as $data)
                                        <tr>
                                            <x-bulk-action.td :id="$data->id" />
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->code }}</td>
                                            <td>
                                                @if ($data->discount_type == 'percentage')
                                                    {{ $data->discount }}%
                                                @else
                                                    {{ amount_with_currency_symbol($data->discount) }}
                                                @endif
                                            </td>
                                            <td>{{ date('d M Y', strtotime($data->expire_date)) }}</td>
                                            <td>
                                                <x-status-span :status="$data->status" />
                                            </td>
                                            <td>
                                                @can('product-coupon-delete')
                                                    <x-table.btn.swal.delete :route="route('admin.products.coupon.delete', $data->id)" />
                                                @endcan
                                                @can('product-coupon-edit')
                                                    <a href="#1" data-bs-toggle="modal"
                                                        data-bs-target="#category_edit_modal"
                                                        class="btn btn-sm btn-primary btn-xs mb-2 me-1 category_edit_btn"
                                                        data-id="{{ $data->id }}" data-title="{{ $data->title }}"
                                                        data-code="{{ $data->code }}"
                                                        data-discount_on="{{ $data->discount_on }}"
                                                        data-discount_on_details="{{ $data->discount_on_details }}"
                                                        data-discount="{{ $data->discount }}"
                                                        data-discount_type="{{ $data->discount_type }}"
                                                        data-expire_date="{{ $data->expire_date }}"
                                                        data-status="{{ $data->status }}">
                                                        <i class="ti-pencil"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @can('product-coupon-create')
                <div class="col-lg-5">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h4 class="dashboard__card__title">{{ __('Add New Coupon') }}</h4>
                        </div>
                        <div class="dashboard__card__body custom__form mt-4">
                            <form action="{{ route('admin.products.coupon.new') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="title">{{ __('Coupon Title') }}</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="{{ __('Title') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="code">{{ __('Coupon Code') }}</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        placeholder="{{ __('Code') }}" required>
                                    <span id="status_text" class="text-danger" style="display: none"></span>
                                </div>
                                <div class="form-group">
                                    <label for="discount_on">{{ __('Discount On') }}</label>
                                    <select name="discount_on" id="discount_on" class="form-control">
                                        <option value="">{{ __('Select an option') }}</option>
                                        @foreach ($coupon_apply_options as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_category">
                                    <label for="category">{{ __('Category') }}</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">{{ __('Select a Category') }}</option>
                                        @foreach ($all_categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_subcategory">
                                    <label for="subcategory">{{ __('Subcategory') }}</label>
                                    <select name="subcategory" id="subcategory" class="form-control">
                                        <option value="">{{ __('Select a Subcategory') }}</option>
                                        @foreach ($all_subcategories as $key => $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_childcategory">
                                    <label for="childcategory">{{ __('childcategory') }}</label>
                                    <select name="childcategory" id="childcategory" class="form-control">
                                        <option value="">{{ __('Select a Child category') }}</option>
                                        @foreach ($all_childcategories as $key => $childcategory)
                                            <option value="{{ $childcategory->id }}">{{ $childcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="form_products">
                                    <label for="products">{{ __('Products') }}</label>
                                    <select name="products[]" id="products" class="form-control wide" multiple>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="discount">{{ __('Discount') }}</label>
                                    <input type="number" class="form-control" id="discount" name="discount"
                                        placeholder="{{ __('Discount') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="discount_type">{{ __('Coupon Type') }}</label>
                                    <select name="discount_type" class="form-control" id="discount_type" required>
                                        <option value="percentage">{{ __('Percentage') }}</option>
                                        <option value="amount">{{ __('Amount') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="expire_date">{{ __('Expire Date') }}</label>
                                    <input type="date" class="form-control flatpickr" id="expire_date" name="expire_date"
                                        placeholder="{{ __('Expire Date') }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="status">{{ __('Status') }}</label>
                                    <select name="status" class="form-control" id="status" required>
                                        <option value="publish">{{ __('Publish') }}</option>
                                        <option value="draft">{{ __('Draft') }}</option>
                                    </select>
                                </div>
                                <button type="submit" id="coupon_create_btn" class="cmn_btn btn_bg_profile">
                                    {{ __('Add New Coupon') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    @can('product-coupon-edit')
        <div class="modal fade" id="category_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Coupon') }}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{ route('admin.products.coupon.update') }}" method="post">
                        <input type="hidden" name="id" id="coupon_id">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="title">{{ __('Coupon Title') }}</label>
                                <input type="text" class="form-control" id="edit_title" name="title"
                                    placeholder="{{ __('Title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_code">{{ __('Coupon Code') }}</label>
                                <input type="text" class="form-control" id="edit_code" name="code"
                                    placeholder="{{ __('Code') }}">
                                <span id="status_text" class="text-danger" style="display: none"></span>
                            </div>
                            <div class="form-group">
                                <label for="discount_on">{{ __('Discount On') }}</label>
                                <select name="discount_on" id="edit_discount_on" class="form-control">
                                    <option value="">{{ __('Select an option') }}</option>
                                    @foreach ($coupon_apply_options as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_category">
                                <label for="edit_category">{{ __('Category') }}</label>
                                <select name="category" id="edit_category" class="form-control">
                                    <option value="">{{ __('Select a Category') }}</option>
                                    @foreach ($all_categories as $key => $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_subcategory">
                                <label for="edit_subcategory">{{ __('Subcategory') }}</label>
                                <select name="subcategory" id="edit_subcategory" class="form-control">
                                    <option value="">{{ __('Select a Subcategory') }}</option>
                                    @foreach ($all_subcategories as $key => $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="edit_form_childcategory">
                                <label for="edit_childcategory">{{ __('Subcategory') }}</label>
                                <select name="childcategory" id="edit_childcategory" class="form-control">
                                    <option value="">{{ __('Select a Child category') }}</option>
                                    @foreach ($all_childcategories as $childcategory)
                                        <option value="{{ $childcategory->id }}">{{ $childcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="edit_form_products">
                                <label for="products">{{ __('Products') }}</label>
                                <select name="products[]" id="products" class="form-control wide" multiple>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_discount">{{ __('Discount') }}</label>
                                <input type="number" class="form-control" id="edit_discount" name="discount"
                                    placeholder="{{ __('Discount') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_discount_type">{{ __('Coupon Type') }}</label>
                                <select name="discount_type" class="form-control" id="edit_discount_type">
                                    <option value="percentage">{{ __('Percentage') }}</option>
                                    <option value="amount">{{ __('Amount') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_expire_date">{{ __('Expire Date') }}</label>
                                <input type="date" class="form-control flatpickr" id="edit_expire_date"
                                    name="expire_date" placeholder="{{ __('Expire Date') }}">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">{{ __('Status') }}</label>
                                <select name="status" class="form-control" id="edit_status">
                                    <option value="draft">{{ __('Draft') }}</option>
                                    <option value="publish">{{ __('Publish') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>
                            <button type="submit" class="btn btn-primary">{{ __('Save Change') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    <div class="lds-ellipsis">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.products.coupon.bulk.action')" />
    <x-niceselect.js />

    <script>
        $(document).ready(function() {
            flatpickr(".flatpickr", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });

            $(document).on('click', '.category_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let status = el.data('status');
                let modal = $('#category_edit_modal');
                let discount_on = el.data('discount_on');
                let discount_on_details = el.data('discount_on_details');

                modal.find('#coupon_id').val(id);
                modal.find('#edit_status option[value="' + status + '"]').attr('selected', true);
                modal.find('#edit_code').val(el.data('code'));
                modal.find('#edit_discount').val(el.data('discount'));
                modal.find('#edit_discount_type').val(el.data('discount_type'));
                modal.find('#edit_expire_date').val(el.data('expire_date'));
                modal.find('#edit_discount_type[value="' + el.data('discount_type') + '"]').attr('selected',
                    true);
                modal.find('#edit_title').val(el.data('title'));
                modal.find('#edit_discount_on').val(el.data('discount_on'));

                $('#edit_form_category').hide();
                $('#edit_form_subcategory').hide();
                $("#edit_form_childcategory").hide();
                $('#edit_form_products').hide();

                if (discount_on == 'product') {
                    $('#edit_form_products').hide();
                    loadProductDiscountHtml($('#edit_discount_on'), '#edit_form_products select', true,
                        discount_on_details);
                } else {
                    $('#edit_form_' + discount_on + ' option[value=' + discount_on_details + ']').attr(
                        'selected', true);
                    $('#edit_form_' + discount_on).show();
                }
            });

            $('#code').on('keyup', function() {
                validateCoupon(this);
            });

            $('#edit_code').on('keyup', function() {
                validateCoupon(this);
            });

            $('#discount_on').on('change', function() {
                loadProductDiscountHtml(this, '#form_products select', false, []);
            });

            $('#edit_discount_on').on('change', function() {
                loadProductDiscountHtml(this, '#edit_form_products select', true, []);
            });
        });

        function loadProductDiscountHtml(context, target_selector, is_edit, values) {
            let product_select = $(target_selector);

            let selector_prefix = '';

            if (is_edit) {
                selector_prefix = 'edit_';
            }

            $('#' + selector_prefix + 'form_category').hide();
            $('#' + selector_prefix + 'form_subcategory').hide();
            $('#' + selector_prefix + 'form_childcategory').hide();
            $('#' + selector_prefix + 'form_products').hide();

            if ($(context).val() == 'category') {
                $('#' + selector_prefix + 'form_category').show(500);
            } else if ($(context).val() == 'subcategory') {
                $('#' + selector_prefix + 'form_subcategory').show(500);
            } else if ($(context).val() == 'childcategory') {
                $('#' + selector_prefix + 'form_childcategory').show(500);
            } else if ($(context).val() == 'product') {
                $('.lds-ellipsis').show();
                $.get('{{ route('admin.products.coupon.products') }}').then(function(data) {
                    $('.lds-ellipsis').hide();

                    let options = '';
                    let discountd_products = [];

                    if (values.length) {
                        discountd_products = values;
                    }

                    if (data.length) {
                        data.forEach(function(product) {
                            let selected_class = '';

                            if (discountd_products.indexOf(product.id) > -1 || discountd_products.indexOf(
                                    String(product.id)) > -1) {
                                selected_class = 'selected';
                            }
                            options += '<option value="' + product.id + '" ' + selected_class + '>' +
                                product.title + '</option>';
                        });

                        product_select.html('');
                        product_select.html(options);
                        product_select.parent().show(500);
                        product_select.addClass('nice-select')

                        if ($('.nice-select').length) {
                            if ($('.nice-select.form-control.wide.has-multiple').length) {
                                $('.nice-select.form-control.wide.has-multiple').remove();
                            }
                            $('.nice-select').niceSelect();
                        }
                    }
                }).catch(function(err) {
                    $('.lds-ellipsis').hide();
                });
            }
        }

        function validateCoupon(context) {
            let code = $(context).val();
            let submit_btn = $(context).closest('form').find('button[type=submit]');
            let status_text = $(context).siblings('#status_text');
            status_text.hide();

            if (code.length) {
                submit_btn.prop("disabled", true);

                $.get("{{ route('admin.products.coupon.check') }}", {
                    code: code
                }).then(function(data) {
                    if (data > 0) {
                        let msg = "{{ __('This coupon is already taken') }}";
                        status_text.removeClass('text-success').addClass('text-danger').text(msg).show();
                        submit_btn.prop("disabled", true);
                    } else {
                        let msg = "{{ __('This coupon is available') }}";
                        status_text.removeClass('text-danger').addClass('text-success').text(msg).show();
                        submit_btn.prop("disabled", false);
                    }
                });
            }
        }
    </script>
@endsection
