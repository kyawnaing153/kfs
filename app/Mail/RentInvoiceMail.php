<?php

namespace App\Mail;

use App\Models\Backend\Rent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class RentInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rent;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Rent $rent, $pdfContent = null)
    {
        $this->rent = $rent;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rent Invoice #' . $this->rent->rent_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'pages.admin.emails.rent-email',
            with: [
                'rent' => $this->rent,
                'customer' => $this->rent->customer,
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
        $attachments = [];
        
        if ($this->pdfContent) {
            $attachments[] = Attachment::fromData(
                fn() => $this->pdfContent,
                'Invoice_' . $this->rent->rent_code . '.pdf'
            )->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
