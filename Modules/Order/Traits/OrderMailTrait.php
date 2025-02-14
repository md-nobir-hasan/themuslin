<?php

namespace Modules\Order\Traits;

use Modules\Order\Emails\OrderAdminMail;
use Modules\Order\Emails\OrderUserMail;
use Modules\Order\Emails\OrderVendorMail;
use Modules\Order\Entities\OrderAddress;
use Modules\Order\Entities\SubOrder;
use Modules\User\Entities\User;

trait OrderMailTrait {
    public static function sendOrderMail($order_process,$request,$type = null): mixed
    {
        try {

            // first need to fetch order address of this order
            if ($type != 'pos'){
                $orderAddress = OrderAddress::where("order_id", $order_process['order_id'])->first()->toArray();
            }else{
                if(!empty($request->selected_customer)){
                    $orderAddress = User::find($request->selected_customer)->toArray();
                }
            }

            // check isVendorMailable is true then send mail for every vendor of this order
            if(self::isVendorMailable() && $type != 'pos'){
                // need to get all vendor of this order vendor if vendor exist against this order
                $vendorIds = SubOrder::with("vendor","vendor.vendor_shop_info")->where("order_id", $order_process['order_id'])
                    ->where("vendor_id","!=",null)->get();

                foreach($vendorIds as $vendor){
                    \Mail::to($vendor?->vendor?->vendor_shop_info?->email)->send(new OrderVendorMail(["order_id" => $order_process['order_id'],"vendor_id" => $vendor?->vendor_id , 'request' => $orderAddress,'type' => $type]));
                }
            }

            // check isAdminMailable is true then send mail for admin
            if (self::isAdminMailable() && $type != 'pos'){
                \Mail::to(get_static_option("site_global_email"))->send(new OrderAdminMail(["order_id" => $order_process['order_id'] , 'request' => $orderAddress,'type' => $type]));
            }

            // check isUserMailable is true then send mail for ordered user
            if(self::isUserMailable()){
                if($type == 'pos' && !empty($request->selected_customer) && $request->send_email == 'on'){
                    \Mail::to($orderAddress["email"])->send(new OrderUserMail(["order_id" => $order_process['order_id'] , 'request' => $orderAddress,'type' => $type]));
                }

                if($type != 'pos'){
                    \Mail::to($orderAddress["email"])->send(new OrderUserMail(["order_id" => $order_process['order_id'] , 'request' => $orderAddress,'type' => $type]));
                }
            }
        }catch (\Exception $e){
            return $e;
        }

        return true;
    }
}