<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Customer\CustomerRequest;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeRegistration(CustomerRequest $request)
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

        return redirect()->route('customers.login')->with('success', 'Customer created successfully.');
    }

    public function updateCustomer(CustomerRequest $request, string $id)
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

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $customer = Auth::guard('customer')->user();

        if (!Hash::check($validated['current_password'], $customer->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
