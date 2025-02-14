@extends('backend.admin-master')
@section('site-title')
    {{ __('Product Inventory') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="col-12">
            <div class="dashboard__card">
                <div class="dashboard__card__header">
                    <h4 class="dashboard__card__title">{{ __('All Product Orders') }}</h4>
                    <div class="dashboard__card__header__right">
                        <div class="bulk-delete-wrapper">
                            <div class="select-box-wrap">
                                <select name="bulk_option" id="bulk_option">
                                    <option value="">{{ __('Bulk Action') }}</option>
                                    <option value="delete">{{ __('Delete') }}</option>
                                </select>
                                <button class="btn btn-primary btn-sm" id="bulk_delete_btn">{{ __('Apply') }}</button>
                            </div>
                        </div>
                        @can('product-order-create')
                            <div class="righ-wrap">
                                <a href="{{ route('admin.product.order.new') }}" class="btn btn-primary">
                                    {{ __('Create An Order') }}
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
                <div class="dashboard__card__body mt-4">
                    <x-msg.error />
                    <x-msg.flash />
                    <div class="data-tables datatable-primary table-responsive">
                        <table id="all_user_table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                    <th>{{ __('Order ID') }}</th>
                                    <th>{{ __('Billing Name') }}</th>
                                    <th>{{ __('Billing Email') }}</th>
                                    <th>{{ __('Total Amount') }}</th>
                                    <th>{{ __('Package Gateway') }}</th>
                                    <th>{{ __('Payment Status') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_orders as $data)
                                    <tr>
                                        <td>
                                            <div class="bulk-checkbox-wrapper">
                                                <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]"
                                                    value="{{ $data->id }}">
                                            </div>
                                        </td>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ float_amount_with_currency_symbol($data->total_amount) }}</td>
                                        {{--                                        <td><strong>{{ ucwords(str_replace('_', ' ', $data->payment_gateway)) }}</strong> --}}
                                        <td><strong>{{ ucwords(str_replace('_', ' ', render_payment_gateway_name($data->payment_gateway))) }}</strong>
                                        </td>
                                        <td>
                                            <span
                                                class="alert @if ($data->payment_status == 'pending') alert-warning @else alert-success @endif text-capitalize">{{ $data->payment_status }}</span>
                                        </td>
                                        <td>
                                            @if ($data->status == 'pending')
                                                <span
                                                    class="alert alert-warning text-capitalize">{{ $data->status }}</span>
                                            @elseif($data->status == 'in_progress')
                                                <span
                                                    class="alert alert-info text-capitalize">{{ ucwords(str_replace('_', ' ', $data->status)) }}</span>
                                            @elseif($data->status == 'shipped')
                                                <span class="alert alert-info text-capitalize">{{ $data->status }}</span>
                                            @elseif($data->status == 'complete')
                                                <span
                                                    class="alert alert-success text-capitalize">{{ $data->status }}</span>
                                            @elseif($data->status == 'cancel')
                                                <span class="alert alert-danger text-capitalize">{{ $data->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ date_format($data->created_at, 'd M Y') }}</td>
                                        <td>
                                            @can('product-order-delete')
                                                <x-table.btn.swal.delete :route="route('admin.product.order.payment.delete', $data->id)" />
                                            @endcan
                                            @can('product-order-view-details')
                                                <a href="{{ route('admin.product.order.view', $data->id) }}" target="_blank"
                                                    class="btn btn-xs btn-primary btn-sm mb-2 me-1">
                                                    <i class="ti-eye"></i>
                                                </a>
                                            @endcan
                                            @can('product-order-send-alert-mail')
                                                @if (!empty($data->user_id) && $data->payment_status == 'pending')
                                                    <form action="{{ route('admin.product.order.reminder') }}" method="post"
                                                        class="d-inline-block">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $data->id }}">
                                                        <button class="btn btn-sm btn-secondary btn-xs mb-2 me-1"
                                                            type="submit"><i class="ti-bell"></i></button>
                                                    </form>
                                                @endif
                                            @endcan
                                            @can('product-order-generate-invoice')
                                                <a href="{{ route('frontend.product.invoice.generate') }}"
                                                    data-id="{{ $data->id }}"
                                                    class="btn btn-xs btn-secondary download_invoice mb-2 me-1"
                                                    target="_blank">{{ __('Invoice') }}</a>
                                            @endcan
                                            @can('product-order-update-status')
                                                <a href="#1" data-id="{{ $data->id }}"
                                                    data-status="{{ $data->status }}" data-bs-toggle="modal"
                                                    data-bs-target="#order_status_change_modal"
                                                    class="btn btn-xs btn-info btn-sm mb-2 me-1 order_status_change_btn">
                                                    {{ __('Update Status') }}
                                                </a>
                                            @endcan
                                            @can('product-order-approve-payment')
                                                @if (
                                                    ($data->payment_gateway == 'cash_on_delivery' || $data->payment_gateway == 'manual_payment') &&
                                                        $data->payment_status == 'pending')
                                                    <a tabindex="0" data-id="{{ $data->id }}"
                                                        class="btn btn-xs btn-success btn-sm mb-2 me-1 approve_payment">
                                                        {{ __('Approve Payment') }}
                                                    </a>
                                                @endif
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
    </div>

    <div class="modal fade" id="view_quote_details_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="view-quote-details-info" id="view-quote-details-info">
                    <h4 class="title">{{ __('View Order Details Information') }}</h4>
                    <div class="view-quote-top-wrap">
                        <div class="status-wrap">
                            {{ __('Status:') }} <span class="quote-status-span"></span>
                        </div>
                        <div class="data-wrap">
                            {{ __(' Date:') }} <span class="quote-date-span"></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="quote-all-custom-fields table-striped table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="order_status_change_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom__form">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Order Status Change') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{ route('admin.product.order.status.change') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="order_id">
                        <div class="form-group">
                            <label for="order_status">{{ __('order Status') }}</label>
                            <select name="order_status" class="form-control" id="order_status">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="in_progress">{{ __('In Progress') }}</option>
                                <option value="shipped">{{ __('Shipped') }}</option>
                                <option value="cancel">{{ __('Cancel') }}</option>
                                <option value="complete">{{ __('Complete') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Change Status') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-table.btn.swal.js />
    <x-datatable.js />
    <script>
        (function($) {
            'use strict'

            $(document).ready(function($) {
                $(document).on('click', '#bulk_delete_btn', function(e) {
                    e.preventDefault();

                    let bulkOption = $('#bulk_option').val();
                    let allCheckbox = $('.bulk-checkbox:checked');
                    let allIds = [];
                    allCheckbox.each(function(index, value) {
                        allIds.push($(this).val());
                    });
                    if (allIds != '' && bulkOption == 'delete') {
                        $(this).text('{{ __('Deleting...') }}');
                        $.ajax({
                            'type': "POST",
                            'url': "{{ route('admin.product.order.bulk.action') }}",
                            'data': {
                                _token: "{{ csrf_token() }}",
                                ids: allIds
                            },
                            success: function(data) {
                                location.reload();
                            }
                        });
                    }

                });

                $('.all-checkbox').on('change', function(e) {
                    e.preventDefault();
                    let value = $('.all-checkbox').is(':checked');
                    let allChek = $(this).parent().parent().parent().parent().parent().find(
                        '.bulk-checkbox');
                    //have write code here fr
                    if (value == true) {
                        allChek.prop('checked', true);
                    } else {
                        allChek.prop('checked', false);
                    }
                });

                $(document).on('click', '#genarate_invoice', function(e) {
                    e.preventDefault();

                    let doc = new jsPDF();
                    let elementHTML = $('#pdf_content_wrapper').html();
                    let specialElementHandlers = {
                        '#elementH': function(element, renderer) {
                            return true;
                        }
                    };
                    doc.fromHTML(elementHTML, 15, 15, {
                        'width': 170,
                        'elementHandlers': specialElementHandlers
                    });

                    // Save the PDF
                    doc.save('sample-document.pdf');

                })

                $(document).on('click', '.view_quote_details_btn', function(e) {
                    e.preventDefault();
                    let el = $(this);
                    let allData = el.data();
                    let parent = $('#view_quote_details_modal');
                    let statusClass = allData.status == 'pending' ? 'alert alert-warning' :
                        'alert alert-success';
                    let allProducts = allData.cart_items;
                    parent.find('.quote-status-span').text(allData.status).addClass(statusClass);
                    parent.find('.quote-date-span').text(allData.date);
                    parent.find('.quote-all-custom-fields').html('');
                    $('#invoice_generate_order_field').val(el.data('order_id'));
                    delete allData.date;
                    delete allData.status;
                    delete allData.target;
                    delete allData.toggle;
                    delete allData.order_id;
                    delete allData.cart_items;
                    $.each(allData, function(index, value) {
                        parent.find('.quote-all-custom-fields').append(
                            '<tr><td class="fname">' + index
                            .replace('_', ' ') + '</td> <td class="fvalue">' + value +
                            '</td></tr>');
                    });
                    $.each(allProducts, function(index, value) {
                        parent.find('.quote-all-custom-fields').append(
                            '<tr><td class="fname">Product Name</td> <td class="fvalue">' +
                            value
                            .title + '</td></tr>');
                        parent.find('.quote-all-custom-fields').append(
                            '<tr><td class="fname">Quantity</td> <td class="fvalue">' +
                            value
                            .quantity + '</td></tr>');
                    });
                });

                $('#all_user_table').DataTable({
                    "order": [
                        [1, "desc"]
                    ],
                    'columnDefs': [{
                        'targets': 'no-sort',
                        'orderable': false
                    }]
                });

                $(document).on('click', '.order_status_change_btn', function(e) {
                    e.preventDefault();
                    let el = $(this);
                    let form = $('#order_status_change_modal');
                    form.find('#order_id').val(el.data('id'));
                    form.find('#order_status option[value="' + el.data('status') + '"]').attr(
                        'selected', true);
                });

                $('.download_invoice').on('click', function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    $.get('{{ route('frontend.product.invoice.generate') }}', {
                        id: id
                    }).then(function(data) {
                        let mywindow = window.open('', 'new div', 'height=874,width=840');
                        mywindow.document.write(data);
                        mywindow.document.close();
                        mywindow.focus();
                    })
                });

                $(document).on('click', '.approve_payment', function(e) {
                    e.preventDefault();
                    let order_id = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will not be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('{{ route('admin.product.order.payment.approve') }}', {
                                id: order_id,
                                _token: '{{ csrf_token() }}'
                            }).then(function(data) {
                                if (data.type == 'success') {
                                    Swal.fire(data.msg);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            });
                        }
                    });
                });
            });
        })(jQuery)
    </script>
@endsection
