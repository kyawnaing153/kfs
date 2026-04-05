<?php
// app/Http/Controllers/Backend/purchases/PurchaseController.php

namespace App\Http\Controllers\Backend\purchases;

use App\Http\Controllers\Controller;
use App\Services\PurchaseService;
use App\Models\Product;
use App\Models\Backend\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Backend\Purchase\PurchaseRequest;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'payment_status' => $request->get('payment_status', 'all'),
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'supplier_id' => $request->get('supplier_id'),
            'order_by' => $request->get('order_by', 'purchase_date'),
            'order_dir' => $request->get('order_dir', 'desc'),
        ];

        $status = $request->get('status', 'all');
        $perPage = $request->get('per_page', 10);

        $purchases = $this->purchaseService->getPurchases($filters, $status, $perPage);
        $statistics = $this->purchaseService->getPurchaseStatistics();

        return view('pages.admin.purchases.index', compact('purchases', 'statistics'));
    }

    public function create()
    {
        $suppliers = $this->purchaseService->getSuppliersForDropdown();
        $purchaseCode = $this->purchaseService->generatePurchaseCode();

        return view('pages.admin.purchases.create', compact('suppliers', 'purchaseCode'));
    }

    public function store(PurchaseRequest $request)
    {
        $data = $request->validated();

        try {
            $purchase = $this->purchaseService->createPurchase($data);

            return redirect()->route('purchases.index', $purchase->id)
                ->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating purchase: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $purchase = $this->purchaseService->getPurchase($id);

        return view('pages.admin.purchases.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = $this->purchaseService->getPurchase($id);

        // Only pending purchases can be edited
        if ($purchase->status == Purchase::STATUS_DELIVERED) {
            return redirect()->route('purchases.show', $id)
                ->with('error', 'Delivered purchases cannot be edited.');
        }

        $suppliers = $this->purchaseService->getSuppliersForDropdown();

        #dd($purchase, $suppliers);
        return view('pages.admin.purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(PurchaseRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $purchase = $this->purchaseService->updatePurchase($id, $data);

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating purchase: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->purchaseService->deletePurchase($id);

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting purchase: ' . $e->getMessage());
        }
    }

    public function markAsDelivered($id)
    {
        try {
            $this->purchaseService->markAsDelivered($id);

            return redirect()->route('purchases.show', $id)
                ->with('success', 'Purchase marked as delivered and stock updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating delivery status: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid payment status'], 422);
        }

        try {
            $purchase = $this->purchaseService->updatePaymentStatus($id, $request->payment_status);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'payment_status' => $purchase->payment_status]);
            }

            return redirect()->route('purchases.show', $id)
                ->with('success', 'Payment status updated successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getProductVariants($productId)
    {
        $product = Product::with('variants')->findOrFail($productId);
        return response()->json($product->variants);
    }

    public function getAvailableVariants()
    {
        try {
            $variants = $this->purchaseService->getAvailableProductVariants();
            return response()->json($variants);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load product variants',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
