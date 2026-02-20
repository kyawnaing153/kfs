<?php

namespace App\Services;

class QuotationService
{
    /**
     * Generate quotation number
     */
    public function generateQuotationNumber(): string
    {
        $prefix = 'QUOT';
        $year = date('Ym');
        $month = date('m');
        $day = date('d');
        $random = strtoupper(substr(md5(microtime()), 0, 6));
        
        return "{$prefix}-{$year}-{$random}";
    }

    /**
     * Calculate quotation totals
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
        $grandTotal = $itemsSubtotal
            + ($data['secure_deposit'] ?? 0)
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
            'set' => 'Set',
            'kg' => 'Kilogram',
            'lb' => 'Pound',
            'm' => 'Meter',
            'ft' => 'Feet',
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
        ];
    }
}