<?php

namespace App\Http\Controllers\Backend\quotation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Quotation\QuotationRequest;
use App\Http\Requests\Backend\Quotation\QuotationEmailRequest;
use App\Services\QuotationService;
use App\Services\QuotationPdfService as PdfService;
use App\Mail\QuotationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    protected $quotationService;
    protected $pdfService;

    public function __construct(QuotationService $quotationService, PdfService $pdfService)
    {
        $this->quotationService = $quotationService;
        $this->pdfService = $pdfService;
    }

    /**
     * Show quotation form
     */
    public function index(Request $request)
    {
        //Clear session if requested
        if($request->has('clear')){
            $request->session()->forget('quotation_data');
             return redirect()->route('admin.quotation.index')->with('success', 'Form data cleared successfully.');
        }

        // Initialize with session data if exists
        $formData = $request->session()->get('quotation_data', [
            'company_email' => 'sale@kyawfamilyscaffolding.com',
            'client_email' => '',
            'client_name' => '',
            'client_address' => '',
            'client_phone' => '',
            'quotation_title' => 'QUOTATION',
            'quotation_no' => $this->quotationService->generateQuotationNumber(),
            'date' => date('Y-m-d'),
            'items' => [
                ['name' => '', 'quantity' => 1, 'unit' => 'pcs', 'unit_price' => 0]
            ],
            'secure_deposit' => 0,
            'transport_fee' => 0,
            'discount' => 0,
            'tax_percentage' => 0,
            'terms' => '1. This quotation is valid for 30 days.
                        2. Payment terms: 50% advance, 50% on delivery.
                        3. Prices are subject to change without notice.
                        4. Delivery within 7-10 working days.',
        ]);

        return view('pages.admin.quotation.create', compact('formData'));
    }

    /**
     * Preview quotation
     */
    public function preview(QuotationRequest $request)
    {
        $validated = $request->validated();

        // Store in session for persistence during the session
        $request->session()->put('quotation_data', $validated);

        // Calculate totals
        $quotationData = $this->quotationService->calculateTotals($validated);

        return view('pages.admin.quotation.preview', compact('quotationData'));
    }

    /**
     * Download PDF
     */
    public function download(QuotationRequest $request)
    {
        $validated = $request->validated();
        $quotationData = $this->quotationService->calculateTotals($validated);

        $pdf = $this->pdfService->generateQuotationPdf($quotationData);

        $filename = 'quotation-' . $quotationData['quotation_no'] . '.pdf';

        // Clear session after download if requested
        if ($request->has('clear_session')) {
            $request->session()->forget('quotation_data');
        }

        return $pdf->download($filename);
    }

    /**
     * Send quotation via email
     */
    public function sendEmail(QuotationEmailRequest $request)
    {
        $validated = $request->validated();

        $quotationData = $this->quotationService->calculateTotals($validated);

        // Generate PDF
        $pdf = $this->pdfService->generateQuotationPdf($quotationData);
        $pdfContent = $pdf->output();

        // Send email
        Mail::to($request->recipient_email)
            ->send(new QuotationMail(
                $quotationData,
                $pdfContent,
                $request->subject,
                $request->message
            ));

        $request->session()->forget('quotation_data');
        
        return back()->with('success', 'Quotation sent successfully to ' . $request->recipient_email);
    }

    /**
     * Generate new quotation number (AJAX endpoint)
     */
    public function generateNumber(Request $request)
    {
        return response()->json([
            'quotation_no' => $this->quotationService->generateQuotationNumber()
        ]);
    }
}
