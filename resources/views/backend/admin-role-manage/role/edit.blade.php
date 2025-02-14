@extends('backend.admin-master')
@section('site-title')
    {{ __('Edit Role') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Edit Role') }}</h4>
                        <div class="btn-wrapper">
                            <a href="{{ route('admin.all.admin.role') }}"
                                class="cmn_btn btn_bg_profile">{{ __('All Roles') }}</a>
                        </div>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <x-msg.error />
                        <x-msg.success />
                        <form action="{{ route('admin.user.role.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $role->id }}">
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" value="{{ $role->name }}" name="name"
                                    placeholder="{{ __('Enter name') }}">
                            </div>
                            <button type="button"
                                class="btn btn-xs mb-4 btn-outline-dark checked_all">{{ __('Check All') }}</button>
                            <div class="row checkbox-wrapper">
                                @foreach ($permissions as $permission)
                                    <div class="col-lg-2 col-md-2">
                                        <div class="form-group">
                                            <label><strong>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</strong></label>
                                            <label class="switch role">
                                                <input type="checkbox" name="permission[]"
                                                    @if (in_array($permission->id, $rolePermissions)) checked @endif
                                                    value="{{ $permission->id }}">
                                                <span class="slider-yes-no"></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            "use strict";

            $(document).on('click', '.checked_all', function() {
                var allCheckbox = $('.checkbox-wrapper input[type="checkbox"]');
                $.each(allCheckbox, function(index, value) {
                    if ($(this).is(':checked')) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).prop('checked', true);
                    }
                });
            });

        });
    </script>
@endsection
