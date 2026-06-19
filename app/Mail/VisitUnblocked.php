<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Visit;

class VisitUnblocked extends Mailable
{
    use SerializesModels;

    public $salesmanName;
    public $visit;
    public $adminName;

    public function __construct(string $salesmanName, Visit $visit, string $adminName)
    {
        $this->salesmanName = $salesmanName;
        $this->visit = $visit;
        $this->adminName = $adminName;
    }

    public function build()
    {
        return $this->subject('Your Visit Has Been Unblocked')
                    ->replyTo(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.visit_unblocked')
                    ->with(['salesmanName' => $this->salesmanName, 'visit' => $this->visit, 'adminName' => $this->adminName]);
    }
}