@extends('layouts.frontend.app')

@section('title', 'KFS - Home')
@section('content')
<div id="page-services" class="page active">
    <div class="pt-20 lg:pt-24min-h-screen bg-navy-800 blueprint-grid-dark">
        
        {{-- Header --}}
        <div class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-orange-400"></i>
                    <span class="text-xs font-semibold text-orange-400 uppercase tracking-wider">Our Services</span>
                </div>
                <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                    Rent or Purchase—<span class="text-orange-400">Your Choice</span>
                </h1>
                <p class="text-lg text-steel-300 max-w-2xl mx-auto">
                    Flexible options designed to fit your project timeline and budget.
                </p>
            </div>
        </div>

        {{-- Service Cards --}}
        <section class="py-16 bg-white blueprint-grid">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- Rental & Purchase Cards --}}
                <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-16">
                    
                    {{-- Rental Card --}}
                    <div class="card-lift group relative bg-white rounded-2xl border border-steel-200 overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-orange-400 to-orange-600"></div>
                        <div class="p-8 lg:p-10">
                            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-100 transition-colors">
                                <i data-lucide="calendar-clock" class="w-7 h-7 text-orange-500"></i>
                            </div>
                            <h3 class="font-display text-2xl font-bold text-navy-800 mb-3">Equipment Rental</h3>
                            <p class="text-steel-500 mb-6 leading-relaxed">
                                Flexible scaffolding rental for projects of any duration. Pay only for what you use, when you need it.
                            </p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-orange-600"></i>
                                    </div>
                                    <span class="text-sm text-steel-600">Flexible pricing by day, week, or month</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-orange-600"></i>
                                    </div>
                                    <span class="text-sm text-steel-600">Ideal for short-term projects</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-orange-600"></i>
                                    </div>
                                    <span class="text-sm text-steel-600">Maintenance & inspection included</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-orange-600"></i>
                                    </div>
                                    <span class="text-sm text-steel-600">Free delivery & pickup</span>
                                </li>
                            </ul>
                            <a href="{{ route('frontend.quotations.create') }}" 
                                class="inline-flex items-center gap-2 text-orange-500 font-semibold hover:text-orange-600 transition-colors group/link">
                                Get a Rental Quote 
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Purchase Card --}}
                    <div class="card-lift group relative bg-navy-800 rounded-2xl overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 to-orange-500"></div>
                        <div class="p-8 lg:p-10">
                            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white/15 transition-colors">
                                <i data-lucide="badge-check" class="w-7 h-7 text-yellow-400"></i>
                            </div>
                            <h3 class="font-display text-2xl font-bold text-white mb-3">Equipment Purchase</h3>
                            <p class="text-steel-300 mb-6 leading-relaxed">
                                Invest in your own scaffolding inventory. New and certified used equipment at competitive prices.
                            </p>
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-yellow-400/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-yellow-400"></i>
                                    </div>
                                    <span class="text-sm text-steel-300">New & quality-certified used equipment</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-yellow-400/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-yellow-400"></i>
                                    </div>
                                    <span class="text-sm text-steel-300">Bulk order discounts available</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-yellow-400/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-yellow-400"></i>
                                    </div>
                                    <span class="text-sm text-steel-300">Lifetime value & asset ownership</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-full bg-yellow-400/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i data-lucide="check" class="w-3 h-3 text-yellow-400"></i>
                                    </div>
                                    <span class="text-sm text-steel-300">Warranty & after-sales support</span>
                                </li>
                            </ul>
                            <a href="{{ route('frontend.contact') }}" 
                                class="inline-flex items-center gap-2 text-yellow-400 font-semibold hover:text-yellow-300 transition-colors group/link">
                                Inquire Now 
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- How It Works --}}
                <div class="text-center mb-12">
                    <h2 class="font-display text-3xl font-bold text-navy-800 mb-4">
                        Simple <span class="text-orange-500">4-Step</span> Process
                    </h2>
                    <p class="text-lg text-steel-500 max-w-2xl mx-auto">
                        From quote to delivery, we streamline every step so you can focus on building.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="card-lift bg-white border border-steel-200 rounded-2xl p-8 text-center relative group">
                        <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="file-text" class="w-8 h-8 text-orange-500"></i>
                        </div>
                        <div class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-3">Step 01</div>
                        <h3 class="font-display text-lg font-bold text-navy-800 mb-2">Request Quote</h3>
                        <p class="text-sm text-steel-500">Tell us what you need using our online quotation form.</p>
                    </div>
                    <div class="card-lift bg-white border border-steel-200 rounded-2xl p-8 text-center relative group">
                        <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="check-circle" class="w-8 h-8 text-orange-500"></i>
                        </div>
                        <div class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-3">Step 02</div>
                        <h3 class="font-display text-lg font-bold text-navy-800 mb-2">Confirm Availability</h3>
                        <p class="text-sm text-steel-500">We verify stock, confirm pricing, and finalize details.</p>
                    </div>
                    <div class="card-lift bg-white border border-steel-200 rounded-2xl p-8 text-center relative group">
                        <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-orange-100 transition-colors">
                            <i data-lucide="truck" class="w-8 h-8 text-orange-500"></i>
                        </div>
                        <div class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-3">Step 03</div>
                        <h3 class="font-display text-lg font-bold text-navy-800 mb-2">Delivery to Site</h3>
                        <p class="text-sm text-steel-500">We deliver the equipment directly to your construction site.</p>
                    </div>
                    <div class="card-lift bg-navy-800 border border-navy-700 rounded-2xl p-8 text-center relative group">
                        <div class="w-16 h-16 bg-orange-500/20 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:bg-orange-500/30 transition-colors">
                            <i data-lucide="hard-hat" class="w-8 h-8 text-orange-400"></i>
                        </div>
                        <div class="text-xs font-bold text-orange-400 uppercase tracking-widest mb-3">Step 04</div>
                        <h3 class="font-display text-lg font-bold text-white mb-2">Start Project</h3>
                        <p class="text-sm text-steel-400">Begin work with certified, inspected scaffolding ready for action.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection