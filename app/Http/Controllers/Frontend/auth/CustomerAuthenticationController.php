<?php

namespace App\Http\Controllers\Frontend\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthenticationController extends Controller
{
    public function showLoginFrom()
    {
        return view('pages.frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            return redirect()->route('frontend.quotations.create')->with('success', 'Logged in successfully');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function showRegistrationForm()
    {
        return view('pages.frontend.auth.register');
    }

    public function register(Request $request) {}

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customers.showLoginFrom')
            ->with('success', 'Logged out successfully');
    }
}
