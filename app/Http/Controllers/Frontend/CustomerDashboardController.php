<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Services\CustomerRentService;
use App\Models\Frontend\Quotation;
use App\Models\Backend\Rent;

class CustomerDashboardController extends Controller
{
    protected CustomerRentService $customerRentService;

    public function __construct(CustomerRentService $customerRentService)
    {
        $this->customerRentService = $customerRentService;
    }

    public function index()
    {
        $customerId = Auth::guard('customer')->user()->id;
        $customer = Customer::findOrFail($customerId);

        // Get rents with their items
        $rents = $this->customerRentService->getRentsByCustomerId($customerId);

        // Get quotations with their items
        $quotations = Quotation::where('customer_id', $customerId)
            ->with(['customer', 'items.productVariant.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_quotations' => $quotations->count(),
            'pending_quotations' => $quotations->where('status', 'submitted')->count(),
            'approved_quotations' => $quotations->where('status', 'approved')->count(),
            'total_rents' => $rents->count(),
            'active_rents' => $rents->where('status', 'ongoing')->count(),
            'completed_rents' => $rents->where('status', 'completed')->count(),
            'total_spent' => $rents->sum('total'),
        ];

        return view('pages.frontend.customers.dashboard', compact('customer', 'rents', 'quotations', 'stats'));
    }

    public function invoice(Rent $rent)
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

        $rent->daily_subtotal = $dailyRentalSubtotal;
        $rent->tax_amount = $taxAmount;

        return view('pages.frontend.customers.rental-invoice', compact('rent'));
    }
}
