@extends('backend.admin-master')
@section('site-title')
    {{ __('Ticket Details') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/media-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/summernote-bs4.css') }}">
    <style>
        span.low,
        span.status-open {
            display: inline-block;
            background-color: #6bb17b;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        span.high,
        span.status-close {
            display: inline-block;
            background-color: #c66060;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }

        span.medium {
            display: inline-block;
            background-color: #70b9ae;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            ,
            font-size: 10px;
            margin: 3px;
        }

        span.urgent {
            display: inline-block;
            background-color: #bfb55a;
            padding: 3px 10px;
            border-radius: 4px;
            color: #fff;
            text-transform: capitalize;
            border: none;
            font-weight: 600;
            font-size: 10px;
            margin: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row">
            <div class="col-lg-12">
                <x-msg.flash />
                <x-msg.error />
                <div class="dashboard__card">
                    <div class="dashboard__card__header">
                        <h4 class="dashboard__card__title">{{ __('Support Ticket Details') }}</h4>
                        @can('support-tickets')
                            <a class="cmn_btn btn_bg_profile" href="{{ route('admin.support.ticket.all') }}">
                                {{ __('All Tickets') }}
                            </a>
                        @endcan
                    </div>
                    <div class="dashboard__card__body mt-4">
                        <div class="gig-chat-message-heading">
                            <div class="gig-order-info">
                                <ul>
                                    <li><strong>{{ __('Ticket ID:') }}</strong> #{{ $ticket_details->id }}</li>
                                    <li><strong>{{ __('Title:') }}</strong> {{ $ticket_details->title }}</li>
                                    <li><strong>{{ __('Subject:') }}</strong> {{ $ticket_details->subject }}</li>
                                    <li><strong>{{ __('Description:') }}</strong> {{ $ticket_details->description }}</li>
                                    <li><strong>{{ __('Status:') }}</strong> <span
                                            class="status-{{ $ticket_details->status }}">{{ $ticket_details->status }}</span>
                                    </li>
                                    <li><strong>{{ __('Priority:') }}</strong> <span
                                            class="{{ $ticket_details->priority }}">{{ $ticket_details->priority }}</span>
                                    </li>
                                    <li><strong>{{ __('User:') }}</strong>
                                        {{ $ticket_details->user->name ?? __('anonymous') }}</li>
                                    <li><strong>{{ __('Department:') }}</strong>
                                        {{ $ticket_details->department->name ?? __('anonymous') }}</li>
                                    @if ($ticket_details->admin_id)
                                        <li><strong>{{ __('Admin:') }}</strong>
                                            {{ $ticket_details->admin->name ?? __('anonymous') }}</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="gig-message-start-wrap">
                                <h2 class="title">{{ __('All Conversation') }}</h2>
                                <div class="all-message-wrap @if ($q == 'all') msg-row-reverse @endif">
                                    @if ($q == 'all' && count($all_messages) > 1)
                                        <form action="" method="get">
                                            <input type="hidden" value="all" name="q">
                                            <button class="load_all_conversation"
                                                type="submit">{{ __('load all message') }}</button>
                                        </form>
                                    @endif
                                    @forelse($all_messages as $msg)
                                        <div class="single-message-item @if ($msg->type == 'customer') customer @endif">
                                            <div class="top-part">
                                                <div class="thumb">
                                                    <span class="title">
                                                        @if ($msg->type == 'customer')
                                                            {{ substr($ticket_details->user->name ?? 'U', 0, 1) }}
                                                        @elseif($msg->type == 'vendor')
                                                            {{ substr($ticket_details->vendor->name ?? 'V', 0, 1) }}
                                                        @else
                                                            {{ substr($ticket_details->admin->name ?? 'A', 0, 1) }}
                                                        @endif
                                                    </span>
                                                    @if ($msg->notify == 'on')
                                                        <i class="fas fa-envelope mt-2"
                                                            title="{{ __('Notified by email') }}"></i>
                                                    @endif
                                                </div>
                                                <div class="content">
                                                    <h6 class="title">
                                                        @if ($msg->type == 'customer')
                                                            {{ $ticket_details->user->name ?? 'U' }}
                                                        @elseif($msg->type == 'vendor')
                                                            {{ $ticket_details->vendor->name ?? 'V' }}
                                                        @else
                                                            {{ $ticket_details->admin->name ?? 'A' }}
                                                        @endif
                                                    </h6>
                                                    <span class="time">{{ date_format($msg->created_at, 'd M Y H:i:s') }}
                                                        | {{ $msg->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <div class="message-content">
                                                    {!! $msg->message !!}
                                                </div>
                                                @if (file_exists('assets/uploads/ticket/' . $msg->attachment))
                                                    <a href="{{ asset('assets/uploads/ticket/' . $msg->attachment) }}"
                                                        download class="anchor-btn">{{ $msg->attachment }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="alert alert-warning">{{ __('no message found') }}</p>
                                    @endforelse
                                </div>
                            </div>
                            @can('support-tickets-send-message')
                                <div class="reply-message-wrap ">
                                    <h5 class="title">{{ __('Replay To Message') }}</h5>
                                    <form action="{{ route('admin.support.ticket.send.message') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" value="{{ $ticket_details->id }}" name="ticket_id">
                                        <input type="hidden" value="admin" name="user_type">
                                        <div class="form-group">
                                            <label for="">{{ __('Message') }}</label>
                                            <textarea name="message" class="form-control d-none" cols="30" rows="5"></textarea>
                                            <div class="summernote"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="file">{{ __('File') }}</label>
                                            <input type="file" name="file" accept="zip">
                                            <small
                                                class="info-text d-block text-danger">{{ __('max file size 200mb, only zip file is allowed') }}</small>
                                        </div>
                                        <div class="form-group d-flex align-items-baseline gap-3">
                                            <input type="checkbox" name="send_notify_mail" id="send_notify_mail">
                                            <label for="send_notify_mail">{{ __('Notify Via Mail') }}</label>
                                        </div>
                                        <button class="cmn_btn btn_bg_profile" type="submit">{{ __('Send Message') }}</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{ asset('assets/backend/js/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/backend/js/dropzone.js') }}"></script>
    @include('backend.partials.media-upload.media-js')
    <script>
        $(document).ready(function() {

            $('.summernote').summernote({
                height: 200, //set editable area's height
                codemirror: { // codemirror options
                    theme: 'monokai'
                },
                callbacks: {
                    onChange: function(contents, $editable) {
                        $(this).prev('textarea').val(contents);
                    },
                    onPaste: function (e) {
                        let bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/plain');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                }
            });

        });
    </script>
@endsection
