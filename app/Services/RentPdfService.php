<?php

namespace App\Services;

use App\Models\Backend\Rent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class RentPdfService
{
    /**
     * Generate invoice PDF for rent and save to storage
     */
    public function generateRentInvoice(Rent $rent): string
    {
        $data = $this->getInvoiceData($rent);
        $pdf = Pdf::loadView('pages.admin.pdf.rent-pdf', $data);

        // Save to storage
        $filename = 'invoices/rent_' . $rent->rent_code . '_' . time() . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Get PDF content for email attachment
     */
    public function getRentInvoicePdf(Rent $rent): string
    {
        $data = $this->getInvoiceData($rent);
        $pdf = Pdf::loadView('pages.admin.pdf.rent-pdf', $data);
        return $pdf->output();
    }

    /**
     * Get invoice data for PDF/Email
     */
    private function getInvoiceData(Rent $rent): array
    {
        return [
            'rent' => $rent->load(['customer', 'items.productVariant.product']),
            'company' => [
                'name' => 'Kyaw Family Scaffolding',
                'address' => 'E2,E3 (27) Ward, Pyitaungsu Rd, North Dagon, Yangon, Myanmar',
                'phone' => '09-428111750, 09975460778',
                'email' => config('mail.from.address', 'sale@kyawfamilyscaffolding.com'),
            ],
            'invoice_number' => $rent->rent_code,
            'date' => $rent->rent_date,
        ];
    }
}
