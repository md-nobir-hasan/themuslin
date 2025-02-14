@extends('backend.admin-master')
@section('site-title')
    {{ __('Country') }}
@endsection
@section('style')
    <x-media.css />
    <x-datatable.css />
    <x-bulk-action.css />
    <x-niceselect.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Add new mobile slider') }}</h4>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.mobile.campaign.update') }}" method="post">
                            @csrf
                            <div class="form-group" id="product-list">
                                <label for="products">Select Campaign</label>
                                <select id="products" name="campaign" class="form-control">
                                    <option value="">Select Campaign</option>
                                    @foreach ($campaigns as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == optional($selectedCampaign)->campaign_id ?? '' ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button class="cmn_btn btn_bg_profile">Update Campaign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <x-media.markup />
@endsection
@section('script')
    <x-media.js />
@endsection
