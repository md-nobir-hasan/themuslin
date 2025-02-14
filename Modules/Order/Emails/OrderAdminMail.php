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

class OrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private mixed $arg)
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        $country = Country::where("id",$this->arg["request"]['country_id'] ?? 0)->first();
        $state = State::where("id",$this->arg["request"]['state_id'] ?? 0)->first();
        $orders = SubOrder::with(["order","vendor","orderItem","orderItem.product","orderItem.variant","orderItem.variant.productColor","orderItem.variant.productSize"])->where("order_id", $this->arg["order_id"] ?? 0)->get();
        $paymentMeta = OrderPaymentMeta::where("order_id", $this->arg["order_id"])->first();
        $order = Order::find($this->arg["order_id"]);

        return $this->from($this->arg['request']['email'])
            ->subject(__("You have a new order"))
            ->view('order::emails.orders.admin_mail',$this->arg + ['paymentMeta' => $paymentMeta,'mainOrder' => $order,'country' => $country,'orders' => $orders,"state" => $state]);
    }
}
