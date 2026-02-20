<?php

namespace App\Http\Controllers\Backend\sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Sale\StoreSaleRequest;
use App\Http\Requests\Backend\Sale\UpdateSaleRequest;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Backend\Sale;

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'customer_id', 'start_date', 'end_date', 'search']);
        $sales = $this->saleService->getAllSales($filters);
        $saleStatics = $this->saleService->getSaleStatictics($sales);
        
        $totalSales = $saleStatics['total_sales'];
        $pendingSales = $saleStatics['pending_sales'];
        $completedSales = $saleStatics['completed_sales'];
        $totalRevenue = $saleStatics['total_revenue'];
        return view('pages.admin.sales.index', compact('sales', 'filters', 'totalSales', 'pendingSales', 'completedSales', 'totalRevenue'));
    }

    public function create(): View
    {
        $customers = $this->saleService->getAvailableCustomers();
        
        return view('pages.admin.sales.create', compact('customers'));
    }

    public function store(StoreSaleRequest $request): RedirectResponse
    {
        try {
            $result = $this->saleService->createSaleWithItems(
                $request->validated(),
                $request->input('items', [])
            );

            return redirect()->route('sales.index', $result['sale']->id)
                ->with('success', 'Sale created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create sale: ' . $e->getMessage());
        }
    }

    public function show(string $id): View
    {
        $sale = $this->saleService->getSaleById($id);
        
        if (!$sale) {
            abort(404);
        }

        return view('pages.admin.sales.show', compact('sale'));
    }

    public function edit(string $id): View
    {
        $sale = $this->saleService->getSaleById($id);
        
        if (!$sale) {
            abort(404);
        }

        $customers = $this->saleService->getAvailableCustomers();

        return view('pages.admin.sales.edit', compact('sale', 'customers'));
    }

    public function update(UpdateSaleRequest $request, string $id): RedirectResponse
    {
        try {
            $updated = $this->saleService->updateSale(
                $id,
                $request->validated(),
                $request->input('items', [])
            );

            if ($updated) {
                return redirect()->route('sales.show', $id)
                    ->with('success', 'Sale updated successfully.');
            }

            return redirect()->back()
                ->with('error', 'Sale not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update sale: ' . $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $deleted = $this->saleService->deleteSale($id);

            if ($deleted) {
                return redirect()->route('sales.index')
                    ->with('success', 'Sale deleted successfully.');
            }

            return redirect()->back()
                ->with('error', 'Sale not found.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete sale: ' . $e->getMessage());
        }
    }

    public function markAsCompleted(string $id): RedirectResponse
    {
        try {
            $completed = $this->saleService->markAsCompleted($id);

            if ($completed) {
                return redirect()->back()
                    ->with('success', 'Sale marked as completed.');
            }

            return redirect()->back()
                ->with('error', 'Cannot mark sale as completed. Please ensure all dues are paid.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update sale status: ' . $e->getMessage());
        }
    }

     /**
     * Get available product variants for rent
     */
    public function getAvailableVariants()
    {
        try {
            $variants = $this->saleService->getAvailableProductVariants();

            return response()->json($variants);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load product variants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function print(Sale $sale)
    {
        $sale->load([
            'customer',
            'items.productVariant.product',
        ]);

        $taxRate = 0; // 0%
        $taxAmount = $sale->sub_total * $taxRate;

        $sale->current_time = now()->format('Y-m-d H:i');
        $sale->tax_amount = $taxAmount;

        return view('pages.admin.sales.invoice', compact('sale'));
    }

    public function itemList(Request $request)
    {
        try {
            $filters = $request->only([
                'sale_code',
                'customer_id',
                'product_name',
                'status',
                'date_from',
                'date_to'
            ]);

            $sales = $this->saleService->getSaleItems($filters);

            // For web view
            $customers = $this->saleService->getAvailableCustomers();

            return view('pages.admin.sales.items.index', compact('sales', 'customers', 'filters'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to load sales',
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to load sale items: ' . $e->getMessage());
        }
    }

    public function sendInvoiceEmail(Sale $sale)
    {
        try {
            $this->saleService->sendSaleInvoiceEmail($sale);
            return redirect()->back()->with('success', 'Invoice email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send invoice email: ' . $e->getMessage());
        }
    }
}