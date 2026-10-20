<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserWelcome extends Mailable
{
    use SerializesModels;

    public $user;
    public $plainPassword;

    public function __construct(User $user, ?string $plainPassword = null)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to ' . config('app.name') . ' — Your Account is Ready')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.user_welcome')
                    ->with(['user' => $this->user, 'plainPassword' => $this->plainPassword]);
    }
}
