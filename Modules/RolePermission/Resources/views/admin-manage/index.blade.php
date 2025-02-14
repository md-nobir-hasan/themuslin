@extends('backend.admin-master')
@section('content')
    <div class="bodyContent">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="dashboard__card simplePresentCart-one">
                        <!-- Title -->
                        <div class="dashboard__card__header">
                            <h3 class="dashboard__card__title">{{ __('Staff Manage') }}</h3>
                            <a data-bs-toggle="modal" data-bs-target="#createNewUser" href="#1"
                                class="cmn_btn btn_bg_profile" data-text="Create Role"
                                style="float:right"><span>{{ __('Create Staff') }} </span></a>
                        </div>
                        <div class="dashboard__card__body mt-4">
                            <x-error-msg />
                            <x-flash-msg />
                            <div class="table-responsive table-wrap custom-dataTable">
                                @if ($users->isNotEmpty())
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Role') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $value)
                                                <tr>
                                                    <td> {{ $value->id }} </td>
                                                    <td> {{ $value->name }} </td>
                                                    <td> {{ $value->email }} </td>
                                                    <td> {{ implode(',', $value->roles->pluck('name')->toArray()) }} </td>
                                                    <td>

                                                        <div class="dropdown custom-dropdown mb-10">
                                                            <button class="dropdown-toggle" type="button"
                                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="las la-ellipsis-h"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                                                                @if (!$value->roles->where('name', 'Super Admin')->first())
                                                                    <li>
                                                                        <x-backend.delete-popover :url="route(
                                                                            'UsersManage.destroy',
                                                                            $value->id,
                                                                        )" />
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    <a class="dropdown-item edit_user"
                                                                        data-id="{{ $value->id }}"
                                                                        data-name="{{ $value->name }}"
                                                                        data-email="{{ $value->email }}"
                                                                        data-role="{{ $value->roles->first()?->id }}"
                                                                        data-bs-toggle="modal" href="#0"
                                                                        data-action="{{ route('UsersManage.update', $value->id) }}"
                                                                        data-bs-target="#editUser">
                                                                        <i class="ti-notepad"></i> {{ __('Edit') }} </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item change_pass"
                                                                        data-bs-toggle="modal" href="#0"
                                                                        data-action="{{ route('UsersManage.changePassword', $value->id) }}"
                                                                        data-bs-target="#changePassword">
                                                                        <i class="ti-notepad"></i>
                                                                        {{ __('Change Password') }}
                                                                    </a>
                                                                </li>
                                                                {{--                                                <li> --}}
                                                                {{--                                                    <a class="dropdown-item verify_user" data-id="{{$value->id}}" data-name="{{$value->name}}" data-bs-toggle="modal" href="#0" data-action="{{route("UsersManage.changePassword",$value->id)}}" data-bs-target="#editUser" > --}}
                                                                {{--                                                        <i class="ti-notepad"></i> Verify Email </a> --}}
                                                                {{--                                                </li> --}}
                                                            </ul>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @else
                                    <span class="text-warning">{{ __('You have no role yet.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createNewUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('UsersManage.store') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-grup">
                            <label for="#">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="enter name">
                        </div>
                        <div class="form-grup">
                            <label for="#">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="enter email">
                        </div>
                        <div class="form-grup">
                            <label for="#">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="enter password">
                        </div>
                        <div class="form-grup">
                            <label for="#">Select Role</label>
                            <select class="form-select" name="role">
                                <option selected>------</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        @csrf
                        @method('PUT')
                        <div class="form-grup">
                            <label for="#">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="enter name">
                        </div>
                        <div class="form-grup">
                            <label for="#">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="enter email">
                        </div>
                        <div class="form-grup">
                            <label for="#">Select Role</label>
                            <select class="form-select" name="role">
                                <option selected>------</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePassword" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        @csrf
                        <div class="form-grup">
                            <label for="#">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="enter password">
                        </div>
                        <div class="form-grup">
                            <label for="#">Password Confirm</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="enter confirm password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function($) {
            "use strict";

            $(document).on("click", ".edit_user", function(e) {
                e.preventDefault();

                let modalContainer = $("#editUser");
                modalContainer.find("form").attr("action", $(this).data("action"));
                modalContainer.find("input[name='id']").val($(this).data("id"));
                modalContainer.find("input[name='name']").val($(this).data("name"));
                modalContainer.find("input[name='email']").val($(this).data("email"));
                modalContainer.find("select[name='role'] option[value='" + $(this).data("role") + "']").attr(
                    "selected", true);

            });

            $(document).on("click", ".change_pass", function(e) {
                e.preventDefault();

                let modalContainer = $("#changePassword");
                modalContainer.find("form").attr("action", $(this).data("action"));
                modalContainer.find("input[name='id']").val($(this).data("id"));

            })


        })(jQuery);
    </script>
@endsection
