<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Order\Traits\OrderDetailsTrait;

class OrderTrackingFrontendController extends Controller
{
    use OrderDetailsTrait;

    public function trackOrderPage(Request $request)
    {
        // find order for tracking if request is empty
        if($request->has("order_id") && $request->has("email")){
            // find order
            $order = $this->orderDetailsMethod($request->order_id);
            // now check requested email and order email is same or not if not then throw 403 response code
            abort_if(($order?->address?->email ?? '') !== $request->email, 403);

            return view("order::frontend.track-order", compact("order"));
        }

        return view("order::frontend.track-order-form");
    }
}
