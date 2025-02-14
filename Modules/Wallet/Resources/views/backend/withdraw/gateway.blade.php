@extends('backend.admin-master')
@section('site-title', __('Vendor withdraw gateway page'))

@section('style')
    <style>
        .w-90 {
            width: 90%;
        }

        .w-20 {
            width: 20%;
        }
    </style>
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <x-msg.error />
            <x-flash-msg />
            <div class="col-lg-7">
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Vendor wallet payment gateway list') }}</h4>
                    </div>
                    <div class="dashboard__card__body">
                        <div class="table-wrap">
                            <table class="table-responsive table">
                                <thead>
                                    <tr>
                                        <th>{{ __('SL NO:') }}</th>
                                        <th>{{ __('Gateway Name:') }}</th>
                                        <th>{{ __('Gateway Filed:') }}</th>
                                        <th>{{ __('Gateway Status:') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gateways as $gateway)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $gateway->name }}</td>
                                            <td>{{ implode(' , ', unserialize($gateway->filed)) }}</td>
                                            <td>
                                                <x-status-span :status="$gateway->status->name" />
                                            </td>
                                            <td>
                                                @can('wallet-withdraw-gateway-update')
                                                    <button type="button" data-name="{{ $gateway->name }}"
                                                        data-id="{{ $gateway->id }}" data-status="{{ $gateway->status_id }}"
                                                        data-blog-filed="{{ json_encode(unserialize($gateway->filed)) }}"
                                                        class="btn btn-sm btn-info mb-2 me-1 update-gateway"
                                                        data-bs-toggle="modal" data-bs-target="#edit-gateway-modal">
                                                        <i class="ti-pencil"></i>
                                                    </button>
                                                @endcan

                                                @can('wallet-withdraw-gateway-delete')
                                                    <x-table.btn.swal.delete :route="route(
                                                        'admin.wallet.withdraw.gateway.delete',
                                                        $gateway->id,
                                                    )" />
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
            <div class="col-lg-5">
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Vendor wallet payment gateway create') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form">
                        <form class="" method="POST" action="{{ route('admin.wallet.withdraw.gateway') }}">
                            @csrf
                            <div class="form-group">
                                <label class="w-100">{{ __('Name:') }}</label>
                                <input class="form-control" name="gateway_name"
                                    placeholder="{{ __('Write gateway name...') }}">
                            </div>
                            <div class="dashboard__card card__two">
                                <div class="dashboard__card__header">
                                    <h4 class="dashboard__card__title">{{ __('Gateway required filed.') }}</h4>
                                </div>
                                <div class="dashboard__card__body">
                                    <div class="form-group row">
                                        <div class="w-90 d-flex align-items-center">
                                            <input class="form-control" name="filed[]" placeholder="Write filed name...">
                                        </div>
                                        <div
                                            class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                            <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                                <i class="las la-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                                <i class="las la-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Select Status') }}</label>
                                <select name="status_id" class="form-control">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="2">{{ __('inactive') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">{{ __('Create gateway') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('wallet-withdraw-gateway-update')
        <!-- Modal -->
        <div class="modal fade" id="edit-gateway-modal" tabindex="-1" aria-labelledby="edit-gateway-modalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content custom__form">
                    <form class="" method="POST" action="{{ route('admin.wallet.withdraw.gateway.update') }}">
                        @method('PUT')
                        @csrf
                        <input type="hidden" value="" name="id" />
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ __('Update wallet withdraw gateway') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label class="w-100">{{ __('Name:') }}</label>
                                <input class="form-control" name="gateway_name" placeholder="Write gateway name...">
                            </div>
                            <div class="dashboard__card">
                                <div class="dashboard__card__header">
                                    <h4 class="dashboard__card__title">{{ __('Gateway required filed.') }}</h4>
                                </div>
                                <div class="card-body gateway-filed-body">

                                </div>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Select Status') }}</label>
                                <select name="status_id" class="form-control">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="2">{{ __('inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('script')
    <x-table.btn.swal.js />
    <script>
        $(document).on("click", ".update-gateway", function() {
            let fileds = JSON.parse($(this).attr("data-blog-filed"));
            $("#edit-gateway-modal input[name='gateway_name']").val($(this).attr("data-name"))
            $("#edit-gateway-modal select[name='status_id'] option[value='" + $(this).attr("data-status") + "']")
                .attr("selected", true);
            $("#edit-gateway-modal input[name='id']").val($(this).attr("data-id"));

            if (fileds.length > 0) {
                let list_filed = "";

                fileds.forEach(function(value, index, array) {
                    list_filed += `
                        <div class="form-group row">
                            <div class="w-90 d-flex align-items-center">
                                <input class="form-control" value="${value}" name="filed[]" placeholder="Write filed name...">
                            </div>
                            <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                                <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                                    <i class="las la-plus"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                                    <i class="las la-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `;
                })

                $(".gateway-filed-body").html(list_filed);
            } else {
                $(".gateway-filed-body").html(`<div class="form-group row">
                    <div class="w-90 d-flex align-items-center">
                        <input class="form-control" name="filed[]" placeholder="Write filed name...">
                    </div>
                    <div class="col-md-1 d-flex flex-column align-items-center justify-content-center pb-2 gap-2">
                        <button type="button" class="btn btn-info btn-sm gateway-filed-add">
                            <i class="las la-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm gateway-filed-remove">
                            <i class="las la-trash-alt"></i>
                        </button>
                    </div>
                </div>`);
            }

        });

        $(document).on("click", ".gateway-filed-add", function() {
            let elem = $(this).parent().parent();
            elem.parent().append(elem.clone());
        });

        $(document).on("click", ".gateway-filed-remove", function() {
            if ($(".gateway-filed-remove").length == 1) {
                return null;
            }
            let elem = $(this).parent().parent().fadeOut(250, () => {
                $(this).parent().parent().remove();
            });
        });

        $(document).on("click", ".update-withdraw-gateway", function(e) {
            e.preventDefault();


            send_ajax_request("PUT")
        })
    </script>
@endsection
