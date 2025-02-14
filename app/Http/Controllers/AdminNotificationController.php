<?php

namespace App\Http\Controllers;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = xgNotifications('page')->paginate();

        return view("backend.notification",compact('notifications'));
    }
}
