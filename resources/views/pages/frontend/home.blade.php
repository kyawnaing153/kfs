@extends('layouts.frontend.app')

@section('title', 'KFS - Home')
@php
    use Illuminate\Support\Str;
@endphp
@section('content')
    <div id="page-home" class="page active">
    <div class="pt-20 lg:pt-24 min-h-screen bg-navy-800 blueprint-grid-dark">
        {{-- Hero Section --}}
        <section id="hero" class="relative min-h-screen flex items-center overflow-hidden">
            <div class="absolute inset-0">
                <img src="https://kyawfamilyscaffolding.com/frontend/assets/img/kfs1.png" alt="Kyaw Family Scaffolding"
                    class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 hero-overlay"></div>
            <div class="absolute inset-0 blueprint-grid-dark"></div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-500 via-yellow-400 to-orange-500">
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-0 w-full">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <div class="fade-up">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-1.5 bg-orange-500/15 border border-orange-500/25 rounded-full mb-6">
                            <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                            <span class="text-xs font-semibold text-orange-300 uppercase tracking-wider">Trusted by 500+
                                Companies</span>
                        </div>
                        <h1
                            class="font-display text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.05] mb-6">
                            Reliable <br>
                            <span class="text-orange-400">Scaffolding</span><br>
                            Solutions
                        </h1>
                        <p class="text-lg sm:text-xl text-steel-300 font-light leading-relaxed mb-8 max-w-xl">
                            Built for Safety. Designed for Strength.<br>
                            Rent or purchase professional scaffolding equipment for your next project.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button onclick="navigateTo('products')"
                                class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-all shadow-xl shadow-orange-500/30 group">
                                Browse Equipment
                                <i data-lucide="arrow-right"
                                    class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <button onclick="navigateTo('quotation')"
                                class="inline-flex items-center justify-center gap-2 px-8 py-4 border-2 border-white/20 text-white font-semibold rounded-xl hover:bg-white/10 hover:border-white/40 transition-all">
                                Request Rental Quote
                            </button>
                        </div>
                        <div class="flex items-center gap-8 mt-12 pt-8 border-t border-white/10">
                            <div>
                                <div class="text-3xl font-bold text-white font-display">10+</div>
                                <div class="text-xs text-steel-400 uppercase tracking-wider mt-1">Years Experience</div>
                            </div>
                            <div class="w-px h-12 bg-white/10"></div>
                            <div>
                                <div class="text-3xl font-bold text-white font-display">500+</div>
                                <div class="text-xs text-steel-400 uppercase tracking-wider mt-1">Projects Done</div>
                            </div>
                            <div class="w-px h-12 bg-white/10"></div>
                            <div>
                                <div class="text-3xl font-bold text-white font-display">24/7</div>
                                <div class="text-xs text-steel-400 uppercase tracking-wider mt-1">Support</div>
                            </div>
                        </div>
                    </div>

                    {{-- Trending Products Carousel --}}
                    <div class="fade-right hidden lg:block">
                        <div class="relative">
                            <div class="absolute -inset-4 bg-orange-500/10 rounded-3xl blur-2xl"></div>
                            <div
                                class="relative bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl overflow-hidden">

                                {{-- Header with Controls --}}
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-500/20 rounded-xl flex items-center justify-center">
                                            <i data-lucide="trending-up" class="w-5 h-5 text-orange-400"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold text-white block">Trending Products</span>
                                            <span class="text-[10px] text-steel-400">Most rented this week</span>
                                        </div>
                                    </div>

                                    {{-- Carousel Navigation Arrows --}}
                                    <div class="flex items-center gap-2">
                                        <button onclick="prevSlide()"
                                            class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center hover:bg-white/10 transition-colors carousel-prev">
                                            <i data-lucide="chevron-left" class="w-4 h-4 text-white"></i>
                                        </button>
                                        <button onclick="nextSlide()"
                                            class="w-8 h-8 bg-white/5 border border-white/10 rounded-lg flex items-center justify-center hover:bg-white/10 transition-colors carousel-next">
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-white"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Carousel Container --}}
                                <div class="relative">
                                    <div id="trendingCarousel"
                                        class="flex gap-3 transition-transform duration-500 ease-in-out">

                                        {{-- Product Section --}}
                                        @forelse ($products as $product)
                                            <div
                                                class="flex-shrink-0 w-48 bg-white/5 rounded-xl overflow-hidden hover:bg-white/10 transition-all cursor-pointer group hover:scale-[1.02]">
                                                <div class="aspect-[4/3] overflow-hidden relative">
                                                    <img src="{{ $product->thumb_url }}" alt="{{ $product->product_name }}"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                    <div
                                                        class="absolute inset-0 bg-gradient-to-t from-navy-900/60 to-transparent">
                                                    </div>
                                                    <div class="absolute top-3 right-3">
                                                        <span
                                                            class="px-2 py-0.5 bg-orange-500 text-white text-[9px] font-bold uppercase tracking-wider rounded-full flex items-center gap-1">
                                                            <i data-lucide="flame" class="w-2.5 h-2.5"></i>
                                                            Hot
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <h4 class="text-sm font-semibold text-white mb-1">
                                                        {{ $product->product_name }}</h4>
                                                    <p class="text-[10px] text-steel-400 mb-2">
                                                        {{ Str::limit($product->description, 30) }}</p>
                                                    <div class="flex items-center justify-between">
                                                        <div>
                                                            <span class="text-xs text-steel-500">From</span>
                                                            <span class="text-xs font-bold text-orange-400 ml-1">Ks
                                                                {{ number_format($product->rent_price ?? 0, 0) }}</span>
                                                            <span class="text-[10px] text-steel-500">/day</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p>No featured products found.</p>
                                        @endforelse

                                    </div>
                                </div>

                                {{-- Pagination Dots --}}
                                <div class="flex items-center justify-center gap-1.5 mt-4">
                                    <button onclick="goToSlide(0)"
                                        class="w-2 h-2 rounded-full bg-orange-500 transition-all carousel-dot active"></button>
                                    <button onclick="goToSlide(1)"
                                        class="w-2 h-2 rounded-full bg-white/20 transition-all carousel-dot"></button>
                                    <button onclick="goToSlide(2)"
                                        class="w-2 h-2 rounded-full bg-white/20 transition-all carousel-dot"></button>
                                </div>

                                {{-- View All Button --}}
                                <a href="{{ route('frontend.products.index') }}"
                                    class="w-full mt-4 py-2.5 text-center text-xs text-orange-400 hover:text-white hover:bg-orange-500/10 rounded-lg transition-all flex items-center justify-center gap-2 group border border-orange-500/20">
                                    <span>View All Products</span>
                                    <i data-lucide="arrow-right"
                                        class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform"></i>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/40 animate-bounce">
                <span class="text-[10px] uppercase tracking-widest">Scroll</span>
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </section>

        {{-- Products Section (directly after Home) --}}
        <section class="py-20 lg:py-28 bg-steel-50 blueprint-grid">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 fade-up">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-orange-50 border border-orange-200 rounded-full mb-4">
                        <i data-lucide="package" class="w-3.5 h-3.5 text-orange-500"></i>
                        <span class="text-xs font-semibold text-orange-600 uppercase tracking-wider">Featured
                            Products</span>
                    </div>
                    <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-navy-800 mb-4">
                        Professional <span class="text-orange-500">Equipment</span>
                    </h2>
                    <p class="text-lg text-steel-500 max-w-2xl mx-auto">
                        Browse our comprehensive range of scaffolding equipment, all certified to international safety
                        standards.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse ($products as $index => $product)
                        @include('components.frontend.products.product-card', [
                            'image' => $product->thumb_url,
                            'title' => $product->product_name,
                            'description' => Str::limit($product->description, 80),
                            'price' => 'Ks ' . number_format($product->rent_price ?? 0, 0) . '/day',
                            'stock' => $product->low_stock ? 'Low Stock' : 'In Stock',
                            'delay' => $index * 0.1 . 's',
                        ])
                    @empty
                        <p class="text-gray-400">No products available.</p>
                    @endforelse
                </div>

                <div class="text-center mt-10 fade-up">
                    <a href="{{ route('frontend.products.index') }}"
                        class="inline-flex items-center gap-2 px-8 py-4 border-2 border-navy-800 text-navy-800 font-semibold rounded-xl hover:bg-navy-800 hover:text-white transition-all">
                        View All Products
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        </section>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ===== TRENDING PRODUCTS CAROUSEL =====
        let currentSlide = 0;
        const totalSlides = 3; // Number of visible groups
        const slidesPerView = 2; // Products visible at once

        function updateCarousel() {
            const carousel = document.getElementById('trendingCarousel');
            const slideWidth = carousel.children[0].offsetWidth + 12; // width + gap
            const offset = currentSlide * slideWidth * slidesPerView;
            carousel.style.transform = `translateX(-${offset}px)`;

            // Update dots
            document.querySelectorAll('.carousel-dot').forEach((dot, index) => {
                dot.classList.toggle('bg-orange-500', index === currentSlide);
                dot.classList.toggle('bg-white/20', index !== currentSlide);
            });
        }

        function nextSlide() {
            if (currentSlide < totalSlides - 1) {
                currentSlide++;
                updateCarousel();
            }
        }

        function prevSlide() {
            if (currentSlide > 0) {
                currentSlide--;
                updateCarousel();
            }
        }

        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
        }

        // Optional: Auto-play
        setInterval(() => {
            if (currentSlide < totalSlides - 1) {
                nextSlide();
            } else {
                currentSlide = 0;
                updateCarousel();
            }
        }, 5000); // Change slide every 5 seconds
    </script>
@endpush
