@extends('layouts.frontend.app')

@section('title', 'Register - KFS Scaffolding')

@section('content')
<div id="page-register" class="page active">
    <div class="pt-20 lg:pt-24 min-h-screen bg-navy-900 blueprint-grid-dark flex items-center justify-center px-4 py-20">
        <div class="w-full max-w-md">
            
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-2 group mb-6">
                    <img src="{{ asset('images/logo/kfs-logo-teal.svg') }}" alt="Logo" width="150" height="90"/>
                </a>
                <h1 class="font-display text-3xl font-bold text-white mb-2">Create account</h1>
                <p class="text-steel-400">Join KFS to access rental quotes and more</p>
            </div>

            {{-- Card --}}
            <div class="bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                
                <form method="POST" action="#" class="space-y-4">
                    @csrf
                    
                    {{-- Name Fields --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">First Name <span class="req">*</span></label>
                            <input type="text" name="first_name" 
                                class="auth-input @error('first_name') border-red-500 @enderror" 
                                placeholder="John"
                                value="{{ old('first_name') }}"
                                required>
                        </div>
                        <div>
                            <label class="form-label">Last Name <span class="req">*</span></label>
                            <input type="text" name="last_name" 
                                class="auth-input @error('last_name') border-red-500 @enderror" 
                                placeholder="Smith"
                                value="{{ old('last_name') }}"
                                required>
                        </div>
                    </div>
                    
                    {{-- Company --}}
                    <div>
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company" 
                            class="auth-input" 
                            placeholder="Your company (optional)"
                            value="{{ old('company') }}">
                    </div>
                    
                    {{-- Email --}}
                    <div>
                        <label class="form-label">Email Address <span class="req">*</span></label>
                        <input type="email" name="email" 
                            class="auth-input @error('email') border-red-500 @enderror" 
                            placeholder="john@company.com"
                            value="{{ old('email') }}"
                            required>
                    </div>
                    
                    {{-- Phone --}}
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" 
                            class="auth-input" 
                            placeholder="+1 (555) 000-0000"
                            value="{{ old('phone') }}">
                    </div>
                    
                    {{-- Password --}}
                    <div>
                        <label class="form-label">Password <span class="req">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="regPassword" 
                                class="auth-input pr-12 @error('password') border-red-500 @enderror" 
                                placeholder="Min 8 characters"
                                required>
                            <button type="button" onclick="togglePasswordVis('regPassword', this)" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-steel-500 hover:text-steel-300 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Confirm Password --}}
                    <div>
                        <label class="form-label">Confirm Password <span class="req">*</span></label>
                        <input type="password" name="password_confirmation" 
                            class="auth-input" 
                            placeholder="Re-enter password"
                            required>
                    </div>
                    
                    {{-- Terms --}}
                    <div class="flex items-start gap-3 pt-1">
                        <input type="checkbox" name="agree_terms" id="agreeTerms" 
                            class="w-4 h-4 mt-0.5 rounded border-white/20 bg-navy-800 text-orange-500 focus:ring-orange-500 cursor-pointer"
                            required>
                        <label for="agreeTerms" class="text-xs text-steel-400 cursor-pointer">
                            I agree to the 
                            <a href="#" class="text-orange-400 hover:text-orange-300">Terms of Service</a> 
                            and 
                            <a href="#" class="text-orange-400 hover:text-orange-300">Privacy Policy</a>
                        </label>
                    </div>
                    
                    {{-- Submit --}}
                    <button type="submit" 
                        class="w-full py-3.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Create Account
                    </button>
                </form>

                {{-- Login Link --}}
                <div class="mt-6 pt-5 border-t border-white/10 text-center">
                    <p class="text-sm text-steel-400">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-orange-400 font-semibold hover:text-orange-300 transition-colors ml-1">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>

            {{-- Back to Home --}}
            <p class="text-center text-xs text-steel-600 mt-6">
                <a href="{{ route('frontend.home') }}" class="hover:text-steel-400 transition-colors">← Back to Home</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVis(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
        } else {
            input.type = 'password';
            btn.innerHTML = '<i data-lucide="eye" class="w-4 h-4"></i>';
        }
        lucide.createIcons();
    }
</script>
@endpush