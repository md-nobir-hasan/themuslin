<?php

namespace Modules\Order\Http\Controllers;

use App\Mail\BasicMail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Entities\SubOrderCommission;
use Modules\Order\Services\OrderService;
use Modules\Wallet\Http\Services\WalletService;

class VendorOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // first of all we need to get all sub order for login user
        $all_orders = SubOrder::with("order:id,payment_status,created_at")
            ->where("vendor_id", auth("vendor")->id())
            ->withCount("orderItem")
            ->whereHas("order.address")
            ->orderByDesc("id")->paginate(20);

        return view('order::vendor.index', compact("all_orders"));
    }

    public function details($id){
        return OrderService::subOrderDetails($id, "vendor");
    }

    /**
     * @throws \Exception
     */
    public function updateOrderStatus(Request $request, SubOrder $subOrder){
        // validate data here
        $data = $request->validate([
            "order_status" => "required|string"
        ]);

        $updateSubOrder = $subOrder->update($data);

        if($data['order_status'] === 'order_cancelled'){
            $order = Order::find($subOrder->order_id);
            // send order amount to the wallet
            // send mail for users and admin
            if($order->user_id){
                // before update database checks user wallet amount if it is a getter then finalAmount or equal then user will be eligible for next action
                WalletService::updateUserWallet($order->user_id,($subOrder->total_amount + $subOrder->shipping_cost + $subOrder->tax_amount),true,'balance',$subOrder->id,checkBalance: false);

            }

            $vendor = auth('vendor')->user();
            $subOrderRoute = route("admin.orders.details", $subOrder->id);
            $subOrderId = $subOrder->id;

            WalletService::updateVendorWallet($subOrder->vendor_id, $subOrder->total_amount, plus: false);
            $orderMessage = $order->user_id ? __("this order amount is transferred to users account") : __("You need to transfer the money manually cause this user is not your registered user");

            $message = <<<HTML
                <a href="{$subOrderRoute}">#{$subOrderId}</a>
                Vendor cancelled this order
                {$orderMessage}
            HTML;

            try {
                \Mail::to(get_static_option('site_global_email'))->send(new BasicMail(["subject" => __("A Vendor cancelled a order"), "message" => $message]));
                if($order->user_id){
                    $userOrderRoute = route('user.product.order.details', $order->id);
                    $message = <<<HTML
                        <a href="{$userOrderRoute}">Sub order id #{$subOrderId}</a>
                        Our seller is cancelled this order your money has trasferred to your account wallet
                    HTML;

                    \Mail::to(get_static_option('site_global_email'))->send(new BasicMail(["subject" => __("A Vendor cancelled a order"),"message" => $message]));
                }
            } catch (\Exception $e) {

            }
        }

        return response()->json([
            "type" => $updateSubOrder ? "success" : "error",
            "msg" => $updateSubOrder ? __("Status changed successfully") : __("Failed to change status something went wrong.")
        ]);
    }
}
