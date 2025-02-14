@extends('backend.admin-master')
@section('site-title')
    {{ __('All Campaign') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.error />
                <x-msg.flash />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Campaign') }}</h4>
                        <div class="dashboard__card__header__right">
                            @can('campaign-delete')
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('campaign-create')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.campaigns.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('Add New Campaign') }}</a>
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
                                    @foreach ($all_campaigns as $campaign)
                                        <tr>
                                            <x-bulk-action.td :id="$campaign->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $campaign->title }}</td>
                                            <x-table.td-image :image="$campaign->image" />
                                            <td><x-status-span :status="$campaign->status" /></td>
                                            <td>
                                                @if ($campaign->id != 1)
                                                    @can('campaign-delete')
                                                        <x-delete-popover :url="route('admin.campaigns.delete', $campaign->id)" />
                                                    @endcan
                                                @endif
                                                @can('campaign-edit')
                                                    <x-table.btn.edit :route="route('admin.campaigns.edit', $campaign->id)" />
                                                @endcan
                                                <a target="_blank" class="btn btn-info btn-xs mb-2 me-1"
                                                    href="{{ route('frontend.products.campaign', ['id' => $campaign->id, 'slug' => \Str::slug($campaign->title)]) }}">
                                                    <i class="ti-eye"></i>
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
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.campaigns.bulk.action')" />

    <script>
        $(document).ready(function() {
            $(document).on('click', '.campaign_edit_btn', function() {
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let modal = $('#campaign_edit_modal');

                modal.find('#campaign_id').val(id);
                modal.find('#edit_name').val(name);

                modal.show();
            });
        });
    </script>
@endsection
