<?php

namespace Modules\Order\Http\Controllers;

use App\Mail\BasicMail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\DeliveryMan\Entities\DeliveryMan;
use Modules\DeliveryMan\Entities\DeliveryManZone;
use Modules\DeliveryMan\Services\GoogleMapServices;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\SubOrder;
use Modules\Order\Services\OrderService;
use Modules\Order\Services\OrderServices;
use Modules\Order\Traits\OrderDetailsTrait;
use Modules\Order\Traits\StoreOrderTrait;
use Modules\ShippingModule\Entities\Zone;
use Modules\Vendor\Entities\Vendor;
use Modules\Wallet\Http\Services\WalletService;
use Illuminate\Support\Facades\Validator;
use Modules\Order\Entities\SubOrderItem;
use Modules\Product\Entities\ProductInventory;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\ProductInventoryDetail;

class AdminOrderController extends Controller
{
    use OrderDetailsTrait;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // first of all we need to get all sub order for login user
        $all_orders = SubOrder::with("order:id,payment_status,created_at")
            ->withCount("orderItem")
            ->orderByDesc("id")->paginate(20);

        return view('order::admin.index', compact("all_orders"));
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function orders()
    {
        // first of all we need to get all sub order for login user
        // $all_orders = Order::with(["paymentMeta","orderTrack" => function ($query){
        //         $query->orderByDesc('id')->limit(1);
        //     }])
        //     ->orderByDesc("id")
        //     ->paginate(20);

        $all_orders = Order::with(["paymentMeta"])
            ->orderByDesc("id")
            ->paginate(20);

        return view('order::admin.order-list', compact("all_orders"));
    }

