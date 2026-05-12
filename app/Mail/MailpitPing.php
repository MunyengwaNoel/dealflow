<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailpitPing extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $note = 'BizTrack Mailpit test.')
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'BizTrack Mailpit ping',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mailpit-ping',
        );
    }
}
