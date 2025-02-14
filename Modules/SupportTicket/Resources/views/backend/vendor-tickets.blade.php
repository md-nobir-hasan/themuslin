@extends('backend.admin-master')
@section('site-title')
    {{ __('All Tickets') }}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.flash />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('All Tickets') }}</h4>
                        <div class="dashboard__card__header__right d-flex">
                            @can("support-tickets-bulk-action")
                                <x-bulk-action.dropdown />
                            @endcan
                            @can('support-tickets-new')
                                <div class="btn-wrapper">
                                    <a href="{{ route('admin.support.ticket.new') }}"
                                        class="cmn_btn btn_bg_profile">{{ __('New Ticket') }}</a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                    @can("support-tickets-bulk-action")
                                        <x-bulk-action.th />
                                    @endcan
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Department') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Priority') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($all_tickets as $data)
                                        <tr>
                                            @can("support-tickets-bulk-action")
                                                <x-bulk-action.td :id="$data->id" />
                                            @endcan
                                            <td>#{{ $data->id }}</td>
                                            <td>{{ $data->title }}</td>
                                            <td>{{ $data->department->name ?? __('anonymous') }}</td>
                                            <td>
                                                {{ __('Vendor:') }}
                                                {{ $data->vendor?->owner_name ?? __('anonymous') }},<br>
                                                @if($data->vendor?->business_name)
                                                    {{ __('Business Name') }} : {{ $data->vendor?->business_name }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="{{ $data->priority }} dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ $data->priority }}
                                                    </button>
                                                    @can('support-tickets-priority-change')
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item change_priority"
                                                                data-id="{{ $data->id }}" data-val="low"
                                                                href="#1">{{ __('Low') }}</a>
                                                            <a class="dropdown-item change_priority"
                                                                data-id="{{ $data->id }}" data-val="high"
                                                                href="#1">{{ __('High') }}</a>
                                                            <a class="dropdown-item change_priority"
                                                                data-id="{{ $data->id }}" data-val="medium"
                                                                href="#1">{{ __('Medium') }}</a>
                                                            <a class="dropdown-item change_priority"
                                                                data-id="{{ $data->id }}" data-val="urgent"
                                                                href="#1">{{ __('Urgent') }}</a>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="status-{{ $data->status }} dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ $data->status }}
                                                    </button>
                                                    @can('support-tickets-status-change')
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item status_change"
                                                                data-id="{{ $data->id }}" data-val="open"
                                                                href="#1">{{ __('Open') }}</a>
                                                            <a class="dropdown-item status_change"
                                                                data-id="{{ $data->id }}" data-val="close"
                                                                href="#1">{{ __('Close') }}</a>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                            <td>
                                                @can('support-tickets-delete')
                                                    <x-delete-popover :url="route('admin.support.ticket.delete', $data->id)" />
                                                @endcan
                                                @can('support-tickets-view')
                                                    <x-view-icon :url="route('admin.support.ticket.view', $data->id)" />
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $all_tickets->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @can("support-tickets-bulk-action")
        <x-bulk-action.js :route="route('admin.support.ticket.bulk.action')" />
    @endcan

    <script>
        (function() {
            "use strict";


            $(document).on('click', '.change_priority', function(e) {
                e.preventDefault();
                //get value
                var priority = $(this).data('val');
                var id = $(this).data('id');
                var currentPriority = $(this).parent().prev('button').text();
                currentPriority = currentPriority.trim();
                $(this).parent().prev('button').removeClass(currentPriority).addClass(priority).text(priority);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url': "{{ route('admin.support.ticket.priority.change') }}",
                    'data': {
                        _token: "{{ csrf_token() }}",
                        priority: priority,
                        id: id,
                    },
                    success: function(data) {
                        $(this).parent().find('button.' + currentPriority).removeClass(
                            currentPriority).addClass(priority).text(priority);
                    }
                })
            });
            $(document).on('click', '.status_change', function(e) {
                e.preventDefault();
                //get value
                var status = $(this).data('val');
                var id = $(this).data('id');
                var currentStatus = $(this).parent().prev('button').text();
                currentStatus = currentStatus.trim();
                $(this).parent().prev('button').removeClass('status-' + currentStatus).addClass('status-' +
                    status).text(status);
                //ajax call
                $.ajax({
                    'type': 'post',
                    'url': "{{ route('admin.support.ticket.status.change') }}",
                    'data': {
                        _token: "{{ csrf_token() }}",
                        status: status,
                        id: id,
                    },
                    success: function(data) {
                        $(this).parent().prev('button').removeClass(currentStatus).addClass(status)
                            .text(status);
                    }
                })
            });


        })(jQuery);
    </script>
@endsection
