<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClockInReminderMail extends Mailable
{
    use SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Clock In Reminder')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.clock_in_reminder');
    }
}