    //filter order 
    public function orderSearch(Request $request)
    {
        parse_str($request->input('data'), $parseData);

        $data = Validator::make($parseData, [
            'invoice_number' => 'nullable|string|max:100',
            'order_status' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // Get the validated data
        $validatedData = $data->validated();

        // Start building the query
        $order = Order::query();

        if (!empty($validatedData['invoice_number'])) {
            $order->where('invoice_number', $validatedData['invoice_number']);
        }

        if (!empty($validatedData['order_status'])) {
            $order->where('order_status', $validatedData['order_status']);
        }

        if (!empty($validatedData['payment_status'])) {
            $order->where('payment_status', $validatedData['payment_status']);
        }

        if (!empty($validatedData['start_date'])) {
            $order->whereDate('created_at', '>=', $validatedData['start_date']);
        }

        if (!empty($validatedData['end_date'])) {
            $order->whereDate('created_at', '<=', $validatedData['end_date']);
        }

        $all_orders = $order->get();

        return view('order::admin.search', compact("all_orders"))->render();
    }



    public function subOrderDetails($id)
    {
        return OrderService::subOrderDetails($id);
    }

    public function orderVendorList()
    {
        // first of all i need to get all vendor with some order information
        $vendors = Vendor::with("logo", "cover_photo")
            ->withCount(["order as total_order" => function ($order) {
                $order->orderByDesc("orders.id");
            }, "order as complete_order" => function ($order) {
                $order->where("orders.order_status", "complete");
            }, "order as pending_order" => function ($order) {
                $order->where("orders.order_status", "pending");
            }, "product"])
            ->whereHas("order")
            ->withSum("subOrder as total_earning", "sub_orders.total_amount")
            ->paginate(get_static_option("order_vendor_list") ?? 20);

        return view("order::admin.vendors", compact("vendors"));
    }

    public function details($id)
    {
        $order = $this->orderDetailsMethod($id);

        // let's check some condition if those condition are not matched then render blade file otherwise redirect to sub order details page
        // first condition will be count sub orders if sub order is less then 2 order and bigger then 0
        // second condition will be if sub order do not have any vendor id on this collection

        //        if($order->SubOrders->count() == 1 &&  !empty($order->SubOrders->first()->vendor_id)){
        //            return "Working";
        //        }


        return view("order::admin.details", compact("order"));
    }

    public function vendorOrders($username)
    {
        $vendor = Vendor::select("id", "username")->where("username", $username)->firstOrFail();

        // first of all we need to get all sub order for login user
        $all_orders = SubOrder::with("order:id,payment_status,created_at")
            ->withCount("orderItem")
            ->where("vendor_id", $vendor->id)
            ->orderByDesc("id")
            ->paginate(get_static_option("order_vendor_list"));

        return view('order::admin.index', compact("all_orders"));
    }

    public function edit($orderId)
    {
        $order = $this->orderDetailsMethod($orderId);

        // let's check some condition if those condition are not matched then render blade file otherwise redirect to sub order details page
        // first condition will be count sub orders if sub order is less then 2 order and bigger then 0
        // second condition will be if sub order do not have any vendor id on this collection

        if ($order->SubOrders->count() == 1 &&  !empty($order->SubOrders->first()->vendor_id)) {
        }

        $edit = true;

        return view("order::admin.details", compact("order", "edit"));
    }

    public function updateOrderTrack(Request $request)
    {
        $orderTrack = $request->validate([
            "order_id" => "required",
            "order_track" => "required",
            "order_track.*" => "required"
        ]);

        $order_info = Order::where(['id' => $orderTrack["order_id"]])->first();
        $orderAddress = OrderAddress::where("order_id", $orderTrack["order_id"])->first()->toArray();

        $status = [
            'picked_by_courier' => 'Picked by Courier',
            'on_the_way' => 'On the Way',
            'ready_for_pickup' => 'Ready for Pickup',
            'delivered' => 'Delivered',
            'ordered' => 'Placed',
        ];

        $length=count($orderTrack["order_track"]);

        foreach ($orderTrack["order_track"] as $key=> $track) {

            if ($track == 'delivered') {
                //todo:: add order amount from pending balance to main balance
                WalletService::completeOrderAmount($orderTrack["order_id"]);
                //todo:: add wallet history that mean's transaction history
            }

            $trackStatus = OrderServices::storeOrderTrack($orderTrack["order_id"], $track, auth()->user()->id, 'admin');

            if ($trackStatus && !empty($orderAddress['email']) &&  $length-1 == $key) {

                $messageBody = '
                                Order Number: <strong>' . $order_info['invoice_number'] . '</strong><br> 
                                Order Status: ' .  $status[$track] . '<br> 
                                Payment Status: ' . ucwords($order_info['payment_status']) .

                    '<br> <br> Thanks you for your order';

                $data['subject'] = 'Your Order is ' . $status[$track];
                $data['message'] = $messageBody;

                Mail::to($orderAddress['email'])->send(new BasicMail($data));
            }
        }

        return back()->with(["type" => "success", "msg" => __("Order track updated successfully.")]);
    }

    public function updateOrderStatus(Request $request)
    {

        $orderData = $request->validate([
            "order_status" => "required",
            "payment_status_hidden" => "required",
            "order_id" => "required",
            "admin_note" => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            if ($orderData['order_status'] == 'rejected') {

                $orderItems = SubOrderItem::where('order_id', $orderData['order_id'])->get();

                foreach ($orderItems as $item) {
                    $productInventory = ProductInventory::where('product_id', $item->product_id)->first();

                    if ($productInventory) {

                        $newStockCount = $productInventory->stock_count + $item->quantity;
                        $newSoldCount = $productInventory->sold_count - $item->quantity;

                        $productInventory->update([
                            'stock_count' =>  $newStockCount,
                            'sold_count' => $newSoldCount
                        ]);

                        // Update the variant stock and sold counts if a variant exists
                        if ($item->productVariant) {
                            $newStockCount =$item->productVariant->stock_count + $item->quantity;
                            $newSoldCount = $item->productVariant->sold_count - $item->quantity;
                            $item->productVariant->update([
                                'stock_count' => $newStockCount,
                                'sold_count' => $newSoldCount
                            ]);
                        }
                    }
                }
            }

            $subOrder = SubOrder::where('order_id', $orderData['order_id'])->first();
            $subOrder->update([
                'order_status' => $orderData['order_status']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        //todo:: now update into database
        Order::where("id", $orderData["order_id"])->update([
            "order_status" => $orderData["order_status"],
            "payment_status" => $orderData["payment_status_hidden"],
            "admin_note" => $orderData["admin_note"],
        ]);

        $order_info = Order::where(['id' => $orderData["order_id"]])->first();
        $orderAddress = OrderAddress::where("order_id", $orderData["order_id"])->first()->toArray();

        if (!empty($orderAddress['email'])) {

            if ($order_info['order_status'] == 'rejected') {

                $messageBody = 'Dear ' . $orderAddress['name'] . ', <br><br> 
                            Your ordered Product has been Rejected.
                            <br><br>
                            Order Number: <strong>' . $order_info['invoice_number'] . '</strong><br> 
                            Order Status: ' . ucwords($order_info['order_status']) . '<br> 
                            Payment Status: ' . ucwords($order_info['payment_status']) . '<br>
                            Rejected Reason: ' . $orderData["admin_note"];

                $data['subject'] = 'Order has been ' . ucwords($order_info['order_status']);
                $data['message'] = $messageBody;
            } else {
                $order_status = $order_info['order_status'] == 'complete' ? 'Delivered' : $order_info['order_status'];

                $messageBody = 'Dear ' . $orderAddress['name'] . ', <br><br> 
                                Your order information has been updated. 
                                <br><br>
                                Order Number: <strong>' . $order_info['invoice_number'] . '</strong><br> 
                                Order Status: ' . ucwords($order_status) . '<br> 
                                Payment Status: ' . ucwords($order_info['payment_status']) .

                    '<br> <br> Thanks you for your order';

                $data['subject'] = 'Order has been ' . ucwords($order_info['order_status']);
                $data['message'] = $messageBody;
            }


            Mail::to($orderAddress['email'])->send(new BasicMail($data));
        }

        return back()->with(["type" => "success", "msg" => __("Status updated successfully.")]);
    }
}
