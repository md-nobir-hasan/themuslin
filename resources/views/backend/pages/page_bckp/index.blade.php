@extends('backend.admin-master')
@section('site-title')
    {{ __('All Pages') }}
@endsection
@section('style')
    <x-datatable.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.success />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Pages') }} </h4>
                        <div class="dashboard__card__header__right">
                            @can('page-delete')
                                <x-bulk-action />
                            @endcan
                            @can('page-create')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.page.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New Page') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    <th class="no-sort">
                                        <div class="mark-all-checkbox">
                                            <input type="checkbox" class="all-checkbox">
                                        </div>
                                    </th>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_pages as $data)
                                        <tr class="{{ $data['id'] }}">
                                            <td>
                                                <x-bulk-delete-checkbox :id="$data->id" />
                                            </td>
                                            <td>{{ $data->id }}</td>
                                            <td>{{ $data->title ?? __('Untitled') }}</td>
                                            <td>{{ $data->created_at->diffForHumans() }}</td>
                                            <td>
                                                <x-status-span :status="$data->status" />
                                            </td>
                                            <td>
                                                <x-view-icon :url="route('frontend.dynamic.page', [$data->slug, $data->id])" />
                                                @can('page-delete')
                                                    <x-delete-popover :url="route('admin.page.delete', $data->id)" />
                                                @endcan
                                                @can('page-edit')
                                                    <x-edit-icon :url="route('admin.page.edit', $data->id)" />
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
@endsection

@section('script')
    @include('backend.partials.datatable.script-enqueue')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                <
                x - bulk - action - js: url = "route('admin.page.bulk.action')" / >
            });
        })(jQuery);
    </script>
@endsection
