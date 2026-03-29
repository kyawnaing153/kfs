<?php

namespace App\Services;

class CustomInvoiceService
{
    /**
     * Generate custom invoice number
     */
    public function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = date('Ym');
        $month = date('m');
        $day = date('d');
        $random = strtoupper(substr(md5(microtime()), 0, 6));
        
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Calculate custom invoice totals
     */
    public function calculateTotals(array $data): array
    {
        // Calculate items subtotal
        $itemsSubtotal = 0;
        foreach ($data['items'] as &$item) {
            $item['line_total'] = $item['quantity'] * $item['unit_price'];
            $itemsSubtotal += $item['line_total'];
        }

        // Calculate tax amount
        $taxAmount = $itemsSubtotal * ($data['tax_percentage'] / 100);
        
        // Calculate grand total
        $grandTotal = ($data['secure_deposit'] ?? 0)
                    + ($data['transport_fee'] ?? 0)
                    - ($data['discount'] ?? 0)
                    + $taxAmount;

        // Add calculated values to data
        $data['items_subtotal'] = $itemsSubtotal;
        $data['tax_amount'] = $taxAmount;
        $data['grand_total'] = $grandTotal;
        
        // Format date
        $data['formatted_date'] = date('F d, Y', strtotime($data['date']));
        
        return $data;
    }

    /**
     * Get unit options
     */
    public function getUnitOptions(): array
    {
        return [
            'pcs' => 'Pieces',
            'kg' => 'Kilograms',
            'm' => 'Meters',
            'hr' => 'Hours',
            'day' => 'Days',
        ];
    }

    
}