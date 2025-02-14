@extends('backend.admin-master')
@section('site-title')
    {{ __('Trash Badges') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
    <x-media.css />

    <style>
        .badge_image {
            width: 50px;
            height: auto;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    @include('backend/partials/message')
                    @include('backend/partials/error')
                </div>
            </div>
            <div class="col-lg-12">
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Trash Badges') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('badge-trash-delete')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('badge')
                                <div class="btn-wrapper">
                                    <a class="cmn_btn btn_bg_profile"
                                        href="{{ route('admin.badge.all') }}">{{ __('Back') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <x-bulk-action.th />
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($badges as $badge)
                                        <tr>
                                            <x-bulk-action.td :id="$badge->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $badge->name }}</td>
                                            <td>
                                                {!! render_image_markup_by_attachment_id($badge->image, 'badge_image') !!}
                                            </td>
                                            <td>{{ $badge->status }}</td>
                                            <td>
                                                @can('badge-trash-restore')
                                                    <a class="btn btn-info btn-sm btn-xs mb-2"
                                                        href="{{ route('admin.badge.trash.restore', $badge->id) }}">
                                                        {{ __('Restore') }}
                                                    </a>
                                                @endcan
                                                @can('badge-trash-delete')
                                                    <x-delete-popover permissions="badge-delete"
                                                        url="{{ route('admin.badge.trash.delete', $badge->id) }}" />
                                                @endcan
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

    <x-media.markup />
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-media.js />
    @can('badge-trash-bulk-action')
        <x-bulk-action.js :route="route('admin.badge.trash.bulk.action.delete')" />
    @endcan
@endsection
