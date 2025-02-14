<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlaceOrder extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $message;
    public $order_details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $subject, $message, $order_details)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->message = $message;
        $this->order_details = $order_details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        
        $mail = $this->from(get_static_option('site_global_email'), get_static_option('site_title'))
            ->subject(__('Order ID #') . ' ' . $this->data->id . ' From ' . get_static_option('site_title'))
            ->view('mail.order', ['mail_message' => $this->message]);

        if (!empty($this->attachment)) {
            foreach ($this->attachment as $field_name => $attached_file) {
                if (file_exists($attached_file)) {
                    $mail->attach($attached_file);
                }
            }
        }

        return $mail;
    }
}
