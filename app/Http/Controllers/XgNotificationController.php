<?php

namespace App\Http\Controllers;

use App\Http\Services\NotificationService;

class XgNotificationController extends Controller
{
    public function __invoke()
    {
        $response = NotificationService::init()->markAsRead();

        if(is_null($response))
            abort(403);

        return response()->json($response);
    }
}
