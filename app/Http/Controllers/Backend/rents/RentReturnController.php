<?php

namespace App\Http\Controllers\Backend\rents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Rent\RentReturnRequest;
use App\Services\{RentReturnService, RentPaymentService};
use App\Models\Backend\Rent;
use App\Models\Backend\RentReturn;
use Illuminate\Http\Request;

class RentReturnController extends Controller
{
    protected $returnService;
    protected $rentPaymentService;

    public function __construct(RentReturnService $returnService, RentPaymentService $rentPaymentService)
    {
        $this->returnService = $returnService;
        $this->rentPaymentService = $rentPaymentService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', 'all');
        $orderBy = $request->get('order_by', 'return_date');
        $orderDir = $request->get('order_dir', 'desc');

        $returns = $this->returnService->getAllReturns(['search' => $search], $status, $orderBy, $orderDir);

        return view('pages.admin.rent_returns.index', compact('returns', 'search', 'status', 'orderBy', 'orderDir'));
    }

    /**
     * Show the form for creating a return
     */
    public function create(Rent $rent)
    {
        if ($rent->status === 'completed') {
            return redirect()->route('rents.show', $rent->id)
                ->with('error', 'This rent is already completed.');
        }
        
        $rent->load('items.productVariant');
        $remainingItems = $this->returnService->getRemainingItems($rent);
        $totalPaymentByRentId = $this->rentPaymentService->getTotalPaymentByRentId($rent->id);
        
        return view('pages.admin.rent_returns.create', compact('rent', 'remainingItems', 'totalPaymentByRentId'));
    }

    /**
     * Store a newly created return
     */
    public function store(RentReturnRequest $request, Rent $rent)
    {
        #dd($request->all());
        try {
            $return = $this->returnService->createReturn($rent, $request->validated());
            
            return redirect()->route('rents.show', $rent->id)
                ->with('success', 'Return processed successfully.');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error processing return: ' . $e->getMessage());
        }
    }

    /**
     * Show return details
     */
    public function show(Rent $rent, RentReturn $return)
    {
        $return->load('items.rentItem.productVariant', 'rent.payments');
        $totalPaymentByRentId = $this->rentPaymentService->getTotalPaymentByRentId($rent->id);

        return view('pages.admin.rent_returns.show', compact('rent', 'return', 'totalPaymentByRentId'));
    }

    /**
     * Print return invoice
     */
    public function print(Rent $rent, RentReturn $return)
    {
        $return->load([
            'items.rentItem.productVariant.product',
            'rent.customer'
        ]);
        
        //get total payment for the rent
        $totalPaymentByRentId = $this->rentPaymentService->getTotalPaymentByRentId($rent->id);

        $return->total_rental_amount = $return->rent->sub_total * $return->total_days;
        // Calculate totals
        $return->current_time = now()->format('Y-m-d H:i');
        
        // Calculate item totals
        foreach ($return->items as $item) {
            $item->damage_total = $item->damage_fee ?? 0;
            $item->returned_total = $item->qty * ($item->rentItem->unit_price ?? 0);
        }
        
        // Calculate summary
        $return->total_damage_fee = $return->items->sum('damage_fee');
        
        // Calculate net amount (collect - refund)
        //$return->final_balance = ($return->total_rental_amount ?? 0) + ($return->transport ?? 0) + ($return->collect_amount ?? 0) + ($return->total_damage_fee ?? 0) - ($rent->deposit ?? 0) - ($return->refund_amount ?? 0) - ($totalPaymentByRentId ?? 0);
        #dd($return);
        return view('pages.admin.rent_returns.invoice', compact('rent', 'return', 'totalPaymentByRentId'));
    }
}
