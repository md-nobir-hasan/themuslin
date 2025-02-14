<?php

namespace App\Listeners;

use App\Events\ProductOrdered;
use App\Mail\PlaceOrder;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductSellInfo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ProductOrderMailUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductOrdered  $event
     * @return void
     */
    public function handle(ProductOrdered $event)
    {
        $orders = $event->data;
        if (!isset($orders['order_id']) && !isset($orders['transaction_id'])) {
            return;
        }

        $payment_details = ProductSellInfo::find($orders['order_id']);
        $order_details = $payment_details->order_details ? json_decode($payment_details->order_details, true) : [];
        $final_order_details = [];

        if ($order_details) {
            $products = Product::select('title','id')->whereIn('id', array_keys($order_details))->get()->pluck('title','id')->toArray();
            foreach ($order_details as $key => $order_items) {
                foreach ($order_items as $item) {
                    $final_order_details[] = [
                        'name' => $products[$key] ?? __('untitled'),
                        'quantity' => $item['quantity'],
                        'attributes' => $item['attributes']
                    ];
                }
            }
        }
        $subject = __('Your order has been placed');
        $message = __('Your order,') . ' #' . $orders['order_id'] . ' ' . __('has been placed');
        $message .= ' ' . __('at') . ' ' . date_format($payment_details->created_at, 'd F Y H:m:s');
        $message .= ' ' . __('via') . ' ' . str_replace('_', ' ', $payment_details->payment_gateway);

        try {
            Mail::to($payment_details->email)->send(new PlaceOrder(
                $payment_details,
                $subject,
                $message,
                $final_order_details
            ));

        } catch (\Exception $e) {
            //show error message
        }
    }
}
