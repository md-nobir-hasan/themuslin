@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
    @include('backend.partials.datatable.style-enqueue')
@endsection
@section('site-title')
    {{ __('All Admins') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Admin Created By Super Admin') }}</h4>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        @include('backend/partials/message')
                        @include('backend/partials/error')
                        <div class="table-wrap data-tables datatable-primary">
                            <table id="all_user_table" class="text-center">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($all_user as $data)
                                        <tr>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->name }} ({{ $data->username }})</td>
                                            <td>
                                                @php
                                                    $img = get_attachment_image_by_id($data->image, null, true);
                                                @endphp
                                                @if (!empty($img))
                                                    <div class="attachment-preview">
                                                        <div class="thumbnail">
                                                            <div class="centered">
                                                                <img class="avatar user-thumb" src="{{ $img['img_url'] }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php  $img_url = $img['img_url']; @endphp
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($data->getRoleNames()))
                                                    @foreach ($data->getRoleNames() as $v)
                                                        {{ $v }}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <x-delete-popover :url="route('admin.delete.user', $data->id)" />
                                                <a href="{{ route('admin.user.edit', $data->id) }}"
                                                    class="btn btn-lg btn-primary btn-sm mb-2 me-1 user_edit_btn">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                                <a href="#1" data-id="{{ $data->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#user_change_password_modal"
                                                    class="btn btn-sm btn-secondary mb-2 me-1 user_change_password_btn">
                                                    {{ __('Change Password') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user_change_password_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Change Admin Password') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                @include('backend/partials/error')
                <form action="{{ route('admin.user.password.change') }}" id="user_password_change_modal_form"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="ch_user_id" id="ch_user_id">
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control" name="password"
                                placeholder="{{ __('Enter Password') }}">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Change Password') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <!-- Start datatable js -->
    @include('backend.partials.datatable.script-enqueue')
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>
    @include('backend.partials.media-upload.media-js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('click', '.user_change_password_btn', function(e) {
                    e.preventDefault();
                    var el = $(this);
                    var form = $('#user_password_change_modal_form');
                    form.find('#ch_user_id').val(el.data('id'));
                });
                $('#all_user_table').DataTable({
                    "order": [
                        [0, "desc"]
                    ]
                });

            });

        })(jQuery);
    </script>
@endsection
