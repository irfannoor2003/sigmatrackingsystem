<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AttendanceClockInVerificationMail extends Mailable
{
    public string $link;

    public function __construct(string $link)
    {
        $this->link = $link;
    }

    public function build()
    {
        return $this
            ->subject('Confirm Your Clock-In')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.attendance-verify');
    }
}
