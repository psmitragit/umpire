<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleGame extends Mailable
{
    use Queueable, SerializesModels;

    public $league;
    public $umpire;
    public $gamedata;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($league, $umpire, $gamedata, $type)
    {
        $this->league = $league;
        $this->umpire = $umpire;
        $this->gamedata = $gamedata;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Game Scheduled',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->type == 'league') {
            return new Content(
                view: 'mail.game_scheduled_league',
            );
        } else {
            return new Content(
                view: 'mail.game_scheduled',
            );
        }
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
