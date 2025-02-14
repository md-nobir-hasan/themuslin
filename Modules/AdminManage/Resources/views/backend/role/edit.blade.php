@extends('backend.admin-master')
@section('site-title')
    {{ __('Edit Role') }}
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30 data-permission-list"
        data-permission-roles="{{ json_encode($rolePermissions) }}">
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
                                class="cmn_btn btn-xs mb-4 btn_bg_profile checked_all">{{ __('Change All') }}</button>
                            <div class="row checkbox-wrapper">
                                @foreach ($permissions as $key => $permission_value)
                                    <div class="editRole__item">
                                        <div class="editRole__item__header">
                                            <h4 class="editRole__item__title d-flex gap-4">
                                                <div>{{ ucwords($key) }}</div>
                                            </h4>
                                        </div>
                                        <br>
                                        <div class="editRole__item__body">
                                            <div class="row g-4">
                                                @foreach ($permission_value as $permission)
                                                    <div class="col-xxl-2 col-lg-4 col-md-4 col-sm-6">
                                                        <div class="editRole__item__switch form-group">
                                                            <div class="editRole__item__switch__inner vendor-coupon-switch">
                                                                <input
                                                                    class="custom-switch permisssion-switch-{{ $permission->id }}"
                                                                    type="checkbox"
                                                                    id="permisssion-switch-{{ $permission->id }}"
                                                                    name="permission[]" value="{{ $permission->id }}" />
                                                                <label
                                                                    class="switch-label permisssion-switch-{{ $permission->id }}"
                                                                    for="permisssion-switch-{{ $permission->id }}"></label>
                                                            </div>
                                                            <label for="permisssion-switch-{{ $permission->id }}">
                                                                <strong>{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</strong>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group mt-4">
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

        active_roles();

        function active_roles() {
            let bulk_permission_ids = JSON.parse($("div.data-permission-list").attr("data-permission-roles"));
            bulk_permission_ids = Object.values(bulk_permission_ids);

            for (let i = 0; i < bulk_permission_ids.length; i++) {
                $("label.permisssion-switch-" + bulk_permission_ids[i]).trigger("click");
            }
        }

        $(document).on("change", ".bulk-permission-input", function() {
            let bulk_permission_ids = JSON.parse($(this).attr("data-bulk-permission-ids"));

            for (let i = 0; i < bulk_permission_ids.length; i++) {
                $("label.permisssion-switch-" + bulk_permission_ids[i]).trigger("click");
            }
        });
    </script>
@endsection
