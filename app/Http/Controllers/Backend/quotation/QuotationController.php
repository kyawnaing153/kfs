<?php

namespace App\Http\Controllers\Backend\quotation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Quotation\QuotationRequest;
use App\Http\Requests\Backend\Quotation\QuotationEmailRequest;
use App\Services\{QuotationService, RentService};
use App\Services\QuotationPdfService as PdfService;
use App\Mail\QuotationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Frontend\{Quotation, QuotationItem};
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    protected $quotationService;
    protected $pdfService;
    protected $rentService;

    public function __construct(QuotationService $quotationService, PdfService $pdfService, RentService $rentService)
    {
        $this->quotationService = $quotationService;
        $this->pdfService = $pdfService;
        $this->rentService = $rentService;
    }

    /**
     * Show quotation form
     */
    public function index(Request $request)
    {
        //Clear session if requested
        if ($request->has('clear')) {
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
        $quotationData['current_time'] = now()->format('Y-m-d H:i');

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
     * Get customers quotation
     */

    public function customerIndex(Request $request)
    {
        $search = $request->get('search', '');

        //load data set
        $customerQuotations = $this->quotationService->getCustomerQuotations($search);

        return view('pages.admin.customer-quotation.index', compact('customerQuotations', 'search'));
    }

    public function customerShow(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'items.productVariant.product',
        ]);

        return view('pages.admin.customer-quotation.show', compact('quotation'));
    }

    public function customerQuotationEdit(Quotation $quotation)
    {
        // Load relationships
        $quotation->load([
            'customer',
            'items.productVariant.product',
        ]);

        return view('pages.admin.customer-quotation.edit', compact('quotation'));
    }

    public function customerQuotationUpdate(Request $request, Quotation $quotation)
    {
        // Validate the request
        $validated = $request->validate([
            'deposit' => 'nullable|numeric|min:0',
            'transport' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'status' => 'required|in:submitted,approved,rejected,expired,converted',
            'notes' => 'nullable|string|max:5000',
            'transport_address' => 'nullable|string|max:500',
            'transport_required' => 'nullable|boolean',
            'rent_date' => 'nullable|date',
            'rent_duration' => 'nullable|integer|min:1',
            // Items update
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|exists:quotation_items,id',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.unit' => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items_to_remove' => 'nullable|array',
            'items_to_remove.*' => 'exists:quotation_items,id',
        ]);

        // Begin transaction
        \DB::beginTransaction();

        try {
            // Update main quotation fields
            $updateData = [
                'deposit' => $validated['deposit'] ?? 0,
                'discount' => $validated['discount'] ?? 0,
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
                'transport_required' => $validated['transport_required'] ?? false,
            ];

            // Update transport fields if transport is required
            if (($validated['transport_required'] ?? false) || $request->has('transport')) {
                $updateData['transport'] = $validated['transport'] ?? 0;
                $updateData['transport_address'] = $validated['transport_address'] ?? null;
            } else {
                $updateData['transport'] = 0;
                $updateData['transport_address'] = null;
            }

            // Update rent fields if provided
            if (isset($validated['rent_date'])) {
                $updateData['rent_date'] = $validated['rent_date'];
            }
            if (isset($validated['rent_duration'])) {
                $updateData['rent_duration'] = $validated['rent_duration'];
            }

            $quotation->update($updateData);

            // Remove items marked for deletion
            if (!empty($validated['items_to_remove'])) {
                QuotationItem::whereIn('id', $validated['items_to_remove'])->delete();
            }

            // Update existing items
            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    $itemTotal = $itemData['qty'] * $itemData['unit_price'];

                    if (isset($itemData['id']) && !empty($itemData['id'])) {
                        // Update existing item
                        $quotationItem = QuotationItem::find($itemData['id']);
                        if ($quotationItem && $quotationItem->quotation_id === $quotation->id) {
                            $quotationItem->update([
                                'qty' => $itemData['qty'],
                                'unit' => $itemData['unit'] ?? 'pcs',
                                'unit_price' => $itemData['unit_price'],
                                'total' => $itemTotal,
                            ]);
                        }
                    }
                }
            }

            // Recalculate totals
            $this->recalculateQuotationTotals($quotation);

            \DB::commit();

            return redirect()
                ->route('customer-quotation.index')
                ->with('success', 'Quotation updated successfully!');
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Quotation update error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Failed to update quotation. Error: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate quotation totals
     */
    private function recalculateQuotationTotals(Quotation $quotation)
    {
        $quotation->load('items');

        // Calculate sub total from items
        $subTotal = $quotation->items->sum('total');

        // Calculate total: sub_total + deposit + transport - discount
        $total = ($quotation->deposit ?? 0)
            + ($quotation->transport_required ? ($quotation->transport ?? 0) : 0)
            - ($quotation->discount ?? 0);

        $quotation->update([
            'sub_total' => $subTotal,
            'total' => max(0, $total),
        ]);
    }

    public function convertToRent(Quotation $quotation)
    {
        // Check if quotation is already converted
        if ($quotation->status === 'converted') {
            return redirect()
                ->route('customer-quotation.show', $quotation->id)
                ->with('error', 'This quotation has already been converted to a rent.');
        }

        // Check if quotation is approved
        if ($quotation->status !== 'approved') {
            return redirect()
                ->route('customer-quotation.show', $quotation->id)
                ->with('error', 'Only approved quotations can be converted to rent.');
        }

        // Check if quotation has items
        if ($quotation->items->isEmpty()) {
            return redirect()
                ->route('customer-quotation.show', $quotation->id)
                ->with('error', 'Cannot convert quotation with no items.');
        }

        try {
            \DB::beginTransaction();

            // Prepare rent data from quotation
            $rentData = $this->prepareRentDataFromQuotation($quotation);

            // Create rent using rent service
            $rent = $this->rentService->createRent($rentData);

            // Update quotation status to converted
            $quotation->update(['status' => 'converted']);

            \DB::commit();

            return redirect()
                ->route('rents.show', $rent->id)
                ->with('success', 'Quotation successfully converted to rent. Rent #' . $rent->rent_code . ' created.');
        } catch (\Exception $e) {
            \DB::rollback();

            return redirect()
                ->back()
                ->with('error', 'Failed to convert quotation to rent: ' . $e->getMessage());
        }
    }

    private function prepareRentDataFromQuotation(Quotation $quotation)
    {
        // Load necessary relationships
        $quotation->load(['customer', 'items.productVariant']);

        // Calculate totals (ensuring proper values)
        $subTotal = $quotation->items->sum('total');
        $deposit = $quotation->deposit ?? 0;
        $transport = $quotation->transport_required ? ($quotation->transport ?? 0) : 0;
        $discount = $quotation->discount ?? 0;
        $total = $subTotal + $deposit + $transport - $discount;
        
        // Determine rent date (use quotation rent_date or current date)
        $rentDate = $quotation->rent_date;
        
        // Prepare rent data array
        $rentData = [
            'customer_id' => $quotation->customer_id,
            'rent_date' => $rentDate,
            'sub_total' => $subTotal,
            'discount' => $discount,
            'deposit' => $deposit,
            'transport' => $transport,
            'total' => $total,
            'total_paid' => 0, // Initially no payment
            'total_due' => $total, // Full amount due initially
            'payment_type' => 'cash',
            'status' => 'pending', // Initial status
            'note' => $quotation->notes ?? null,
            // Items will be added separately
            'items' => $this->prepareRentItemsData($quotation->items)
        ];
           
        return $rentData;
    }

    private function prepareRentItemsData($quotationItems)
    {
        $items = [];
        
        foreach ($quotationItems as $item) {
            $items[] = [
                'product_variant_id' => $item->product_variant_id,
                'rent_qty' => $item->qty,
                'returned_qty' => 0, // Initially no returns
                'unit' => $item->unit ?? 'pcs',
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ];
        }
        
        return $items;
    }

    public function sendCustomerQuotationEmail()
    {
        
    }
}
