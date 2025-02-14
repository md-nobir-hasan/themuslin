<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Entities\ProductSellInfo;

class TrackOrder extends Mailable
{
    use Queueable, SerializesModels;
    public ProductSellInfo $sell_info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductSellInfo $sell_info)
    {
        $this->sell_info = $sell_info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_message = __("Your order is now on the") . " <b>" . $this->sell_info->status . " </b>" . __('stage.') . " ";
        $mail_message .= __("And the payment is") . " <b>" . $this->sell_info->payment_status . "</b>.";
        return $this->view('mail.order-track', [
            'mail_message' => $mail_message
        ]);
    }
}
