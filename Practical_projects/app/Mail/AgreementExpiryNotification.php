<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Agreement;

class AgreementExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;
    
    public Agreement $agreement;

    public function __construct(Agreement $agreement)
    {
        $this->agreement = $agreement;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alert: Agreement Expiring Soon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.agreement-expiry',
        );
    }
}