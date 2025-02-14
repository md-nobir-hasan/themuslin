@extends('backend.admin-master')

@section('site-title')
    {{ __('Wallet settings') }}
@endsection

@section('style')
    <style>
        .payment_attachment {
            width: 100px;
        }
    </style>

    <x-media.css />
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-error-msg />
                <x-flash-msg />
                <div class="dashboard__card card__two">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All withdraw request page') }}</h4>
                    </div>
                    <div class="dashboard__card__body">
                        <div class="table-wrap">
                            <table class="table-responsive table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Vendor Info') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Gateway Name') }}</th>
                                        <th style="width: 30%">{{ __('Gateway Fields') }}</th>
                                        <th>{{ __('Note') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($withdrawRequests as $request)
                                        @php
                                            $fields = '';
                                        @endphp
                                        @foreach (json_decode($request->gateway_fields) as $key => $value)
                                            @php
                                                $fields .= ucwords(str_replace('_', ' ', $key)) . ' => ' . $value;
                                                if (!$loop->last) {
                                                    $fields .= ' , ';
                                                }
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td>
                                                {{ __('Owner Name:') }} {{ $request->deliveryMan->full_name ?? '' }}
                                                <br />
                                                <b>{{ __('Account Balance:') }}
                                                    {{ float_amount_with_currency_symbol($request->wallet->balance ?? null) ?? '' }}
                                                </b>
                                            </td>
                                            <td>{{ float_amount_with_currency_symbol($request->amount ?? null) ?? '' }}</td>
                                            <td>{{ $request->gateway->name }}</td>
                                            <td>{{ $fields }}</td>
                                            <td>{{ $request->note ?? '' }}</td>
                                            <td>
                                                @if (!empty($request->image))
                                                    <img src="{{ asset('assets/uploads/wallet-withdraw-request/' . $request->image) }}"
                                                        alt="{{ $request->gateway->name }}" />
                                                @endif
                                            </td>
                                            <td>
                                                <x-status-span :status="$request->request_status" />
                                            </td>
                                            <td>
                                                @if ($request->request_status == 'pending' || $request->request_status == 'processing')
                                                    <button data-fields="{{ $fields }}"
                                                        data-id="{{ $request->id }}"
                                                        data-request-status="{{ $request->request_status }}"
                                                        id="update-wallet-request" data-bs-target="#updateWalletStatus"
                                                        data-bs-toggle="modal" class="btn btn-info">
                                                        <i class="las la-pen-alt"></i>
                                                    </button>
                                                @endif
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="updateWalletStatus" tabindex="-1" aria-labelledby="updateWalletStatus" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.wallet.delivery-man-withdraw-request.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Update wallet modal') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <p class="request-fields-data"></p>
                        </div>

                        <input type="hidden" value="" name="id" />

                        <div class="form-group">
                            <label>{{ __('Select Status') }}</label>
                            <select name="request_status" class="form-control">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="processing">{{ __('Processing') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="failed">{{ __('Failed') }}</option>
                                <option value="refunded">{{ __('Refunded') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Select image if needed') }}</label>
                            <input name="request_image" type="file" />
                        </div>

                        <div class="form-group">
                            <label>{{ __('Write note for wallet request') }}</label>
                            <textarea name="note" rows="4" placeholder="Write note for wallet request"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Update withdraw request') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <x-media.js />

    <script>
        $(document).on("click", "#update-wallet-request", function() {
            $("#updateWalletStatus input[name=id]").val($(this).attr("data-id"));
            $("#updateWalletStatus select[name=request_status] option[value=" + $(this).attr(
                "data-request-status") + "]").attr("selected", true)
            $("#updateWalletStatus .request-fields-data").text($(this).attr("data-fields"))
        });
    </script>
@endsection
