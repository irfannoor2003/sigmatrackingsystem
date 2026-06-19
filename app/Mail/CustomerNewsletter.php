<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;

    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Monthly Reminder from Our Team')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.customer_newsletter');
    }
}
