<?php

namespace App\Http\Controllers\Backend\customInvoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CustomInvoiceService;
use App\Http\Requests\Backend\CustomInvoice\CustomInvoiceRequest;

class CustomInvoiceController extends Controller
{
    protected $customInvoiceService;

    public function __construct(CustomInvoiceService $customInvoiceService)
    {
        $this->customInvoiceService = $customInvoiceService;
    }

    /**
     * Show custom invoice form
     */
    public function index(Request $request)
    {
        // Clear session if requested
        if ($request->has('clear')) {
            $request->session()->forget('custom_invoice_data');
            return redirect()->route('custom-invoice.index')->with('success', 'Form data cleared successfully.');
        }

        // Initialize with session data if exists
        $formData = $request->session()->get('custom_invoice_data', [
            'company_email' => 'sale@kyawfamilyscaffolding.com',
            'client_email' => '',
            'client_name' => '',
            'client_address' => '',
            'client_phone' => '',
            'invoice_title' => 'INVOICE',
            'invoice_no' => $this->customInvoiceService->generateInvoiceNumber(),
            'date' => date('Y-m-d'),
            'items' => [
                ['name' => '', 'quantity' => 1, 'unit' => 'pcs', 'unit_price' => 0]
            ],
            'secure_deposit' => 0,
            'transport_fee' => 0,
            'discount' => 0,
            'tax_percentage' => 0,
            'terms' => '1. This invoice is valid for 30 days.
                        2. Payment terms: 50% advance, 50% on delivery.
                        3. Prices are subject to change without notice.
                        4. Delivery within 7-10 working days.',
        ]);

        return view('pages.admin.custom-invoice.create', compact('formData'));
    }

    /**
     * Preview custom invoice
     */
    public function preview(CustomInvoiceRequest $request)
    {
        $validated = $request->validated();

        //Store validated data in session for later use (download/email)
        $request->session()->put('custom_invoice_data', $validated);

        // Calculate totals
        $invoiceData = $this->customInvoiceService->calculateTotals($validated);
        $invoiceData['current_time'] = now()->format('Y-m-d H:i');

        #dd($invoiceData);
        return view('pages.admin.custom-invoice.preview', compact('invoiceData'));
    }
}