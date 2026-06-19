<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Visit;

class VisitBlocked extends Mailable
{
    use SerializesModels;

    public $salesmanName;
    public $visit;

    public function __construct(string $salesmanName, Visit $visit)
    {
        $this->salesmanName = $salesmanName;
        $this->visit = $visit;
    }

    public function build()
    {
        return $this->subject('Your Visit Has Been Automatically Blocked')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.visit_blocked')
                    ->with(['salesmanName' => $this->salesmanName, 'visit' => $this->visit]);
    }
}