<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $billingNumber;
    public $invoiceUrl;
    public $ticketDownloadUrl;

    public function __construct($userName, $billingNumber, $invoiceUrl, $ticketDownloadUrl)
    {
        $this->userName = $userName;
        $this->billingNumber = $billingNumber;
        $this->invoiceUrl = $invoiceUrl;
        $this->ticketDownloadUrl = $ticketDownloadUrl;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Pembayaran & Tiket Anda')
                    ->view('emails.payment.confirmed')
                    ->with([
                        'userName' => $this->userName,
                        'billingNumber' => $this->billingNumber,
                        'invoiceUrl' => $this->invoiceUrl,
                        'ticketDownloadUrl' => $this->ticketDownloadUrl,
                    ]);
    }
}
