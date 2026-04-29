@extends('layouts.frontend.app')

@section('title', 'Login - KFS Scaffolding')

@section('content')
<div id="page-login" class="page active">
    <div class="pt-20 lg:pt-24 min-h-screen bg-navy-900 blueprint-grid-dark flex items-center justify-center px-4 py-20">
        <div class="w-full max-w-md">
            
            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-2 group mb-6">
                    <img src="{{ asset('images/logo/kfs-logo-teal.svg') }}" alt="Logo" width="150" height="90"/>
                </a>
                <h1 class="font-display text-3xl font-bold text-white mb-2">Welcome back</h1>
                <p class="text-steel-400">Sign in to your account to continue</p>
            </div>

            {{-- Card --}}
            <div class="bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                
                {{-- Session Status --}}
                @if(session('status'))
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-green-500/10 border border-green-500/20 rounded-xl text-sm text-green-400">
                        <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                
                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-red-500/10 border border-red-500/20 rounded-xl text-sm text-red-400">
                        <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('customers.login') }}" class="space-y-5">
                    @csrf
                    
                    {{-- Email --}}
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" 
                            class="auth-input @error('email') border-red-500 @enderror" 
                            placeholder="your@email.com" 
                            {{-- value="{{ old('email') }}" --}}
                            required autofocus autocomplete="email">
                    </div>
                    
                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="form-label" style="margin-bottom:0">Password</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs text-orange-400 hover:text-orange-300 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="loginPassword" 
                                class="auth-input pr-12 @error('password') border-red-500 @enderror" 
                                placeholder="••••••••" 
                                required autocomplete="current-password">
                            <button type="button" onclick="togglePasswordVis('loginPassword', this)" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-steel-500 hover:text-steel-300 transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Remember Me --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember" 
                            class="w-4 h-4 rounded border-white/20 bg-navy-800 text-orange-500 focus:ring-orange-500">
                        <label for="remember" class="text-sm text-steel-400 cursor-pointer">Remember me</label>
                    </div>
                    
                    {{-- Submit --}}
                    <button type="submit" 
                        class="w-full py-3.5 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25 flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Sign In
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-6 pt-5 border-t border-white/10 text-center">
                    <p class="text-sm text-steel-400">
                        Don't have an account?
                        <a href="{{ route('customers.register') }}" class="text-orange-400 font-semibold hover:text-orange-300 transition-colors ml-1">
                            Create one
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