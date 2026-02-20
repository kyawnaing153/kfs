<?php

namespace App\Mail;

use App\Models\Backend\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SaleInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sale;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Sale $sale, $pdfContent = null)
    {
        $this->sale = $sale;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sale Invoice #' . $this->sale->sale_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'pages.admin.emails.sale-email',
            with: [
                'sale' => $this->sale,
                'customer' => $this->sale->customer,
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
                'Invoice_' . $this->sale->sale_code . '.pdf'
            )->withMime('application/pdf');
        }
        
        return $attachments;
    }
}
