<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotationData;
    public $customSubject;
    public $customMessage;
    public $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(array $quotationData, $pdfContent, $subject, $message = null)
    {
        $this->quotationData = $quotationData;
        $this->customSubject = $subject;
        $this->customMessage = $message;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject ?? 'Quotation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'pages.admin.emails.quotation',
            with: [
                'quotationData' => $this->quotationData,
                'customMessage' => $this->customMessage,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [
    //         $this->pdfContent, 'quotation-' . $this->quotationData['quotation_no'] . '.pdf', [
    //             'mime' => 'application/pdf',
    //         ]
    //     ];
    // }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                'quotation-' . $this->quotationData['quotation_no'] . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
