<?php

namespace App\Http\Services;

use App\XGNotification;

class NotificationService extends \App\Http\Abstracts\XGNotification
{
    private array|object $notifications = [];

    public function send($model,string $type){
        $message = static::$instance->generateMessage($type);
        $vendor_id = auth("vendor")->id();
        $data = ["message" => $message,"type" => $this->type];

        // check vendor id is not empty or not
        if($vendor_id && $this->type != 'withdraw_request'){
            $data["vendor_id"] = $vendor_id;
        }

        if ($this->type == 'sub_order'){
            $data += $this->data;
        }

        if(!is_null($this->vendor_id)){
            $data["vendor_id"] = $this->vendor_id;
        }

        if(!is_null($this->user_id)){
            $data["user_id"] = $this->user_id;
        }

        if(!is_null($this->delivery_man_id)){
            $data["delivery_man_id"] = $this->delivery_man_id;
        }

        return static::$instance->store($model, $data);
    }

    protected function store($model, array $notificationData){
        $data = $model->notifications()->create($notificationData);

        // this line of code will destroy the current instance for new instance
        static::$instance = null;

        return $data;
    }

    public function fetch($for = null): object|array
    {
        // check auth is admin or vendor then fetch notification
        $activeGuard = activeGuard();

        if($activeGuard != 'web'){
            // get last 15 notification admin
            $this->notifications = XGNotification::when($activeGuard == 'vendor', function ($query){
                $query->where("vendor_id", auth()->id());
            })->when($activeGuard == 'admin', function ($query){
                $query->where("vendor_id", null);
                $query->where("user_id", null);
                $query->where("delivery_man_id", null);
            });

            if($for == null)
                $this->notifications = $this->notifications->latest()->limit(15)->get();
        }

        return $this->notifications;
    }

    public function getActiveColumn(): string
    {
        $type = activeGuard();
        $condition = 'is_read_admin';

        if($type == 'vendor'){
            $condition = 'is_read_vendor';
        }elseif($type == 'web'){
            $condition = 'is_read_user';
        }

        return $condition;
    }

    public function update(): bool
    {
        // check a type if a type is admin
        $condition = $this->getActiveColumn();
        $activeGuard = activeGuard();

        // check if type id product then update
        return XGNotification::when($activeGuard == 'vendor', function ($query){
            $query->where("vendor_id", auth()->id());
        })->when($activeGuard == 'admin', function ($query){
            $query->where("vendor_id", null);
            $query->where("user_id", null);
        })->where($condition, 0)->update([$condition => 1]);
    }

    public function unreadMessageCount($from = null): int
    {
        if($from != null && $from == 'delivery_man'){
            return XGNotification::where("delivery_man_id", auth()->user()->id)
                ->where('delivery_man_id', 0)->count();
        }

        // check which user is logged in
        $condition = $this->getActiveColumn();
        $activeGuard = activeGuard();

        return XGNotification::when($activeGuard == 'vendor', function ($query){
            $query->where("vendor_id", auth()->id());
        })->when($activeGuard == 'admin', function ($query){
            $query->where("vendor_id", null);
            $query->where("user_id", null);
        })->where($condition, 0)->count();
    }

    public function markAsRead(): ?array
    {
        $activeGuard = activeGuard();
        $notification = XGNotification::query();

        $response = [
            "msg" => __("Notification updated as read"),
            "type" => "success",
        ];

        if($activeGuard == 'web'){
            // here will be updated all user_id notifications as read that are matched with user_id = auth('web')->id()
            $notification->where("user_id", auth('web')->id())->update(["is_read_user" => 1]);
        }elseif ($activeGuard == 'vendor'){
            // here will be updated all vendor_id notifications as read that are matched with vendor_id = auth('vendor')->id()
            $notification->where("vendor_id", auth('vendor')->id())->update(["is_read_vendor" => 1]);
        }elseif ($activeGuard == 'admin'){
            // here will be updated all admin_id notifications as read
            $notification->where("vendor_id", null)->where("user_id", null)->update(["is_read_admin" => 1]);
        }else{
            $response = null;
        }

        return $response;
    }



    public static function generateUrl($type,$notification): string
    {
        $href = "";

        if($type == 'admin'){
            $href = match($notification->type){
                "product" => route("admin.products.edit", $notification->model_id),
                "sub_order" => route("admin.orders.details", $notification->model_id),
                "order" => route("admin.orders.order.details", $notification->model_id),
                "withdraw_request" => route("admin.wallet.withdraw-request") . '?request=' . $notification->model_id,
                "stock_out" => route("admin.products.inventory.edit",$notification->model_id),
                "refund_request" => route("admin.refund.view-request",$notification->model_id),
                default => "#1"
            };
        }elseif($type == 'vendor'){
            $href = match($notification->type){
                "sub_order" => route("vendor.orders.details", $notification->model_id),
                "stock_out" => route("vendor.products.inventory.edit",$notification->model_id),
                default => "#1"
            };
        }

        return $href;
    }
}