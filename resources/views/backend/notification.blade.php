@extends("backend.admin-master")

@section("site-title", __("Notification list page"))

@section("style")

@endsection

@section("content")
    <div class="card">
        <div class="card-header">
            <h2>{{ __("Notifications") }}</h2>
        </div>
        <div class="card-body">
            @php
                $type = $type ?? 'admin';
            @endphp
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __("SL NO:") }}</th>
                        <th>{{ __("Message") }}</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($notifications as $notification)
                            @php
                                // get model namespace and make a class
                                $namespace = new $notification->model();
                                $productName = '';


                                // this line will be executed when a notification type is product
                                if ($notification->type == 'product') {
                                    $productName = $namespace->select('id', 'name')->find($notification->model_id)?->name;
                                }

                                // this method will generate
                                $href = \App\Http\Services\NotificationService::generateUrl($type, $notification);
                            @endphp

                            <tr>
                                <td>
                                    {{ ($notifications->perPage() * ($notifications->currentPage() - 1)) + $loop->iteration }}
                                </td>
                                <td class="{{ $notification->type == 'stock_out' ? 'bg bg-warning' : '' }}">
                                    <div class="notification-list-flex">
                                        <div class="notification-icon">
                                            <i class="las la-bell"></i>
                                        </div>

                                        <div class="notification-contents">
                                            <a class="list-title" href="{{ $href }}">
                                                {!! str_replace(
                                                    ['{product_name}', '{vendor_text}'],
                                                    ["<b>$productName</b>", ''],
                                                    formatNotificationText(strip_tags($notification->message)),
                                                ) !!} </a>
                                            <span class="list-sub"> {{ $notification->created_at->diffForHumans() }} </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")

@endsection