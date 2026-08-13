<?php

namespace App\Mail;

use App\Models\Devis;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NouveauDevis extends Mailable
{
    public function __construct(public Devis $devis)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de devis — '.$this->devis->nom.' ('.($this->devis->commune ?: 'Bruxelles').')',
            replyTo: $this->devis->email ? [$this->devis->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.nouveau-devis');
    }
}
