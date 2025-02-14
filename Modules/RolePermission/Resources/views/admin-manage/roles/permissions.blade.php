@extends('backend.admin-master')

@section('site-title', __('Manage Permission'))

@section('style')
    <style>
        :root {
            --paragraph-color-one: #73777D;
            --bs-dropdown-item-padding-y: 0.25rem;
            --bs-dropdown-item-padding-x: 1rem;
            --bs-dropdown-header-color: #6c757d;
            --heading-font: "Poppins", sans-serif;
            --main-color-one: #696CFF;
            --white: #fff;
        }

        .simplePresentCart-one {
            padding: 30px 24px;
            border-radius: 16px;
        }

        .white-bg {
            background: white;
        }

        .mb-24 {
            margin-bottom: 24px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .section-tittle-one .title {
            color: #151D26;
            font-family: "Poppins", sans-serif;
            text-transform: capitalize;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 10px;
            display: inline-block;
            position: relative;
            z-index: 0;
        }

        .cmn-btn.style-3 {
            overflow: hidden;
            -webkit-transition: border-color 0.3s, background-color 0.3s;
            transition: border-color 0.3s, background-color 0.3s;
            -webkit-transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
            transition-timing-function: cubic-bezier(0.2, 1, 0.3, 1);
        }

        .cmn-btn.style-7::after,
        .cmn-btn.style-5 span,
        .cmn-btn.style-5::before,
        .cmn-btn.style-5,
        .cmn-btn.style-3::after,
        .cmn-btn.style-3,
        .cmn-btn {
            padding: 7px 16px;
        }

        .cmn-btn {
            display: inline-block;
            min-width: 100px;
            margin-bottom: 10px;
            border: inherit;
            background: inherit;
            vertical-align: middle;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .border-style-solid {
            border-style: solid !important;
        }

        .border-1 {
            border-width: 1px !important;
        }

        .border-main-one {
            border-color: #696CFF !important;
            color: #696CFF;
        }

        .radius-16 {
            border-radius: 16px !important;
        }

        .btn-danger {
            background-color: #d22d3d !important;
            border-color: #d22d3d !important;
        }

        .custom-dataTable,
        .custom-dataTable * {
            font-size: 12px;
        }

        .custom-dropdown button {
            background: none;
            padding: 0;
            border: 0;
            font-size: 40px;
            color: #A1A5A8;
            line-height: 1;
        }

        .custom-dataTable,
        .custom-dataTable * {
            font-size: 12px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .custom-dataTable,
        .custom-dataTable * {
            font-size: 12px;
        }

        .dropdown-toggle {
            white-space: nowrap;
        }

        .custom-dataTable .custom-dropdown button>i {
            font-size: 30px !important;
            font-weight: 700;
            color: #333;
            border: 1px solid #e2e2e2e2;
            border-radius: 10px;
            line-height: 20px;
            padding: 2px 6px;
        }

        .dropdown-menu {
            border: 0;
            -webkit-box-shadow: 0 3px 12px rgba(45, 23, 191, 0.09);
            box-shadow: 0 3px 12px rgba(45, 23, 191, 0.09);
        }

        .custom-dataTable,
        .custom-dataTable * {
            font-size: 12px;
        }

        .swal_delete_button {
            cursor: pointer;
        }

        .dropdown-item {
            font-weight: 500;
            color: var(--paragraph-color-one);
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: var(--bs-dropdown-item-padding-y) var(--bs-dropdown-item-padding-x);
            clear: both;
            font-weight: 400;
            color: var(--bs-dropdown-link-color);
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
        }

        .dropdown-toggle::after {
            display: none;
        }


        .custom-dataTable,
        .custom-dataTable * {
            font-size: 12px;
        }

        .custom-dataTable td {
            font-size: 12px;
        }

        .cmn-btn1 {
            font-family: var(--heading-font);
            -webkit-transition: 0.4s;
            transition: 0.4s;
            border: 1px solid transparent;
            background: var(--main-color-one);
            color: var(--white);
            padding: 13px 15px;
            font-size: 16px;
            font-weight: 500;
            display: inline-block;
            border-radius: 30px;
            text-align: center;
            text-transform: capitalize;
        }

        .font-weight-bold {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="bodyContent">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard__card">
                        <div class="dashboard__card__header">
                            <h3 class="dashboard__card__title">{{ $role->name }}: {{ __('Permissions') }}</h3>
                            <div class="button-wrapper">
                                <button class="d-none" data-bs-target="#create-new-permission" data-bs-toggle="modal"
                                    class="cmn_btn btn_outline_profile">{{ __('Add new permission') }}</button>
                                <a href="{{ route('admin.roles.index') }}" class="cmn_btn btn_bg_profile"
                                    data-text="All Roles"><span> {{ __('All Roles') }} </span></a>
                            </div>
                        </div>
                        <div class="dashboard__card__body mt-4">
                            <x-error-msg />
                            <x-flash-msg />
                            <div class="permission-wrap">
                                <form action="{{ route('admin.roles.permissions', $role->id) }}" method="post">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="checkbox-wrapper">
                                                @foreach ($permissions as $key => $permission_value)
                                                    @php
                                                        $groupName = str_replace('-', ' ', strtolower($key));
                                                    @endphp
                                                    <div class="permission-group-wrapper">
                                                        <div class="vendor__switch__header">
                                                            <h5 class="vendor__switch__title">{{ ucwords($groupName) }}</h5>
                                                            <div class="vendor-coupon-switch m-0">
                                                                <input class="custom-switch permisssion-group-switch"
                                                                    type="checkbox"
                                                                    id="permisssion-group-switch-{{ $groupName }}" />
                                                                <label class="switch-label permisssion-group-switch"
                                                                    for="permisssion-group-switch-{{ $groupName }}"></label>
                                                            </div>
                                                        </div>

                                                        <div class="row g-4 mt-0">
                                                            @foreach ($permission_value as $permission)
                                                                <div class="col-lg-3 col-md-4">
                                                                    <div class="switch_inlines">
                                                                        <div class="vendor-coupon-switch m-0">
                                                                            <input
                                                                                @if (in_array($permission->id, $rolePermissions)) checked @endif
                                                                                class="permission-switch custom-switch permisssion-switch-{{ $permission->id }}"
                                                                                type="checkbox"
                                                                                id="permisssion-switch-{{ $permission->id }}"
                                                                                name="permission[]"
                                                                                value="{{ $permission->id }}" />
                                                                            <label
                                                                                class="switch-label permisssion-switch-{{ $permission->id }}"
                                                                                for="permisssion-switch-{{ $permission->id }}"></label>
                                                                        </div>

                                                                        <label class="switch_inlines__title"
                                                                            for="permisssion-switch-{{ $permission->id }}">
                                                                            {{ ucfirst(str_replace(['-', '.'], [' ', ' '], $permission->name)) }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <button type="submit"
                                                class="cmn_btn btn_bg_profile">{{ __('Submit Now') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-new-permission" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('New Role') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.roles.create-permission') }}" method="post">
                    <div class="modal-body">
                        @csrf

                        <div class="form-grup">
                            <label for="#">{{ __('Menu Name') }}</label>
                            <input type="text" name="menu_name" class="form-control"
                                placeholder="{{ __('enter name') }}">
                        </div>

                        {{-- write a table for creating permissins --}}
                        <div class="table-wrapper">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <td>{{ __('Permission Name') }}</td>
                                        <td>{{ __('Guard') }}</td>
                                        <td>{{ __('Action') }}</td>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="name[]" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" name="guard[]">
                                                    <option value="">{{ __('Select a guard') }}</option>
                                                    @foreach (config('auth.guards') as $key => $guard)
                                                        <option {{ $key == 'admin' ? 'selected' : '' }}
                                                            value="{{ $key }}">{{ __($key) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group mt-0">
                                                <button type="button"
                                                    class="btn btn-info add">{{ __('Add') }}</button>
                                                <button type="button"
                                                    class="btn btn-danger remove">{{ __('Delete') }}</button>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        // handle group switch button click
        $(document).on("change", ".permisssion-group-switch", function() {
            // get current element
            let currentEl = $(this);
            // select permission group wrapper
            let permissionGroup = currentEl.closest(".permission-group-wrapper");
            // get all the buttons that are available in this group
            let availableSwitch = permissionGroup.find('.permission-switch');

            // now check currentEl is checked or not if checked then select all available switches if not then de-checked all switch
            if (currentEl.is(':checked')) {
                // run a loop here for checked all available options
                availableSwitch.each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                availableSwitch.each(function() {
                    $(this).prop("checked", false);
                });
            }
        });

        $(document).on("click", ".permission-switch", function() {
            // get this input group first
            let currentEl = $(this);
            let permissionGroup = currentEl.closest(".permission-group-wrapper");

            handleGroupSwitch(permissionGroup);
        })

        // create a function for preselecting all group switches
        function handleGroupSwitch(permissionGroup = null) {
            // select permission group wrapper
            let permissionGroupWrapper = (permissionGroup == null) ? $('.permission-group-wrapper') : permissionGroup;

            permissionGroupWrapper.each(function() {
                // select all available switches on this group
                let availableSwitch = $(this).find('.permission-switch').length;
                // select all checked switches
                let checkedSwitch = $(this).find('.permission-switch:checked').length;

                console.log(availableSwitch === checkedSwitch)

                if (availableSwitch === checkedSwitch) {
                    $(this).find('.permisssion-group-switch').prop("checked", true);
                } else {
                    $(this).find('.permisssion-group-switch').prop("checked", false);
                }
            });
        }

        handleGroupSwitch();


        // write javascript for repeater of permissions
        $(document).on("click", ".add", function() {
            $(this).closest('tr').after($(this).closest('tr').clone());
        });

        (function($) {
            "use strict";
            $(document).on("click", ".edit_role", function(e) {
                e.preventDefault();
                let modalContainer = $("#editRoles");

                modalContainer.find("form").attr("action", $(this).data("action"));
                modalContainer.find("input[name='id']").val($(this).data("id"));
                modalContainer.find("input[name='name']").val($(this).data("name"));
            })
        })(jQuery);
    </script>
@endsection
