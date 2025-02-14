@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
    @include('backend.partials.datatable.style-enqueue')
@endsection
@section('site-title')
    {{ __('All Admin Roles') }}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-12">
                <x-msg.error />
                <x-msg.success />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Admin Roles') }}</h4>
                        <div class="btn-wrapper">
                            <a href="{{ route('admin.role.new') }}" class="cmn_btn btn_bg_profile">{{ __('New Role') }}</a>
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="data-tables datatable-primary table-wrap">
                            <table class="text-center">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @if ($role->name != 'Super Admin')
                                                    <x-edit-icon :url="route('admin.user.role.edit', $role->id)" />
                                                    <x-delete-popover :url="route('admin.user.role.delete', $role->id)" />
                                                @else
                                                    <span
                                                        class="alert alert-warning text-capitalize">{{ __('super admin has all access') }}</span>
                                                @endif
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
@endsection
@section('script')
    @include('backend.partials.datatable.script-enqueue', ['onlyjs' => true])
    <script>
        (function($) {
            "use strict";
            $('.table-wrap > table').DataTable({
                "order": [
                    [0, "desc"]
                ],
                'columnDefs': [{
                    'targets': 'no-sort',
                    'orderable': false
                }]
            });

        })(jQuery);
    </script>
@endsection
