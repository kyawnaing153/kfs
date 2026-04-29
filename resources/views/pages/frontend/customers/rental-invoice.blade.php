@extends('layouts.frontend.app')

@section('title', 'Rental Invoice #' . ($rent->rent_code ?? 'N/A') . ' - KFS Scaffolding')

@section('content')
<style>
    @media print {
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .invoice-container {
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .print\:hidden {
            display: none !important;
        }

        /* Hide navigation elements */
        nav,
        aside,
        header,
        footer,
        .navbar,
        .sidebar,
        .main-header,
        .sticky-header,
        [role="navigation"],
        .fixed,
        .sticky,
        .dashboard-header,
        .step-btn,
        .tab-btn {
            display: none !important;
        }

        /* Hide layout wrappers */
        .min-h-screen,
        .bg-navy-800,
        .blueprint-grid-dark {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Make invoice take full page */
        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        .py-12 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .mx-auto {
            margin: 0 !important;
            max-width: 100% !important;
        }

        .px-4, .sm\:px-6, .lg\:px-8 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        @page {
            size: A4;
            margin: 2.5mm 3mm 2.5mm 3mm;
        }
    }

    /* Invoice Styles */
    .invoice-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        margin: 20px auto;
        max-width: 1200px;
    }

    @media (max-width: 768px) {
        .invoice-container {
            padding: 1.5rem !important;
        }
        
        .flex.justify-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .text-right {
            text-align: left;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<div class="min-h-screen bg-navy-800 blueprint-grid-dark py-12">
    <div class="max-w-7xl mx-auto px-4 pt-20 lg:pt-24">
        <!-- Back Button (Screen only) -->
        <div class="mb-4 print:hidden">
            <a href="{{ route('frontend.customer.dashboard') }}#rentals" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-navy-700 text-white text-sm font-semibold rounded-lg hover:bg-navy-600 transition-colors border border-white/10">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Dashboard
            </a>
        </div>

        <div class="invoice-container bg-white p-8 lg:p-10">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-orange-500 flex-wrap gap-4">
                <div class="company-info">
                    <h1 class="text-orange-600 text-2xl lg:text-3xl font-bold mb-2">{{ $settings['companyName'] ?? 'Kyaw Family Scaffolding' }}</h1>
                    <p class="text-gray-600 text-sm">{{ $settings['address'] ?? 'Yangon, Myanmar' }}</p>
                    <p class="text-gray-600 text-sm">Phone: {{ $settings['phone'] ?? '09-428111750' }} | Email: {{ $settings['email'] ?? 'info@kfs-scaffolding.com' }}</p>
                </div>
                @php
                    $phones = array_map('trim', explode(',', $rent->customer->phone_number ?? $rent->customer->phone ?? ''));
                @endphp
                <div class="invoice-details text-left lg:text-right">
                    <h2 class="text-orange-600 text-2xl lg:text-3xl font-bold mb-2">RENTAL INVOICE</h2>
                    <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Invoice No:</span> 
                        <span class="font-mono">{{ $rent->rent_code }}</span>
                    </p>
                    <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Print Date:</span> 
                        {{ $rent->current_time ?? now()->format('Y-m-d H:i:s') }}
                    </p>
                    <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Rent Date:</span> 
                        {{ \Carbon\Carbon::parse($rent->rent_date)->format('F d, Y') }}
                    </p>
                    <p class="text-gray-600 text-sm">
                        <span class="font-semibold text-gray-800">Status:</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $rent->status === 'completed' ? 'bg-green-100 text-green-700' : ($rent->status === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                            <span class="w-1.5 h-1.5 rounded-full 
                                {{ $rent->status === 'completed' ? 'bg-green-500' : ($rent->status === 'active' ? 'bg-blue-500' : 'bg-yellow-500') }}"></span>
                            {{ ucfirst($rent->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Client Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bill-to">
                    <div class="text-orange-600 font-bold text-sm uppercase mb-3 border-l-4 border-orange-500 pl-3">
                        Rented To:
                    </div>
                    <div class="text-gray-700 text-sm space-y-1">
                        <p class="font-semibold text-gray-800 text-base">
                            {{ $rent->customer->name ?? 'N/A' }}
                            @if($rent->customer->company_name ?? false)
                                <span class="font-normal text-gray-600">from {{ $rent->customer->company_name }}</span>
                            @endif
                        </p>
                        <p>📞 Phone: {{ $phones[0] ?? $rent->customer->phone ?? 'N/A' }}</p>
                        @if($rent->customer->email ?? false)
                            <p>✉️ Email: {{ $rent->customer->email }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="ship-to">
                    <div class="text-orange-600 font-bold text-sm uppercase mb-3 border-l-4 border-orange-500 pl-3">
                        Delivery To:
                    </div>
                    <div class="text-gray-700 text-sm space-y-1">
                        <p class="font-semibold text-gray-800">Project Site</p>
                        <p>📍 {{ $rent->note ?? $rent->transport_address ?? 'N/A' }}</p>
                        @if($phones[1] ?? false)
                            <p>📞 Phone: {{ $phones[1] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rental Items Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="bg-orange-600 text-white rounded-l-lg text-left p-3 font-semibold text-sm w-[40%]">
                                Item Description
                            </th>
                            <th class="bg-orange-600 text-white text-center p-3 font-semibold text-sm w-[15%]">
                                Quantity
                            </th>
                            <th class="bg-orange-600 text-white text-center p-3 font-semibold text-sm w-[20%]">
                                Daily Rate (Ks)
                            </th>
                            <th class="bg-orange-600 text-white rounded-r-lg text-right p-3 font-semibold text-sm w-[25%]">
                                Daily Total (Ks)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rent->items as $item)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="p-3 text-sm">
                                    <p class="font-semibold text-gray-800">
                                        {{ $item->productVariant->product->product_name ?? 'N/A' }}
                                    </p>
                                    @if($item->productVariant->size ?? false)
                                        <p class="text-gray-500 text-xs mt-1">Size: {{ $item->productVariant->size }}</p>
                                    @endif
                                    @if($item->productVariant->brand ?? false)
                                        <p class="text-gray-500 text-xs">Brand: {{ $item->productVariant->brand }}</p>
                                    @endif
                                </td>
                                <td class="p-3 text-sm text-center">
                                    {{ $item->rent_qty }} {{ $item->unit }}
                                </td>
                                <td class="p-3 text-sm text-center">
                                    {{ number_format($item->unit_price, 0) }}
                                </td>
                                <td class="p-3 text-sm text-right font-semibold">
                                    {{ number_format($item->daily_total ?? $item->rent_qty * $item->unit_price, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-gray-500">
                                    <i data-lucide="package" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    No rental items found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Signatures & Terms -->
                <div>
                    <!-- Signatures -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-gray-800 font-semibold text-sm mb-8">Customer's Signature</p>
                            <div class="border-t-2 border-gray-300 pt-2">
                                <p class="text-gray-500 text-xs">_________________</p>
                                <p class="text-gray-400 text-xs mt-1">Date: _____________</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-800 font-semibold text-sm mb-8">Authorized Signature</p>
                            <div class="border-t-2 border-gray-300 pt-2">
                                <p class="text-gray-500 text-xs">_________________</p>
                                <p class="text-gray-400 text-xs mt-1">Date: _____________</p>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-orange-600 font-bold mb-3 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            Rental Terms & Conditions
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex gap-2">• <span><strong>Damage/Loss:</strong> Customer is responsible for any damage or loss of rented items</span></li>
                            <li class="flex gap-2">• <span><strong>Late Return:</strong> Additional charges apply for late returns at 150% of daily rate</span></li>
                            <li class="flex gap-2">• <span><strong>Inspection:</strong> All items must be inspected upon return</span></li>
                        </ul>
                        
                        @if($rent->payment_type == 'kpay')
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm"><span class="font-semibold text-gray-800">K-pay Number:</span> 09428111750</p>
                            </div>
                        @endif

                        <!-- Payment History -->
                        @if($rent->payments && $rent->payments->count() > 0)
                            <div class="mt-4 pt-3 border-t border-gray-200">
                                <h4 class="font-semibold text-gray-800 mb-2">Payment History:</h4>
                                <div class="space-y-1">
                                    @foreach($rent->payments as $payment)
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}:
                                            <span class="font-semibold">{{ number_format($payment->amount, 0) }} Ks</span>
                                            via {{ ucfirst($payment->payment_method) }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Summary Section -->
                <div>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        <table class="w-full">
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="p-3 text-sm text-gray-600">Daily Rental Subtotal</td>
                                    <td class="p-3 text-sm text-right font-semibold">
                                        {{ number_format($rent->daily_subtotal ?? $rent->sub_total, 0) }} Ks
                                    </td>
                                </tr>
                                
                                @if(($rent->deposit ?? 0) > 0)
                                <tr class="border-b border-gray-200">
                                    <td class="p-3 text-sm text-gray-600">Security Deposit</td>
                                    <td class="p-3 text-sm text-right text-orange-600 font-semibold">
                                        + {{ number_format($rent->deposit, 0) }} Ks
                                    </td>
                                </tr>
                                @endif

                                @if(($rent->transport ?? 0) > 0)
                                <tr class="border-b border-gray-200">
                                    <td class="p-3 text-sm text-gray-600">Transport Fee</td>
                                    <td class="p-3 text-sm text-right text-orange-600 font-semibold">
                                        + {{ number_format($rent->transport, 0) }} Ks
                                    </td>
                                </tr>
                                @endif

                                @if(($rent->discount ?? 0) > 0)
                                <tr class="border-b border-gray-200">
                                    <td class="p-3 text-sm text-gray-600">Discount</td>
                                    <td class="p-3 text-sm text-right text-green-600 font-semibold">
                                        - {{ number_format($rent->discount, 0) }} Ks
                                    </td>
                                </tr>
                                @endif

                                <tr class="border-b border-gray-200">
                                    <td class="p-3 text-sm text-gray-600">Tax</td>
                                    <td class="p-3 text-sm text-right">
                                        {{ number_format($rent->tax_amount ?? 0, 0) }} Ks
                                        <span class="text-gray-400 text-xs">({{ $rent->tax_percent ?? 5 }}%)</span>
                                    </td>
                                </tr>

                                <tr class="bg-orange-50">
                                    <td class="p-3 font-bold text-gray-800">TOTAL AMOUNT</td>
                                    <td class="p-3 text-right font-bold text-orange-600 text-lg">
                                        {{ number_format($rent->total, 0) }} Ks
                                    </td>
                                </tr>

                                @if(($rent->total_paid ?? 0) > 0)
                                <tr class="border-t border-gray-200">
                                    <td class="p-3 text-sm font-semibold text-gray-700">Total Paid</td>
                                    <td class="p-3 text-sm text-right font-semibold text-green-600">
                                        {{ number_format($rent->total_paid, 0) }} Ks
                                    </td>
                                </tr>
                                <tr class="{{ ($rent->total_due ?? 0) > 0 ? 'bg-red-50' : 'bg-green-50' }}">
                                    <td class="p-3 font-bold {{ ($rent->total_due ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ ($rent->total_due ?? 0) > 0 ? 'BALANCE DUE' : 'PAID IN FULL' }}
                                    </td>
                                    <td class="p-3 text-right font-bold {{ ($rent->total_due ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        @if(($rent->total_due ?? 0) > 0)
                                            {{ number_format($rent->total_due, 0) }} Ks
                                        @else
                                            {{ number_format(abs($rent->total_due), 0) }} Ks
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-8 mt-8 border-t border-gray-200">
                <h3 class="text-orange-600 font-bold mb-2">Thank you for choosing Kyaw Family Scaffolding!</h3>
                <p class="text-gray-500 text-sm">For any inquiries regarding this rental invoice, please contact our customer support.</p>
                <div class="flex justify-center gap-6 mt-3 text-xs text-gray-400">
                    <span>📞 {{ $settings['phone'] ?? '09-428111750' }}</span>
                    <span>✉️ {{ $settings['email'] ?? 'info@kfs-scaffolding.com' }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6 print:hidden">
                <button onclick="window.print()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-all shadow-md">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    Print / Save as PDF
                </button>
                <button onclick="downloadInvoice()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-navy-700 text-white text-sm font-semibold rounded-lg hover:bg-navy-600 transition-all border border-white/10">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
    
    function downloadInvoice() {
        window.print();
    }
</script>
@endpush

@endsection