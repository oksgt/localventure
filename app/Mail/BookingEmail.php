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

    public function __construct($destination, $selectedImage, $result)
    {
        $this->destination = $destination;
        $this->selectedImage = $selectedImage;
        $this->result = $result;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'landing-page.finish-payment',
            with: [
                'destination' => $this->destination,
                'selectedImage' => $this->selectedImage,
                'result' => $this->result,
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
