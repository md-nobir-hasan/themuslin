@extends('backend.admin-master')
@section('site-title')
    {{ __('Add New Role') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('New Role') }}</h4>
                        <div class="btn-wrapper">
                            <a href="{{ route('admin.all.admin.role') }}"
                                class="cmn_btn btn_bg_profile">{{ __('All Roles') }}</a>
                        </div>
                    </div>
                    <div class="dashboard__card__body custom__form mt-4">
                        <x-msg.error />
                        <x-msg.success />
                        <form action="{{ route('admin.role.new') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Enter name') }}">
                            </div>
                            <button type="button"
                                class="btn btn-xs mb-4 btn-outline-dark checked_all">{{ __('Check All') }}</button>
                            <div class="row checkbox-wrapper">
                                @foreach ($permissions as $key => $permission_value)
                                    <h5 class="d-flex gap-4">
                                        <div>{{ ucwords($key) }}</div>
                                        <div class="vendor-coupon-switch">
                                            <input class="custom-switch bulk-permission-input" type="checkbox"
                                                id="bulk-permission-switch-{{ Str::slug($key) }}" name="bulk_permission"
                                                data-bulk-permission-ids="{{ json_encode($permission_value?->pluck('id')->toArray() ?? []) }}"
                                                value="{{ $key }}" />
                                            <label class="switch-label"
                                                for="bulk-permission-switch-{{ Str::slug($key) }}"></label>
                                        </div>
                                    </h5>
                                    <br>
                                    @foreach ($permission_value as $permission)
                                        <div class="col-lg-2 col-md-3">
                                            <div class="form-group d-flex gap-2">
                                                <label>
                                                    <strong>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</strong>
                                                </label>

                                                <div class="vendor-coupon-switch">
                                                    <input class="custom-switch permisssion-switch-{{ $permission->id }}"
                                                        type="checkbox" id="permisssion-switch-{{ $permission->id }}"
                                                        name="permission[]" value="{{ $permission->id }}" />
                                                    <label class="switch-label permisssion-switch-{{ $permission->id }}"
                                                        for="permisssion-switch-{{ $permission->id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <br>
                                    <br>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <button type="submit" class="cmn_btn btn_bg_profile">{{ __('Submit') }}</button>
                            </div>
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

        $(document).on("change", ".bulk-permission-input", function() {
            let bulk_permission_ids = JSON.parse($(this).attr("data-bulk-permission-ids"));

            for (let i = 0; i < bulk_permission_ids.length; i++) {
                $("label.permisssion-switch-" + bulk_permission_ids[i]).trigger("click");
            }
        });
    </script>
@endsection
