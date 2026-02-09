<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class QuotationPdfService
{
    /**
     * Generate quotation PDF
     */
    public function generateQuotationPdf(array $quotationData)
    {
        return Pdf::loadView('pages.admin.pdf.quotation', compact('quotationData'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'chroot' => public_path(),
            ]);
    }
}