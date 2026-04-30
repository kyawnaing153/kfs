<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\QuotationRequest;
use App\Models\Frontend\Quotation;
use App\Models\Frontend\QuotationItem;
use App\Models\ProductVariant;
use App\Models\Backend\GeneralSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function create()
    {
        return view('pages.frontend.quotation');
    }

    public function store(QuotationRequest $request) 
    {
        $validated = $request->validated();

        $quotationCode = $this->generateQuotationCode();
        $validated['quotation_code'] = $quotationCode;
        
        $type = $validated['type'];
        $transportRequired = filter_var($validated['transport_required'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $depositPerProduct = $this->depositPerProduct();
        $duration = $type === 'rent' ? ($validated['rent_duration'] ?? 1) : 1;
        
        $subTotal = 0;
        $deposit = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            $variant = ProductVariant::with('prices')->find($item['product_variant_id']);
            if (!$variant) continue;
            
            $unitPrice = 0;

            if ($type === 'purchase') {
                $salePrice = $variant->salePrice();
                $unitPrice = $salePrice ? $salePrice->price : 0;
            } else {
                $rentPrices = $variant->rentPrices()->sortByDesc('duration_days')->values();
                $selectedPrice = null;

                if ($rentPrices->isNotEmpty()) {
                    foreach ($rentPrices as $rentPrice) {
                        if ($duration >= $rentPrice->duration_days) {
                            $selectedPrice = $rentPrice;
                            break;
                        }
                    }
                    if (!$selectedPrice) {
                        $selectedPrice = $rentPrices->last();
                    }
                    $unitPrice = $selectedPrice->price;
                }
            }

            $lineTotal = $unitPrice * $item['qty']; //* ($type === 'rent' ? $duration : 1)
            $subTotal += $lineTotal;
            if ($type === 'rent') {
                $deposit += $depositPerProduct * $item['qty'];
            }

            $itemsData[] = [
                'product_variant_id' => $item['product_variant_id'],
                'qty' => $item['qty'],
                'unit' => $variant->unit ?? 'pcs',
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ];
        }

        $deposit = $type === 'rent' ? $deposit : 0;
        $transport = $transportRequired ? 0 : 0; // Admin can update transport cost later
        $discount = 0;
        
        if ($type === 'purchase') {
            $total = $subTotal + $transport - $discount;
        } else {
            // Rent total normally requires only deposit + transport upfront in this configuration
            $total = $deposit + $transport; 
        }

        $notes = $validated['notes'] ?? null;
        if (empty($validated['customer_id']) && !empty($validated['customer_name'])) {
            $guestName = "Guest Name: " . $validated['customer_name'];
            $notes = $notes ? $guestName . "\n" . $notes : $guestName;
        }

        DB::beginTransaction();
        try {
            $quotation = Quotation::create([
                'quotation_code' => $validated['quotation_code'],
                'customer_id' => $validated['customer_id'] ?? null,
                'type' => $type,
                'quotation_date' => now()->format('Y-m-d'),
                'rent_date' => $type === 'rent' ? $validated['rent_date'] : now()->format('Y-m-d'),
                'rent_duration' => $type === 'rent' ? $duration : null,
                'transport_required' => $transportRequired,
                'sub_total' => $subTotal,
                'deposit' => $deposit,
                'transport' => $transport,
                'discount' => $discount,
                'total' => $total,
                'status' => 'submitted',
                'transport_address' => $validated['transport_address'] ?? null,
                'notes' => $notes,
            ]);

            foreach ($itemsData as $itemData) {
                $itemData['quotation_id'] = $quotation->id;
                QuotationItem::create($itemData);
            }

            DB::commit();

            return redirect()->route('frontend.customer.dashboard')
                ->with('success', 'Quotation submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating quotation: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'items.productVariant.product']);

        return view('pages.frontend.quotations.quotation-detail', compact('quotation'));
    }

    private function generateQuotationCode(): string
    {
        $prefix = 'QTO-' . date('Ym');
        $lastQuotation = Quotation::where('quotation_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastQuotation) {
            $lastNumber = (int) substr($lastQuotation->quotation_code, -2);
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '01';
        }

        return $prefix . '-' . $newNumber;
    }

    private function depositPerProduct(): float
    {
        return (float) (GeneralSetting::where('key', 'deposit_amount')->value('value') ?? 5000);
    }
}
