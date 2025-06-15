<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $destination;
    public $selectedImage;
    public $result;
    public $encrypted_id;

    public function __construct($destination, $selectedImage, $result, $encrypted_id)
    {
        $this->destination = $destination;
        $this->selectedImage = $selectedImage;
        $this->result = $result;
        $this->encrypted_id = $encrypted_id;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Unpaid Invoice',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.finish-payment-email',
            with: [
                'destination' => $this->destination,
                'selectedImage' => $this->selectedImage,
                'result' => $this->result,
                'encrypted_id' => $this->encrypted_id
            ],
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
