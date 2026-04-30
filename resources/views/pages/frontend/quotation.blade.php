@extends('layouts.frontend.app')

@section('title', 'Quotation - KFS Scaffolding')

@section('content')
    <div id="page-quotation" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-navy-800 blueprint-grid-dark">
            <div class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl pointer-events-none">
                </div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-400/5 rounded-full blur-3xl pointer-events-none">
                </div>

                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
                    {{-- Header --}}
                    <div class="text-center mb-10">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-orange-400"></i>
                            <span class="text-xs font-semibold text-orange-400 uppercase tracking-wider">Rental
                                Quotation</span>
                        </div>
                        <h1 class="font-display text-3xl sm:text-4xl font-bold text-white mb-4">
                            Create a <span class="text-orange-400">Quotation</span>
                        </h1>
                        <p class="text-steel-300 max-w-2xl mx-auto">Fill in the details below to generate your quotation.
                        </p>
                    </div>

                    {{-- Login Prompt --}}
                    @guest
                        <div class="max-w-2xl mx-auto mb-8">
                            <div
                                class="flex items-start gap-4 px-5 py-4 bg-orange-500/10 border border-orange-500/25 rounded-xl">
                                <i data-lucide="info" class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5"></i>
                                <div class="flex-1">
                                    <p class="text-sm text-orange-200 font-medium">Login to auto-detect your Customer ID</p>
                                    <p class="text-xs text-orange-300/70 mt-1">You can still submit a quote as a guest, but
                                        logging in streamlines the process.</p>
                                </div>
                                <a href="{{ route('customers.login') }}"
                                    class="px-4 py-2 bg-orange-500 text-white text-xs font-semibold rounded-lg hover:bg-orange-600 transition-colors flex-shrink-0">Login</a>
                            </div>
                        </div>
                    @endguest

                    <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden">
                        {{-- Progress Steps --}}
                        <div class="px-6 sm:px-8 pt-6">
                            <div class="flex items-center justify-between">
                                <button type="button" id="step1Btn"
                                    class="step-btn flex flex-col items-center gap-2 group" data-step="1">
                                    <div
                                        class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold shadow-lg">
                                        1</div>
                                    <span class="text-sm font-medium text-white">Products</span>
                                </button>
                                <div class="flex-1 h-0.5 bg-white/10 mx-4"></div>
                                <button type="button" id="step2Btn"
                                    class="step-btn flex flex-col items-center gap-2 group opacity-50" data-step="2">
                                    <div
                                        class="w-10 h-10 rounded-full bg-steel-700 flex items-center justify-center text-white font-bold">
                                        2</div>
                                    <span class="text-sm font-medium text-steel-400">Customer Info</span>
                                </button>
                                <div class="flex-1 h-0.5 bg-white/10 mx-4"></div>
                                <button type="button" id="step3Btn"
                                    class="step-btn flex flex-col items-center gap-2 group opacity-50" data-step="3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-steel-700 flex items-center justify-center text-white font-bold">
                                        3</div>
                                    <span class="text-sm font-medium text-steel-400">Review</span>
                                </button>
                            </div>
                        </div>

                        <form action="{{ route('frontend.quotation.store') }}" method="POST" id="quotationForm">
                            @csrf
                            <input type="hidden" name="deposit" id="depositInput" value="0">

                            {{-- STEP 1: Products Selection --}}
                            <div id="step1" class="step-content p-6 sm:p-8">
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-white mb-2">Select Products</h3>
                                    <p class="text-sm text-steel-400">Choose the products you want to rent or purchase</p>
                                </div>

                                {{-- Custom Radio Group for Quotation Type --}}
                                <div class="mb-8">
                                    <label class="form-label text-sm font-medium text-steel-300 mb-3 block">Quotation Type
                                        <span class="text-orange-400">*</span></label>

                                    <div class="radio-group" style="width: 260px; height: 42px;">
                                        <div class="slider"></div>
                                        <div class="radio-option">
                                            <input type="radio" name="type" id="typeRent" value="rent" checked />
                                            <label for="typeRent" class="radio-label">Rent</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" name="type" id="typePurchase" value="purchase" />
                                            <label for="typePurchase" class="radio-label">Purchase</label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Rent Details (shown only for rent) --}}
                                <div id="rentDetails" class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label class="form-label text-sm font-medium text-steel-300 mb-2 block">Rental Date
                                            <span class="text-orange-400">*</span></label>
                                        <input type="date" name="rent_date" id="rentDate"
                                            class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                    </div>
                                    <div>
                                        <label class="form-label text-sm font-medium text-steel-300 mb-2 block">Duration
                                            (Days) <span class="text-orange-400">*</span></label>
                                        <input type="number" name="rent_duration" id="rentDuration" value="7"
                                            min="1"
                                            class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                    </div>
                                </div>

                                {{-- Products Container --}}
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-semibold text-white">Products</h4>
                                        <button type="button" id="addProductBtn"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-orange-500/20 border border-orange-500/30 text-orange-400 text-xs font-semibold rounded-lg hover:bg-orange-500/30 transition-colors">
                                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Product
                                        </button>
                                    </div>
                                    <div id="productsContainer" class="space-y-3"></div>
                                    <div id="emptyProductsMsg"
                                        class="text-center py-8 border border-dashed border-white/10 rounded-xl">
                                        <i data-lucide="package" class="w-8 h-8 text-steel-600 mx-auto mb-2"></i>
                                        <p class="text-sm text-steel-500">No products added yet</p>
                                        <p class="text-xs text-steel-600 mt-1">Click "Add Product" to add items</p>
                                    </div>
                                </div>

                                {{-- Navigation --}}
                                <div class="flex justify-end pt-4 border-t border-white/10">
                                    <button type="button"
                                        class="next-step px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                        Next: Customer Info <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- STEP 2: Customer Information --}}
                            <div id="step2" class="step-content hidden p-6 sm:p-8">
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-white mb-2">Customer Information</h3>
                                    <p class="text-sm text-steel-400">Tell us who you are and where to deliver</p>
                                </div>

                                <div class="space-y-5">
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div>
                                            <label
                                                class="form-label text-sm font-medium text-steel-300 mb-2 block">Customer
                                                Name <span class="text-orange-400">*</span></label>
                                            <input type="text" name="customer_name" id="customerName"
                                                class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50"
                                                placeholder="Enter your name"
                                                value="@auth {{ Auth::guard('customer')->user()->name }} @endauth"
                                                @auth readonly @endauth>
                                            <input type="hidden" name="customer_id" id="customerId"
                                                value="@auth {{ Auth::guard('customer')->user()->id }} @endauth">
                                        </div>
                                        <div>
                                            <label class="form-label text-sm font-medium text-steel-300 mb-2 block">Email
                                                Address</label>
                                            <input type="email" name="email" id="customerEmail"
                                                class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50"
                                                placeholder="your@email.com"
                                                value="@auth {{ Auth::guard('customer')->user()->email }} @endauth">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="form-label text-sm font-medium text-steel-300 mb-2 block">Phone
                                            Number</label>
                                        <input type="tel" name="phone" id="customerPhone"
                                            class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50"
                                            placeholder="+959xxxxxxxx"
                                            value="@auth {{ Auth::guard('customer')->user()->phone_number }} @endauth">
                                    </div>

                                    <div>
                                        <label
                                            class="form-label text-sm font-medium text-steel-300 mb-2 block">Delivery/Transport
                                            Address</label>
                                        <textarea name="transport_address" id="transportAddress" rows="3"
                                            class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50 resize-none"
                                            placeholder="Enter your complete address for delivery..."></textarea>
                                    </div>

                                    {{-- - Modern Toggle Switch --}}
                                    <div class="mb-6">
                                        <label class="modern-toggle">
                                            <input type="checkbox" name="transport_required" value="1"
                                                id="transportCheckbox" class="modern-toggle-input">
                                            <span class="modern-toggle-slider"></span>
                                            <span class="modern-toggle-label">I need transport/delivery service</span>
                                        </label>
                                    </div>

                                    <div>
                                        <label class="form-label text-sm font-medium text-steel-300 mb-2 block">Additional
                                            Notes</label>
                                        <textarea name="notes" id="quoteNote" rows="3"
                                            class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50 resize-none"
                                            placeholder="Special requirements, delivery instructions, etc..."></textarea>
                                    </div>
                                </div>

                                {{-- Navigation --}}
                                <div class="flex justify-between pt-6 border-t border-white/10 mt-6">
                                    <button type="button"
                                        class="prev-step px-6 py-2 bg-steel-700 text-white font-semibold rounded-lg hover:bg-steel-600 transition-colors">
                                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i> Back
                                    </button>
                                    <button type="button"
                                        class="next-step px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                        Review Order <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- STEP 3: Review & Submit --}}
                            <div id="step3" class="step-content hidden p-6 sm:p-8">
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-white mb-2">Review Your Quotation</h3>
                                    <p class="text-sm text-steel-400">Please review all details before submitting</p>
                                </div>

                                {{-- Products Review --}}
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-orange-400 mb-3">Products</h4>
                                    <div class="bg-navy-800/50 rounded-lg overflow-hidden border border-white/10">
                                        <table class="w-full text-sm">
                                            <thead class="bg-navy-900/50 border-b border-white/10">
                                                <tr>
                                                    <th class="text-left px-4 py-3 text-steel-400 font-medium">Product</th>
                                                    <th class="text-center px-4 py-3 text-steel-400 font-medium">Qty</th>
                                                    <th class="text-right px-4 py-3 text-steel-400 font-medium">Unit Price
                                                        (Ks)</th>
                                                    <th class="text-right px-4 py-3 text-steel-400 font-medium">Total (Ks)
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="reviewProductsList"></tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Order Summary --}}
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-orange-400 mb-3">Order Summary</h4>
                                    <div class="bg-navy-800/50 rounded-xl p-5 border border-white/10">
                                        <div class="space-y-3">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-steel-400">Sub Total</span>
                                                <span id="reviewSubTotal" class="text-white font-semibold">Ks 0</span>
                                            </div>
                                            <div id="reviewDepositRow" class="flex justify-between text-sm">
                                                <span class="text-steel-400">Deposit (Rent)</span>
                                                <span id="reviewDeposit" class="text-white font-semibold">Ks 0</span>
                                            </div>
                                            <div id="reviewTransportRow" class="flex justify-between text-sm hidden">
                                                <span class="text-steel-400">Transport/Delivery</span>
                                                <span id="reviewTransport" class="text-xs text-gray-400 mt-1">Ks 0.00</span>
                                            </div>
                                            <div id="reviewTypeRow" class="flex justify-between text-sm">
                                                <span class="text-steel-400">Quotation Type</span>
                                                <span id="reviewType"
                                                    class="text-white font-semibold capitalize">Rent</span>
                                            </div>
                                            <div id="reviewDurationRow" class="flex justify-between text-sm">
                                                <span class="text-steel-400">Rental Duration</span>
                                                <span id="reviewDuration" class="text-white font-semibold">-</span>
                                            </div>
                                            <div class="pt-3 border-t border-white/10 flex justify-between">
                                                <div>
                                                    <span class="font-semibold text-white">Total Amount</span>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        Excludes transportation charges
                                                    </p>
                                                </div>
                                                <span id="reviewTotal" class="text-lg font-bold text-orange-400">Ks
                                                    {{ number_format($settings['deposit_amount'], 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Customer Details Review --}}
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-orange-400 mb-3">Customer Details</h4>
                                    <div class="bg-navy-800/50 rounded-xl p-5 border border-white/10 space-y-2 text-sm">
                                        <div class="flex"><span class="text-steel-400 w-32">Name:</span><span
                                                id="reviewCustomerName" class="text-white">-</span></div>
                                        <div class="flex"><span class="text-steel-400 w-32">Email:</span><span
                                                id="reviewCustomerEmail" class="text-white">-</span></div>
                                        <div class="flex"><span class="text-steel-400 w-32">Phone:</span><span
                                                id="reviewCustomerPhone" class="text-white">-</span></div>
                                        <div class="flex"><span class="text-steel-400 w-32">Address:</span><span
                                                id="reviewCustomerAddress" class="text-white">-</span></div>
                                        <div class="flex"><span class="text-steel-400 w-32">Notes:</span><span
                                                id="reviewCustomerNotes" class="text-white">-</span></div>
                                    </div>
                                </div>

                                {{-- Hidden Fields for Items --}}
                                <div id="hiddenItemsContainer"></div>

                                {{-- Navigation --}}
                                <div class="flex justify-between pt-6 border-t border-white/10 mt-6">
                                    <button type="button"
                                        class="prev-step px-2 py-2 bg-steel-700 text-white font-semibold rounded-lg hover:bg-steel-600 transition-colors">
                                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-1"></i> Back
                                    </button>
                                    <button type="submit" id="submitQuotationBtn"
                                        class="px-4 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/25">
                                        <i data-lucide="check" class="w-4 h-4 inline mr-1"></i> Send Quotation
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Radio Group Styles */
        .radio-group {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            padding: 4px;
            border-radius: 50px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            animation: slide-in 0.6s ease-out;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Indicador deslizante de fondo */
        .radio-group .slider {
            position: absolute;
            top: 4px;
            bottom: 4px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-radius: 50px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 3px 12px rgba(249, 115, 22, 0.3), 0 1px 4px rgba(0, 0, 0, 0.1);
            z-index: 0;
        }

        .radio-group .radio-option {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .radio-group .radio-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .radio-group .radio-label {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 0 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            border-radius: 50px;
            position: relative;
            white-space: nowrap;
        }

        .radio-group .radio-option input[type="radio"]:checked+.radio-label {
            color: white;
            text-shadow: 0 0 8px rgba(249, 115, 22, 0.5);
        }

        .radio-group .radio-label:hover {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Efecto de brillo al pasar el mouse */
        .radio-group .radio-label::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .radio-group .radio-label:hover::before {
            opacity: 1;
        }

        /* Animación de entrada escalonada */
        .radio-group .radio-option {
            animation: fade-in 0.5s ease-out backwards;
        }

        .radio-group .radio-option:nth-child(1) {
            animation-delay: 0.1s;
        }

        .radio-group .radio-option:nth-child(2) {
            animation-delay: 0.2s;
        }

        .radio-group .radio-option:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Ajuste automático del slider */
        .radio-group:has(#typeRent:checked) .slider {
            left: 4px;
            width: calc(50% - 4px);
        }

        .radio-group:has(#typePurchase:checked) .slider {
            left: calc(50% + 0px);
            width: calc(50% - 4px);
        }

        /* Efecto de pulso en el slider */
        @keyframes pulse {
            0% {
                box-shadow: 0 3px 12px rgba(249, 115, 22, 0.3), 0 1px 4px rgba(0, 0, 0, 0.1);
            }

            50% {
                box-shadow: 0 5px 20px rgba(249, 115, 22, 0.5), 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            100% {
                box-shadow: 0 3px 12px rgba(249, 115, 22, 0.3), 0 1px 4px rgba(0, 0, 0, 0.1);
            }
        }

        .radio-group .radio-option input[type="radio"]:checked~.slider {
            animation: pulse 0.6s ease-out;
        }

        /* Brillo decorativo en el contenedor */
        .radio-group::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            border-radius: 50px;
            z-index: -1;
            animation: shine 3s linear infinite;
            opacity: 0;
            pointer-events: none;
        }

        .radio-group:hover::before {
            opacity: 1;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) rotate(45deg);
            }
        }

        /* Modern Toggle Switch */
        .modern-toggle {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            user-select: none;
        }

        .modern-toggle-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .modern-toggle-slider {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
            background: rgba(51, 65, 85, 0.8);
            border-radius: 34px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
        }

        .modern-toggle-slider::before {
            content: '';
            position: absolute;
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 2px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .modern-toggle-input:checked+.modern-toggle-slider {
            background: linear-gradient(135deg, #f97316, #ea580c);
            border-color: #f97316;
            box-shadow: 0 0 12px rgba(249, 115, 22, 0.3);
        }

        .modern-toggle-input:checked+.modern-toggle-slider::before {
            transform: translateX(24px);
        }

        .modern-toggle-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #94a3b8;
            transition: color 0.3s ease;
        }

        .modern-toggle:hover .modern-toggle-slider {
            border-color: rgba(249, 115, 22, 0.5);
            transform: scale(1.02);
        }

        .modern-toggle:hover .modern-toggle-label {
            color: #e2e8f0;
        }

        .modern-toggle-input:focus+.modern-toggle-slider {
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script>
        window.quotationConfig = {
            depositPerProduct: @json($settings['deposit_amount']),
        };
    </script>
    <script src="{{ asset('js/quotation-multi-step.js') }}"></script>
@endpush
