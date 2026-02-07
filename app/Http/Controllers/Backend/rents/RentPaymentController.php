<?php

namespace App\Http\Controllers\Backend\rents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Rent\RentPaymentRequest;
use App\Services\RentPaymentService;
use App\Models\Backend\Rent;
use App\Models\Backend\RentPayment;
use Illuminate\Http\Request;

class RentPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(RentPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of payments for a specific rent
     */
    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('payment_method')) {
            $filters['payment_method'] = $request->payment_method;
        }

        if ($request->filled('rent_id')) {
            $filters['rent_id'] = $request->rent_id;
        }

        if ($request->filled('payment_date')) {
            $filters['payment_date'] = $request->payment_date;
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $filters['start_date'] = $request->start_date;
            $filters['end_date'] = $request->end_date;
        }

        if ($request->filled('customer_id')) {
            $filters['customer_id'] = $request->customer_id;
        }

        if ($request->filled('payment_for')) {
            $filters['payment_for'] = $request->payment_for;
        }

        $orderBy = $request->input('order_by', 'payment_date');
        $orderDir = $request->input('order_dir', 'desc');

        // Get payments with filters
        $payments = $this->paymentService
            ->getPayments($filters, $orderBy, $orderDir)
            ->paginate(15)
            ->withQueryString();

        // Get payment statistics
        $statistics = $this->paymentService->getPaymentStatistics();

        // Get available customers for filter dropdown
        $customers = \App\Models\Customer::active()
            ->orderBy('name')
            ->get();

        // Get payment types for filter dropdown
        $paymentTypes = RentPayment::distinct('payment_for')
            ->whereNotNull('payment_for')
            ->orderBy('payment_for')
            ->pluck('payment_for');

        return view('pages.admin.rent_payments.index', compact(
            'payments',
            'statistics',
            'customers',
            'paymentTypes'
        ));
    }

    /**
     * Show the form for creating a payment
     */
    public function create(Rent $rent)
    {
        $dueAmount = $rent->total_due;
        $lastPayment = $rent->payments()->latest()->first();

        return view('pages.admin.rent_payments.create', compact('rent', 'dueAmount', 'lastPayment'));
    }

    /**
     * Store a newly created payment
     */
    public function store(RentPaymentRequest $request, Rent $rent)
    {
        #dd($request->all());
        try {
            $payment = $this->paymentService->createPayment($rent, $request->validated());

            return redirect()->route('rents.show', $rent->id)
                ->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }

    /**
     * Show payment receipt
     */
    public function show(Rent $rent, RentPayment $payment)
    {
        return view('pages.admin.rent_payments.show', compact('rent', 'payment'));
    }
}
