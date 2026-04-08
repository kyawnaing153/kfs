<?php

namespace App\Http\Controllers\Backend\rents;


use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Rent\RentRequest;
use App\Services\{RentService, RentReturnService};
use App\Models\Backend\Rent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class RentController extends Controller
{
    protected $rentService;
    protected $rentReturnService;

    public function __construct(RentService $rentService, RentReturnService $rentReturnService)
    {
        $this->rentService = $rentService;
        $this->rentReturnService = $rentReturnService;
    }

    /**
     * Display a listing of rents
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'rents');
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $returnStatus = $request->get('return_status', 'all');
        $perPage = (int) $request->get('per_page', 20);

        // Always load both data sets
        $rents = $this->rentService->getRents(['search' => $search], $status, $perPage);
        $returns = $this->rentReturnService->getAllReturns(['search' => $search], $returnStatus);

        return view('pages.admin.rents.index', compact('rents', 'returns', 'status', 'returnStatus', 'activeTab', 'search'));
    }

    /**
     * Show the form for creating a new rent
     */
    public function create()
    {
        $customers = $this->rentService->getAvailableCustomers();

        return view('pages.admin.rents.create', compact('customers'));
    }

    /**
     * Store a newly created rent
     */
    public function store(RentRequest $request)
    {
        try {
            $rent = $this->rentService->createRent($request->validated());

            return redirect()->route('rents.index')
                ->with('success', 'Rent created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating rent: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified rent
     */
    public function show(Rent $rent)
    {
        $rent->load([
            'customer',
            'items.productVariant.product',
            'payments',
            'returns.items.rentItem'
        ]);

        return view('pages.admin.rents.show', compact('rent'));
    }

    /**
     * Show the form for editing the rent
     */
    public function edit(Rent $rent)
    {
        if ($rent->status !== 'pending') {
            return redirect()->route('rents.show', $rent->id)
                ->with('error', 'Only pending rents can be edited.');
        }

        $customers = $this->rentService->getAvailableCustomers();
        #$productVariants = $this->rentService->getAvailableProductVariants();

        return view('pages.admin.rents.edit', compact('rent', 'customers'));
    }

    /**
     * Update the specified rent
     */
    public function update(RentRequest $request, Rent $rent)
    {
        try {
            $this->rentService->updateRent($rent, $request->validated());

            return redirect()->route('rents.show', $rent->id)
                ->with('success', 'Rent updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating rent: ' . $e->getMessage());
        }
    }

    /**
     * Cancel the specified rent
     */
    public function destroy(Rent $rent)
    {
        try {
            $this->rentService->cancelRent($rent);

            return redirect()->route('rents.index')
                ->with('success', 'Rent cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error cancelling rent: ' . $e->getMessage());
        }
    }

    /**
     * Print rent invoice
     */
    public function print(Rent $rent)
    {
        $rent->load([
            'customer',
            'items.productVariant.product',
            'payments'
        ]);

        // Calculate daily rental subtotal from items
        $dailyRentalSubtotal = 0;
        foreach ($rent->items as $item) {
            $item->daily_total = $item->rent_qty * $item->unit_price;
            $dailyRentalSubtotal += $item->daily_total;
        }


        // Calculate tax
        $taxRate = 0; // 0%
        $taxAmount = $rent->sub_total * $taxRate;

        // Add calculated values to rent object
        $rent->current_time = now()->format('Y-m-d H:i');

        $rent->daily_subtotal = $dailyRentalSubtotal;
        $rent->tax_amount = $taxAmount;

        return view('pages.admin.rents.invoice', compact('rent'));
    }

    /**
     * Get available product variants for rent
     */
    public function getAvailableVariants()
    {
        try {
            $variants = $this->rentService->getAvailableProductVariants();
            return response()->json($variants);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to load product variants',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get rent list for DataTables
     */
    
    public function itemList(Request $request)
    {
        try {
            $filters = $request->only([
                'rent_code',
                'customer_id',
                'product_name',
                'status',
                'date_from',
                'date_to'
            ]);

            $rents = $this->rentService->getRentItems($filters);

            // For web view
            $customers = $this->rentService->getAvailableCustomers();

            return view('pages.admin.rents.items.index', compact('rents', 'customers', 'filters'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to load rents',
                    'message' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to load rent items: ' . $e->getMessage());
        }
    }

    /**
     * Send rent invoice email to customer
     */
    public function sendInvoiceEmail(Rent $rent)
    {
        try {
            $this->rentService->sendRentInvoiceEmail($rent);

            return redirect()->route('rents.index', $rent->id)
                ->with('success', 'Invoice email sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send invoice email: ' . $e->getMessage());
        }
    }

    /*
    * Status marks as delivered
    */
    public function markAsDelivered(Rent $rent)
    {
        try {
            $this->rentService->markAsDelivered($rent);

            return redirect()->route('rents.index', $rent->id)
                ->with('success', 'Rent delivered successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to mark rent as delivered: ' . $e->getMessage());
        }

    }

    /**
     * Display overdue rents report
     */
    public function overdueReport()
    {
        $thresholdDate = Carbon::now()->subDays(30)->toDateString();

        $rents = Rent::with([
                'customer',
                'items',
                'payments' => function ($query) {
                    $query->orderBy('payment_date', 'desc');
                }
            ])
            ->whereNotIn('status', ['completed', 'returned', 'cancelled'])
            ->whereDate('rent_date', '<=', $thresholdDate)
            ->where(function ($query) use ($thresholdDate) {
                $query->where(function ($q) use ($thresholdDate) {
                    $q->whereHas('payments')
                        ->whereDoesntHave('payments', function ($paymentQuery) use ($thresholdDate) {
                            $paymentQuery->whereDate('payment_date', '>', $thresholdDate);
                        });
                })->orWhereDoesntHave('payments');
            })
            ->get();

        // Calculate additional metrics
        $rents->each(function ($rent) use ($thresholdDate) {
            $rentDate = Carbon::parse($rent->rent_date);
            $rent->days_since_rent = intval(Carbon::now()->diffInDays($rentDate, true));
            $rent->is_rent_date_overdue = $rentDate->lte($thresholdDate);
            
            $lastPayment = $rent->payments->first();
            if ($lastPayment) {
                $lastPaymentDate = Carbon::parse($lastPayment->payment_date);
                $rent->days_since_last_payment = intval(Carbon::now()->diffInDays($lastPaymentDate, true));
                $rent->last_payment_date = $lastPaymentDate->toDateString();
                $rent->last_payment_amount = $lastPayment->amount;
                $rent->has_recent_payment = $lastPaymentDate->gt($thresholdDate);
            } else {
                $rent->days_since_last_payment = null;
                $rent->last_payment_date = null;
                $rent->last_payment_amount = null;
                $rent->has_recent_payment = false;
            }
            
            // Determine overdue reason
            if (!$lastPayment && $rent->is_rent_date_overdue) {
                $rent->overdue_reason = 'No payment ever and rent period exceeded 30 days';
            } elseif ($rent->is_rent_date_overdue && !$rent->has_recent_payment) {
                $rent->overdue_reason = 'Both rent period exceeded and no recent payment';
            } else {
                $rent->overdue_reason = 'Unknown';
            }
            
            // Calculate outstanding items
            $totalRented = $rent->items->sum('rent_qty');
            $totalReturned = $rent->items->sum('return_qty');
            $rent->items_outstanding = $totalRented - $totalReturned;
            
            // Calculate payment status
            $rent->total_paid = $rent->payments->sum('amount');
            $rent->remaining_balance = $rent->total_due - $rent->total_paid;
        });
        
        $summary = [
            'total_overdue_rents' => $rents->count(),
            'rent_date_overdue_count' => $rents->where('is_rent_date_overdue', true)->count(),
            'payment_overdue_count' => $rents->where('has_recent_payment', false)->count(),
            'no_payment_ever_count' => $rents->whereNull('last_payment_date')->count(),
            'as_of_date' => Carbon::now()->toDateString(),
            'threshold_date' => $thresholdDate
        ];
        
        return view('pages.admin.rents.overdue-report', compact('rents', 'summary'));
    }
}
