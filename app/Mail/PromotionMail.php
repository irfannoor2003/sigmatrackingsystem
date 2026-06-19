<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PromotionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $content;
    public string $senderName;
    public string $senderRole;
    public ?string $attachmentPath;

    public function __construct(
        string $subjectText,
        string $content,
        string $senderName,
        string $senderRole,
        ?string $attachmentPath = null
    ) {
        $this->subjectText    = $subjectText;
        $this->content        = $content;
        $this->senderName     = $senderName;
        $this->senderRole     = $senderRole;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        $mail = $this->subject($this->subjectText)
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.promotion');

        if ($this->attachmentPath) {
            $mail->attach(
                storage_path('app/public/' . $this->attachmentPath)
            );
        }

        return $mail;
    }
}
