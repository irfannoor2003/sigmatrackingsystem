<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClockOutReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Attendance Reminder - Clock Out')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.clock_out_reminder');
    }
}
