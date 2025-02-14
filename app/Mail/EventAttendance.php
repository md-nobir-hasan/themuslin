<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventAttendance extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $payment;
    public $event;
    public $message_body;


    public function __construct($args)
    {
        $this->data = $args['event_attendance'];
        $this->message_body = $args['message'];
        $this->subject = $args['subject'];
        $this->event = $args['event_details'];
        $this->payment = $args['event_payment_logs'];
    }


    public function build()
    {
        return $this->from(get_static_option('site_global_email'), get_static_option('site_title'))
            ->subject($this->subject)
            ->view('mail.event-attendance');
    }
}
