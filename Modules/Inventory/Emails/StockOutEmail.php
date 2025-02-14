<?php

namespace Modules\Inventory\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockOutEmail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build(): StockOutEmail
    {
        $stock_details = $this->data;

        return $this->from(get_static_option('site_global_email'))
            ->subject(__('Product Stock Warning').' From '.get_static_option('site_title'))
            ->view('inventory::emails.stock_out', compact('stock_details'));
    }
}
