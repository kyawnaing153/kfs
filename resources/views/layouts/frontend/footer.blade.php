<footer class="bg-navy-900 border-t border-white/5 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            
            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('frontend.home') }}" class="inline-flex items-center gap-2 group mb-6">
                        <img src="{{ asset('images/logo/kfs-logo-teal.svg') }}" alt="Logo" width="150" height="90"/>
                    </a>
                </div>
                <p class="text-sm text-steel-400 leading-relaxed mb-4">
                    Professional scaffolding sale & rental solutions. Built for safety, designed for strength.
                </p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs text-steel-400">Systems Online</span>
                </div>
            </div>

            {{-- Navigation Links --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigation</h4>
                <ul class="space-y-2.5">
                    <li><button onclick="navigateTo('home')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Home</button></li>
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Products</button></li>
                    <li><button onclick="navigateTo('services')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Services</button></li>
                    <li><button onclick="navigateTo('quotation')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Quotation</button></li>
                    <li><button onclick="navigateTo('contact')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Contact</button></li>
                </ul>
            </div>

            {{-- Equipment --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Equipment</h4>
                <ul class="space-y-2.5">
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Adjustable Props</button></li>
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Steel Pipes</button></li>
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Frame Systems</button></li>
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Steel Planks</button></li>
                    <li><button onclick="navigateTo('products')" class="text-sm text-steel-400 hover:text-orange-400 transition-colors">Couplers & Wheels</button></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contact</h4>
                <ul class="space-y-2.5">
                    <li class="text-sm text-steel-400">
                        <a href="tel:{{ $settings['phone'] ?? '' }}">{{ $settings['phone'] ?? '' }}</a>
                    </li>
                    <li class="text-sm text-steel-400">
                        <a href="mailto:{{ $settings['email'] ?? '' }}">{{ $settings['email'] ?? '' }}</a>
                    </li>
                    <li class="text-sm text-steel-400">
                        {{ $settings['address'] ?? '' }}
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-steel-500">© {{ date('Y') }} KFS Scaffolding System. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="text-xs text-steel-500 hover:text-steel-300 transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-steel-500 hover:text-steel-300 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>