@extends('backend.admin-master')
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
                        @can('support-ticket-list')
                            <a href="{{ route('admin.support.ticket.all') }}"
                                class="cmn_btn btn_bg_profile">{{ __('All Support Tickets') }}</a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <form action="{{ route('admin.support.ticket.new') }}" method="POST" enctype="multipart/form-data">
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
                                <label>{{ __('User') }}</label>
                                <select name="user_id" class="form-control wide">
                                    @foreach ($all_users as $user)
                                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label>{{ __('Description') }}</label>
                                <textarea name="description"class="form-control" cols="30" rows="10" placeholder="{{ __('Description') }}"></textarea>
                            </div>
                            @can('support-ticket-create')
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Submit Ticket') }}</button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <x-niceselect.js />
    <script>
        $(document).ready(function() {
            let $selector = $('.nice-select');
            if ($selector.length > 0) {
                $selector.niceSelect();
            }
        });
    </script>
@endsection
