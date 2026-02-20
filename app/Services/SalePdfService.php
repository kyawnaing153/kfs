<?php

namespace App\Services;

use App\Models\Backend\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SalePdfService
{
    /**
     * Generate invoice PDF for rent and save to storage
     */
    public function generateRentInvoice(Sale $sale): string
    {
        $data = $this->getInvoiceData($sale);
        $pdf = Pdf::loadView('pdf.sale-invoice', $data);
        
        // Save to storage
        $filename = 'Backend/invoices/sale_' . $sale->sale_code . '_' . time() . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }

    /**
     * Get PDF content for email attachment
     */
    public function getSaleInvoicePdf(Sale $sale): string
    {
        $data = $this->getInvoiceData($sale);
        $pdf = Pdf::loadView('pages.admin.pdf.sale-invoice', $data);
        return $pdf->output();
    }

    /**
     * Get invoice data for PDF/Email
     */
    private function getInvoiceData(Sale $sale): array
    {
        return [
            'sale' => $sale->load(['customer', 'items.productVariant.product']),
            'company' => [
                'name' => config('app.name', 'Rental System'),
                'address' => '123 Business St, City, Country',
                'phone' => '+1234567890',
                'email' => config('mail.from.address', 'info@example.com'),
            ],
            'invoice_number' => $sale->sale_code,
            'date' => $sale->sale_date,
        ];
    }
}