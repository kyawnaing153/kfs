{{-- resources/views/frontend/quotations/create.blade.php --}}
@extends('layouts.frontend.app')

@section('title', 'Request Quotation - KFS')

@push('styles')
<style>
    .step-item {
        @apply relative flex items-center gap-4;
    }
    .step-circle {
        @apply w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg transition-all;
    }
    .step-active .step-circle {
        @apply bg-orange-500 text-white shadow-lg shadow-orange-500/30;
    }
    .step-completed .step-circle {
        @apply bg-green-500 text-white;
    }
    .step-pending .step-circle {
        @apply bg-white/10 text-steel-400;
    }
</style>
@endpush

@section('content')
<div class="pt-24 lg:pt-28 min-h-screen bg-navy-800 blueprint-grid-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Header --}}
        <div class="text-center mb-12 fade-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                <i data-lucide="file-text" class="w-3.5 h-3.5 text-orange-400"></i>
                <span class="text-xs font-semibold text-orange-300 uppercase tracking-wider">Request Quote</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                Get Your <span class="text-orange-400">Custom Quotation</span>
            </h1>
            <p class="text-lg text-steel-300 max-w-2xl mx-auto">
                Fill out the form below and our team will respond within 24 hours with a competitive quote.
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-2">
                <div class="bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                    
                    {{-- Progress Steps --}}
                    <div class="flex justify-between mb-8 pb-4 border-b border-white/10">
                        <div class="step-item step-active">
                            <div class="step-circle">1</div>
                            <span class="text-sm font-medium text-white hidden sm:inline">Project Details</span>
                        </div>
                        <div class="step-item step-pending">
                            <div class="step-circle">2</div>
                            <span class="text-sm font-medium text-steel-400 hidden sm:inline">Equipment</span>
                        </div>
                        <div class="step-item step-pending">
                            <div class="step-circle">3</div>
                            <span class="text-sm font-medium text-steel-400 hidden sm:inline">Contact Info</span>
                        </div>
                        <div class="step-item step-pending">
                            <div class="step-circle">4</div>
                            <span class="text-sm font-medium text-steel-400 hidden sm:inline">Review</span>
                        </div>
                    </div>

                    <form action="{{ route('frontend.quotation.store') }}" method="POST" id="quotationForm">
                        @csrf
                        
                        {{-- Step 1: Project Details --}}
                        <div class="step-content" data-step="1">
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Project Name *</label>
                                    <input type="text" name="project_name" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                                        placeholder="e.g., Downtown Office Building Construction">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Project Location *</label>
                                    <input type="text" name="location" required
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all"
                                        placeholder="Full address or area">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Project Duration *</label>
                                        <select name="duration" required
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                            <option value="">Select duration</option>
                                            <option value="1-7">1-7 days</option>
                                            <option value="8-14">8-14 days</option>
                                            <option value="15-30">15-30 days</option>
                                            <option value="31-60">1-2 months</option>
                                            <option value="61-90">2-3 months</option>
                                            <option value="90+">3+ months</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Project Type *</label>
                                        <select name="project_type" required
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                            <option value="">Select type</option>
                                            <option value="residential">Residential</option>
                                            <option value="commercial">Commercial</option>
                                            <option value="industrial">Industrial</option>
                                            <option value="infrastructure">Infrastructure</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Project Description</label>
                                    <textarea name="description" rows="4"
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500"
                                        placeholder="Tell us more about your project requirements..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: Equipment Selection --}}
                        <div class="step-content hidden" data-step="2">
                            <div class="space-y-4">
                                <p class="text-steel-300 text-sm mb-4">Select the equipment you need for your project</p>
                                
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                                    @php
                                        $dummyProducts = [
                                            ['id' => 1, 'name' => 'Standard Scaffolding Frame', 'price' => 2500, 'unit' => 'set', 'image' => 'https://placehold.co/400x300'],
                                            ['id' => 2, 'name' => 'Heavy Duty Scaffolding', 'price' => 4500, 'unit' => 'set', 'image' => 'https://placehold.co/400x300'],
                                            ['id' => 3, 'name' => 'Scaffolding Boards', 'price' => 850, 'unit' => 'piece', 'image' => 'https://placehold.co/400x300'],
                                            ['id' => 4, 'name' => 'Guard Rail System', 'price' => 1200, 'unit' => 'piece', 'image' => 'https://placehold.co/400x300'],
                                            ['id' => 5, 'name' => 'Base Jacks', 'price' => 350, 'unit' => 'piece', 'image' => 'https://placehold.co/400x300'],
                                            ['id' => 6, 'name' => 'Cross Braces', 'price' => 450, 'unit' => 'pair', 'image' => 'https://placehold.co/400x300'],
                                        ];
                                    @endphp

                                    @foreach($dummyProducts as $product)
                                    <div class="equipment-item bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-all">
                                        <div class="flex gap-4">
                                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-20 h-20 object-cover rounded-lg">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-white">{{ $product['name'] }}</h4>
                                                <p class="text-xs text-orange-400">Ks {{ number_format($product['price']) }}/{{ $product['unit'] }}/day</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="qty-minus w-8 h-8 bg-white/10 rounded-lg text-white hover:bg-orange-500 transition-all">-</button>
                                                <input type="number" name="equipment[{{ $product['id'] }}]" value="0" min="0" class="qty-input w-16 text-center px-2 py-1 bg-white/5 border border-white/10 rounded-lg text-white">
                                                <button type="button" class="qty-plus w-8 h-8 bg-white/10 rounded-lg text-white hover:bg-orange-500 transition-all">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Contact Information --}}
                        <div class="step-content hidden" data-step="3">
                            <div class="space-y-5">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Full Name *</label>
                                        <input type="text" name="name" required
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Company Name</label>
                                        <input type="text" name="company"
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Email *</label>
                                        <input type="email" name="email" required
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-white mb-2">Phone Number *</label>
                                        <input type="tel" name="phone" required
                                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Preferred Contact Method</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="contact_method" value="email" checked class="text-orange-500">
                                            <span class="text-steel-300">Email</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="contact_method" value="phone" class="text-orange-500">
                                            <span class="text-steel-300">Phone</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="contact_method" value="whatsapp" class="text-orange-500">
                                            <span class="text-steel-300">WhatsApp</span>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-white mb-2">Special Requirements</label>
                                    <textarea name="special_requirements" rows="3"
                                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500"
                                        placeholder="Any specific requirements or deadlines?"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: Review --}}
                        <div class="step-content hidden" data-step="4">
                            <div class="space-y-4">
                                <div class="bg-orange-500/10 border border-orange-500/20 rounded-lg p-4">
                                    <h4 class="font-semibold text-white mb-2">Review Your Request</h4>
                                    <p class="text-sm text-steel-300">Please review your details before submitting</p>
                                </div>
                                
                                <div class="review-details space-y-3 text-sm">
                                    <!-- Dynamic review content will be populated via JS -->
                                </div>

                                <div class="bg-white/5 rounded-lg p-4">
                                    <label class="flex items-center gap-3">
                                        <input type="checkbox" required class="w-4 h-4 text-orange-500 rounded">
                                        <span class="text-sm text-steel-300">I agree to the <a href="#" class="text-orange-400 hover:underline">Terms and Conditions</a></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="flex justify-between gap-4 mt-8 pt-6 border-t border-white/10">
                            <button type="button" id="prevBtn" class="hidden px-6 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all">
                                Previous
                            </button>
                            <button type="button" id="nextBtn" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all ml-auto">
                                Next
                            </button>
                            <button type="submit" id="submitBtn" class="hidden px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-gradient-to-br from-orange-500/20 to-orange-600/10 border border-orange-500/30 rounded-2xl p-6">
                    <i data-lucide="clock" class="w-10 h-10 text-orange-400 mb-3"></i>
                    <h3 class="text-lg font-semibold text-white mb-2">Quick Response</h3>
                    <p class="text-steel-300 text-sm mb-4">Get your customized quotation within 24 hours</p>
                    <div class="flex items-center gap-2 text-orange-400 text-sm">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>No obligation to purchase</span>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h3 class="font-semibold text-white mb-3">Why Choose Us?</h3>
                    <div class="space-y-3">
                        <div class="flex gap-3">
                            <i data-lucide="shield" class="w-5 h-5 text-orange-400 flex-shrink-0"></i>
                            <span class="text-sm text-steel-300">Certified safety equipment</span>
                        </div>
                        <div class="flex gap-3">
                            <i data-lucide="truck" class="w-5 h-5 text-orange-400 flex-shrink-0"></i>
                            <span class="text-sm text-steel-300">Free delivery within city limits</span>
                        </div>
                        <div class="flex gap-3">
                            <i data-lucide="headphones" class="w-5 h-5 text-orange-400 flex-shrink-0"></i>
                            <span class="text-sm text-steel-300">24/7 technical support</span>
                        </div>
                        <div class="flex gap-3">
                            <i data-lucide="credit-card" class="w-5 h-5 text-orange-400 flex-shrink-0"></i>
                            <span class="text-sm text-steel-300">Flexible payment terms</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 4;

    function updateSteps() {
        // Update step indicators
        for(let i = 1; i <= totalSteps; i++) {
            const stepItem = document.querySelector(`.step-item:nth-child(${i})`);
            if(i < currentStep) {
                stepItem.classList.add('step-completed');
                stepItem.classList.remove('step-active', 'step-pending');
            } else if(i === currentStep) {
                stepItem.classList.add('step-active');
                stepItem.classList.remove('step-completed', 'step-pending');
            } else {
                stepItem.classList.add('step-pending');
                stepItem.classList.remove('step-completed', 'step-active');
            }
        }

        // Show/hide step contents
        for(let i = 1; i <= totalSteps; i++) {
            const content = document.querySelector(`.step-content[data-step="${i}"]`);
            if(i === currentStep) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        }

        // Update navigation buttons
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if(currentStep === 1) {
            prevBtn.classList.add('hidden');
        } else {
            prevBtn.classList.remove('hidden');
        }

        if(currentStep === totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
    }

    // Quantity buttons
    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            input.value = parseInt(input.value) + 1;
        });
    });

    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.qty-input');
            if(parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
            }
        });
    });

    document.getElementById('nextBtn').addEventListener('click', () => {
        if(currentStep < totalSteps) {
            currentStep++;
            updateSteps();
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        if(currentStep > 1) {
            currentStep--;
            updateSteps();
        }
    });

    updateSteps();
</script>
@endpush
@endsection