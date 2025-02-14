<?php

namespace Modules\Order\Abstract;

abstract class OrderAbstract
{
    abstract protected static function isVendorMailable();
    abstract protected static function isMailSendWithQueue();
    abstract protected static function prepareOrderForVendor($vendor_id,$order_id, $total_amount, $shipping_cost, $tax_amount, $order_address_id);
    abstract protected static function prepareOrderForAdmin($sub_order_id,$order_id,$product_id,$variant_id,$quantity,$price,$sale_price);
    abstract protected static function orderProcess($request);
    abstract protected static function cartInstanceName();
    abstract protected static function groupByColumn();
    abstract public static function testOrder($request);
    abstract public static function sendOrder($request);
}