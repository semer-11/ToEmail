<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailAttachment extends Mailable
{
    use Queueable, SerializesModels;

    protected $text;
    protected $attachment;
    /**
     * Create a new message instance.
     */
    public function __construct($text = null, $attachment = null)
    {
        $this->text = $text;
        $this->attachment = $attachment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Attachment',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.attachment',
            with: [
                'caption' => $this->text ?? "No caption"
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path("app/" . $this->attachment)),
        ];
    }
}
