@extends('backend.admin-master')
@section('site-title')
    {{ __('Shipping Method List') }}
@endsection

@section('style')
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 dashboard-area">
        <x-msg.error />
        <x-msg.flash />
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Shipping Methods Create') }}</h4>
                        @can("shipping-method")
                            <a class="btn btn-info" href="{{ route('admin.shipping-method.index') }}">{{ __("Shipping Method List") }}</a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form dashboard-recent-order">
                        @can("shipping-method-store")
                            <form action="{{ route('admin.shipping-method.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-12">
                        @endcan

                                    <input value="12" name="zone_id" type="hidden" /> 
                                    <!-- Remove select option & statically setup zone -->

                                   
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

                        @can("shipping-method-store")

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Create Shipping Method') }}</button>
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
