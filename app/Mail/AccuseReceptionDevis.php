<?php

namespace App\Mail;

use App\Models\Devis;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AccuseReceptionDevis extends Mailable
{
    public function __construct(public Devis $devis)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre demande de devis — Videsgrenier.be');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.accuse-reception');
    }
}
