{{-- Navigation Bar --}}
<nav id="navbar"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-navy-800/95 backdrop-blur-md shadow-xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">

            {{-- Logo --}}
            <a href="{{ route('frontend.home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo/kfs-logo-teal.svg') }}" alt="Logo" width="90" height="60"/>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('frontend.home') }}"
                    class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('frontend.home') ? 'active text-orange-400' : 'text-steel-200 hover:text-white' }}">
                    Home
                </a>
                <a href="{{ route('frontend.products.index') }}"
                    class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('frontend.products.index') ? 'active text-orange-400' : 'text-steel-200 hover:text-white' }}">
                    Products
                </a>
                <a href="{{ route('frontend.services') }}"
                    class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('frontend.services') ? 'active text-orange-400' : 'text-steel-200 hover:text-white' }}">
                    Services
                </a>
                
                <a href="{{ route('frontend.contact') }}"
                    class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('frontend.contact') ? 'active text-orange-400' : 'text-steel-200 hover:text-white' }}">
                    Contact
                </a>
                <a href="{{ route('frontend.quotations.create') }}"
                    class="nav-link text-sm font-medium transition-colors {{ request()->routeIs('frontend.quotations.create') ? 'active text-orange-400' : 'text-steel-200 hover:text-white' }}">
                    Get Quotation
                </a>
            </div>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden lg:flex items-center gap-3">
                @auth('customer')
                    {{-- Logged In User Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-2 px-3 py-1.5 bg-white/10 rounded-lg border border-white/10 hover:bg-white/15 transition-all">
                            <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-3.5 h-3.5 text-white"></i>
                            </div>
                            <span class="text-sm font-medium text-white">{{ Auth::guard('customer')->user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-steel-300 transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute right-0 mt-2 w-56 bg-navy-800/95 backdrop-blur-md rounded-lg shadow-xl border border-white/10 overflow-hidden z-50">
                            
                            <div class="py-1">
                                {{-- Customer Dashboard --}}
                                <a href="{{ route('frontend.customer.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 transition-colors">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                    <span>Dashboard</span>
                                </a>

                                {{-- Show Quotations --}}
                                <a href="{{ route('frontend.quotations.create') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 transition-colors">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                    <span>Get New Quotation</span>
                                </a>

                                {{-- Divider --}}
                                <div class="my-1 border-t border-white/10"></div>

                                {{-- Logout --}}
                                <form method="POST" action="{{ route('customers.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:text-red-300 hover:bg-white/5 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customers.showLoginFrom') }}"
                        class="nav-link px-4 py-2 text-sm font-medium text-steel-200 hover:text-white transition-colors">
                        Login
                    </a>
                    <a href="{{ route('customers.register') }}"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25">
                        Register
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Toggle --}}
            <button id="mobileToggle" class="lg:hidden p-2 text-white" aria-label="Toggle menu">
                <i data-lucide="menu" class="w-6 h-6" id="menuIcon"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="mobile-menu lg:hidden">
            <div class="py-4 space-y-1 border-t border-white/10">
                <a href="{{ route('frontend.home') }}"
                    class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    Home
                </a>
                <a href="{{ route('frontend.products.index') }}"
                    class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    Products
                </a>
                <a href="{{ route('frontend.services') }}"
                    class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    Services
                </a>
                <a href="{{ route('frontend.quotations.create') }}"
                    class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    Quotation
                </a>
                <a href="{{ route('frontend.contact') }}"
                    class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                    Contact
                </a>

                @auth('customer')
                    {{-- Logged In Mobile Menu Options --}}
                    <div class="mt-4 pt-2 border-t border-white/10">
                        <a href="{{ route('frontend.customer.dashboard') }}"
                            class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('frontend.quotations.create') }}"
                            class="block w-full text-left px-4 py-2.5 text-sm text-steel-200 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                            Get New Quotation
                        </a>

                        <form method="POST" action="{{ route('customers.logout') }}" class="pt-3 px-4 border-t border-white/5 mt-2">
                            @csrf
                            <button type="submit"
                                class="w-full text-center px-4 py-2.5 text-sm font-medium text-red-400 border border-white/20 rounded-lg hover:bg-white/5 transition-all">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-3 px-4 flex gap-3 border-t border-white/5 mt-2">
                        <a href="{{ route('customers.showLoginFrom') }}"
                            class="flex-1 text-center px-4 py-2.5 text-sm font-medium text-steel-200 border border-white/20 rounded-lg hover:bg-white/5 transition-all">
                            Login
                        </a>
                        <a href="{{ route('customers.register') }}"
                            class="flex-1 text-center px-4 py-2.5 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition-all">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Alpine.js for dropdown functionality --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Lucide Icons --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>