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
        $pdf = Pdf::loadView('pdf.rent-invoice', $data);
        
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
        $pdf = Pdf::loadView('pages.admin.pdf.rent-invoice', $data);
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
                'name' => config('app.name', 'Rental System'),
                'address' => '123 Business St, City, Country',
                'phone' => '+1234567890',
                'email' => config('mail.from.address', 'info@example.com'),
            ],
            'invoice_number' => $rent->rent_code,
            'date' => $rent->rent_date,
        ];
    }
}