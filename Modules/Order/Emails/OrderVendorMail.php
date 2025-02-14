<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderPaymentMeta;
use Modules\Order\Entities\SubOrder;

class OrderVendorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private $arg = [])
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $country = Country::where("id",$this->arg["request"]['country_id'] ?? 0)->first();
        $state = State::where("id",$this->arg["request"]['state_id'] ?? 0)->first();
        $orders = SubOrder::with(["order","vendor","orderItem","orderItem.product","orderItem.variant","orderItem.variant.productColor","orderItem.variant.productSize"])->where("vendor_id", $this->arg["vendor_id"])->where("order_id", $this->arg["order_id"])->get();
        $paymentMeta = OrderPaymentMeta::where("order_id", $this->arg["order_id"])->first();
        $order = Order::find($this->arg["order_id"]);

        return $this->from(get_static_option("site_global_email"))
            ->subject(__("You have a new order"))
            ->view('order::emails.orders.vendor_mail',$this->arg + ['paymentMeta' => $paymentMeta,'mainOrder' => $order,'country' => $country,'orders' => $orders,"state" => $state]);
    }
}
