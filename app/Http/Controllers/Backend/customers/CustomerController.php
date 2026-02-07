<?php

namespace App\Http\Controllers\Backend\customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Customer\CustomerRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $customers = $this->customerService
            ->getCustomers($filters, $orderBy, $orderDir)
            ->paginate(6);

        return view('pages.admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload if present
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $filename = uniqid('customer_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Backend/img/customer/'), $filename);
            $validated['profile_picture'] = $filename;
        }

        $validated['password'] = bcrypt($validated['password']);
        $this->customerService->createCustomer($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = $this->customerService->getCustomer($id);
        return view('pages.admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = $this->customerService->getCustomer($id);
        return view('pages.admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        $validated = $request->validated();

        //Remove password field if empty
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        // Handle file upload if present
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $customer = $this->customerService->getCustomer($id);
            
            // Delete old profile picture if exists
            if ($customer->profile_picture && file_exists(public_path('Backend/img/customer/' . $customer->profile_picture))) {
                unlink(public_path('Backend/img/customer/' . $customer->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = uniqid('customer_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('Backend/img/customer/'), $filename);
            $validated['profile_picture'] = $filename;
        }

        $this->customerService->updateCustomer($id, $validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->customerService->deleteCustomer($id);
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function toggleStatus($id) {
        $this->customerService->toggleCustomerStatus($id);
        return redirect()->back()->with('success', 'Customer status toggled successfully.');
    }
}
