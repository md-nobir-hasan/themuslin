@extends('backend.admin-master')
@section('site-title')
    {{ __('Shipping Method Update') }}
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
                        <h4 class="dashboard__card__title">{{ __('Shipping Methods Edit') }}</h4>
                        @can('shipping-method')
                            <a class="cmn_btn btn_bg_profile" href="{{ route('admin.shipping-method.index') }}">
                                {{ __('Shipping Method List') }}
                            </a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        @can('shipping-method-update')
                            <form action="{{ route('admin.shipping-method.update', $method->id) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                    @endcan

                                    <input value="12" name="zone_id" type="hidden" /> 

                                    <!-- Remove select option & statically setup zone -->
                                   

                                    <div class="form-group">
                                        <label for="title">{{ __('Title') }}
                                            <input value="{{ $method->title }}" name="title" class="form-control"
                                                placeholder="{{ __('Write shipping method title') }}" /> 
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach ($all_publish_status as $key => $status)
                                                <option {{ $method->status_id == $key ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="cost">{{ __('Cost') }}</label>
                                        <input value="{{ $method->cost }}" type="number" id="cost" name="cost"
                                            class="form-control" placeholder="{{ __('Cost') }}">
                                    </div>

                                    @can('shipping-method-update')
                                        <div class="form-group">
                                            <button type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Update Shipping Method') }}</button>
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
@endsection

@section('script')
@endsection
