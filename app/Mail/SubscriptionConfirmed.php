<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User   $user,
        public string $plan,
        public string $teamName,
        public string $transactionRef,
        public string $amount,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[DevTracker] Your ' . strtoupper($this->plan) . ' subscription is active',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.subscription-confirmed',
        );
    }
}
