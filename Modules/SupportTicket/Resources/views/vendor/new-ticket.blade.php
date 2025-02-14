@extends('vendor.vendor-master')
@section('site-title')
    {{ __('New Ticket') }}
@endsection
@section('style')
    <x-niceselect.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-msg.flash />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('New Ticket') }}</h4>
                        <a href="{{ route('vendor.support.ticket.all') }}"
                            class="cmn_btn btn_bg_profile">{{ __('All Support Tickets') }}</a>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('vendor.support.ticket.new') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Title') }}</label>
                                <input type="text" class="form-control" name="title" placeholder="{{ __('Title') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Subject') }}</label>
                                <input type="text" class="form-control" name="subject" placeholder="{{ __('Subject') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Priority') }}</label>
                                <select name="priority" class="form-control">
                                    <option value="low">{{ __('Low') }}</option>
                                    <option value="medium">{{ __('Medium') }}</option>
                                    <option value="high">{{ __('High') }}</option>
                                    <option value="urgent">{{ __('Urgent') }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Departments') }}</label>
                                <select name="departments" class="form-control">
                                    @foreach ($departments as $dep)
                                        <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea name="description" class="form-control" cols="30" rows="10" placeholder="{{ __('Description') }}"></textarea>
                            </div>
                            <div class="form-group btn-wrapper">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Submit Ticket') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <x-niceselect.js />
@endsection
