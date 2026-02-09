<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutoClockOutMail extends Mailable
{
    use SerializesModels;

    public $attendance;

    public function __construct($attendance)
    {
        $this->attendance = $attendance;
    }

    public function build()
    {
        return $this->subject('⏰ Auto Clock-Out at 8:00 PM')
            ->view('emails.auto_clock_out');
    }
}
