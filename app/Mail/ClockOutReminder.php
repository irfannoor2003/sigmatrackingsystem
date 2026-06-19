<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClockOutReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Clock Out Reminder')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.clockout_reminder');
    }
}
