<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserLeft extends Mailable
{
    use SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Your account has been marked as left')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.user_left')
                    ->with(['user' => $this->user]);
    }
}