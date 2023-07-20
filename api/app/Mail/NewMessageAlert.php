<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

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
        $fromEmail = $this->message->sender_type === 'App\Models\User' ?
            $this->message->sender->username . '@parse.usepigment.com' :
            $this->message->sender->email;

        return new Envelope(
            subject: "You've received a new message",
            from: $fromEmail, //config('mail.from.email'), //$this->message->sender->email,
            metadata: [
                'submission' => $this->message->submission_id,
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.new-message',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $files = $this->message->getMedia('attachments');

        return $files->map(function ($file) {
            return Attachment::fromPath($file->getPath());
        })->toArray();
    }
}
