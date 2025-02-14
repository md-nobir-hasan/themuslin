@extends('vendor.vendor-master')
@section('site-title')
    {{ __('Shipping Method List') }}
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
                        <h4 class="dashboard__card__title">{{ __('Shipping Methods Create') }}</h4>
                        <a class="cmn_btn btn_bg_profile" href="{{ route('vendor.shipping-method.index') }}">Shipping Method
                            List</a>
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order mt-4">
                        <form action="{{ route('vendor.shipping-method.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="zone_id">{{ __('Zone') }}</label>
                                        <select name="zone_id" id="zone_id" class="form-control">
                                            @foreach ($all_zones as $zone)
                                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('Title') }}
                                            <input name="title" class="form-control"
                                                placeholder="{{ __('Write shipping method title') }}" />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="status">{{ __('Status') }}</label>
                                        <select name="status" id="status" class="form-control">
                                            @foreach ($all_publish_status as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="cost">{{ __('Cost') }}</label>
                                        <input type="number" id="cost" name="cost" class="form-control"
                                            placeholder="{{ __('Cost') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="submit"
                                            class="cmn_btn btn_bg_profile">{{ __('Create Shipping Method') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
