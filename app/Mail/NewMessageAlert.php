<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMessageAlert extends Mailable
{
    use Queueable, SerializesModels;

    // public $conversation;
    public $message;
    /**
     * Create a new message instance.
     */
    public function __construct(Message $message)
    {
        // $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've received a new message",
            from: $this->message->sender->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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

    public function headers()
    {
        return new Headers(
            messageId: $this->message->id,
            text: [
                'from_email' => $this->message->sender->email,
                'conversation_id' => $this->message->conversation_id,
            ],
        );
    }
}