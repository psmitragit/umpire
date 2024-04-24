<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeagueMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ump;
    public $league;
    public $leaguemsg;
    /**
     * Create a new leaguemsg instance.
     */
    public function __construct($leaguemsg, $ump, $league)
    {
        $this->ump = $ump;
        $this->league = $league;
        $this->leaguemsg = $leaguemsg;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'League Message Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.league_message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
