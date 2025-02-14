@extends('backend.admin-master')
@section('site-title')
    {{ __('Shipping Method List') }}
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/toastr.css')}}">
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Shipping Methods List') }}</h4>
                        @can("shipping-method-create")
                            <a href="{{ route('admin.shipping-method.create') }}" class="cmn_btn btn_bg_profile">
                                {{ __("Create Shipping Method") }}
                            </a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body dashboard-recent-order mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <!-- <th>{{ __('Zone') }}</th> -->
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Cost') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_shipping_methods as $method)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ optional($method)->title }}</td>
                                            <!-- <td>{{ optional($method->zone)->name }}</td> -->
                                            <td>
                                                <x-status-span :status="optional($method)->status->name" />
                                            </td>
                                            <td>{{ amount_with_currency_symbol(optional($method)->cost) }}</td>

                                            <td>
                                                @can("shipping-method-delete")
                                                    @if (!$method->is_default)
                                                        <a href="{{ route('admin.shipping-method.destroy', $method->id) }}"
                                                            class="btn btn-danger btn-xs mb-2 me-1">
                                                            <i class="las la-trash"></i> </a>
                                                    @endif
                                                @endcan

                                                @can("shipping-method-edit")
                                                    <a href="{{ route('admin.shipping-method.edit', $method->id) }}"
                                                       class="btn btn-primary btn-xs mb-2 me-1">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @endcan

                                                @can("shipping-method-make-default")
                                                    @if (!$method->is_default)
                                                        <form action="{{ route('admin.shipping-method.make-default') }}"
                                                            method="post" style="display: inline">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $method->id }}">
                                                            <button
                                                                class="btn btn-info btn-xs mb-2 me-1">{{ __('Make Default') }}</button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-success btn-xs px-4 mb-2 me-1"
                                                            disabled>{{ __('Default') }}</button>
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
            <div class="col-lg-12 mt-4">
                
                <div class="dashboard__card">
                   
                    <div class="dashboard__card__body dashboard-recent-order mt-4">
                        <!-- International Shipping Methods -->
                        <div class="dashboard__card mt-4">
                            <div class="dashboard__card__header">
                                <h4 class="dashboard__card__title">{{ __('International Shipping Methods') }}</h4>
                            </div>
                            <div class="dashboard__card__body mt-4">
                                <!-- DHL Express -->
                                <div class="shipping-method-item mb-4">
                                    <form id="dhl-form">
                                        <div class="form-check mb-3">
                                            <input name="courier_name" type="checkbox" class="form-check-input shipping-method-toggle" 
                                                   id="dhl-active" value="dhl-express" data-method="dhl-express">
                                            <label class="form-check-label" for="dhl-active">
                                                <strong>{{ __('DHL Express Courier') }}</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="shipping-method-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('API Key') }}</label>
                                                        <input type="text" class="form-control" name="api_key" id="dhl-express-api-key">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('API Secret') }}</label>
                                                        <input type="password" class="form-control" name="api_secret" id="dhl-express-api-secret">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-group">
                                                        <label>{{ __('API URL') }}</label>
                                                        <input type="url" class="form-control" name="api_url" id="dhl-express-api-url">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-group">
                                                        <label>{{ __('Test API URL') }}</label>
                                                        <input type="url" class="form-control" name="api_url_test" id="dhl-express-api-url-test">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="is_test_mode" id="dhl-express-test_mode">
                                                        <label class="form-check-label" for="dhl-express-test_mode">{{ __('Test Mode') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- FedEx -->
                                <div class="shipping-method-item mb-4">
                                    <form id="fedex-form">
                                        <div class="form-check mb-3">
                                            <input type="checkbox" name="courier_name" class="form-check-input shipping-method-toggle" 
                                                   id="fedex-active" value="fedex-international" data-method="fedex-international">
                                            <label class="form-check-label" for="fedex-active">
                                                <strong>{{ __('FedEx International') }}</strong>
                                            </label>
                                        </div>
                                        
                                        <div class="shipping-method-details">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('API Key') }}</label>
                                                        <input type="text" class="form-control" name="api_key" id="fedex-international-api-key">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('API Secret') }}</label>
                                                        <input type="password" class="form-control" name="api_secret" id="fedex-international-api-secret">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-group">
                                                        <label>{{ __('API URL') }}</label>
                                                        <input type="url" class="form-control" name="api_url" id="fedex-international-api-url">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-group">
                                                        <label>{{ __('Test API URL') }}</label>
                                                        <input type="url" class="form-control" name="api_url_test" id="fedex-international-api-url-test">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="is_test_mode" id="fedex-international-test_mode">
                                                        <label class="form-check-label" for="fedex-international-test_mode">{{ __('Test Mode') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Save Changes') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <style>
                        .shipping-method-item {
                            background: #f8f9fa;
                            padding: 20px;
                            border-radius: 5px;
                        }

                        .shipping-method-details {
                            padding: 15px;
                            background: #ffffff;
                            border-radius: 5px;
                            margin-top: 10px;
                        }

                        .form-check-input {
                            cursor: pointer;
                        }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/backend/js/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/international-shipping.js')}}"></script>
@endsection
