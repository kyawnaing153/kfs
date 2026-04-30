@extends('layouts.frontend.app')

@section('title', 'Quotation #' . ($quotation->quotation_code ?? 'N/A') . ' - KFS Scaffolding')

@section('content')
    @php
        $statusStyles = [
            'submitted' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'expired' => 'bg-gray-100 text-gray-700',
        ];
        $statusDots = [
            'submitted' => 'bg-yellow-500',
            'approved' => 'bg-green-500',
            'rejected' => 'bg-red-500',
            'expired' => 'bg-gray-500',
        ];
        $statusClass = $statusStyles[$quotation->status] ?? 'bg-gray-100 text-gray-700';
        $statusDot = $statusDots[$quotation->status] ?? 'bg-gray-500';
        $phones = array_map('trim', explode(',', $quotation->customer->phone_number ?? ($quotation->customer->phone ?? '')));
        $isRent = $quotation->type === 'rent';
    @endphp

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

            .min-h-screen,
            .bg-navy-800,
            .blueprint-grid-dark {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

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

            .px-4,
            .sm\:px-6,
            .lg\:px-8 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            @page {
                size: A4;
                margin: 2.5mm 3mm 2.5mm 3mm;
            }
        }

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

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 pt-20 lg:pt-24">
            <div class="mb-4 print:hidden">
                <a href="{{ route('frontend.customer.dashboard') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back to Dashboard
                </a>
            </div>

            <div class="invoice-container bg-white p-8 lg:p-10">
                <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-orange-500 flex-wrap gap-4">
                    <div class="company-info">
                        <h1 class="text-orange-600 text-2xl lg:text-3xl font-bold mb-2">
                            {{ $settings['companyName'] ?? 'Kyaw Family Scaffolding' }}
                        </h1>
                        <p class="text-gray-600 text-sm">{{ $settings['address'] ?? 'Yangon, Myanmar' }}</p>
                        <p class="text-gray-600 text-sm">
                            Phone: {{ $settings['phone'] ?? '09-428111750' }} | Email:
                            {{ $settings['email'] ?? 'info@kfs-scaffolding.com' }}
                        </p>
                    </div>

                    <div class="invoice-details text-left lg:text-right">
                        <h2 class="text-orange-600 text-2xl lg:text-3xl font-bold mb-2">QUOTATION</h2>
                        <p class="text-gray-600 text-sm mb-1">
                            <span class="font-semibold text-gray-800">Quotation No:</span>
                            <span class="font-mono">{{ $quotation->quotation_code }}</span>
                        </p>
                        <p class="text-gray-600 text-sm mb-1">
                            <span class="font-semibold text-gray-800">Quotation Date:</span>
                            {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('F d, Y') }}
                        </p>
                        @if ($isRent && $quotation->rent_date)
                            <p class="text-gray-600 text-sm mb-1">
                                <span class="font-semibold text-gray-800">Rent Start Date:</span>
                                {{ \Carbon\Carbon::parse($quotation->rent_date)->format('F d, Y') }}
                            </p>
                        @endif
                        <p class="text-gray-600 text-sm">
                            <span class="font-semibold text-gray-800">Status:</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <div class="text-orange-600 font-bold text-sm uppercase mb-3 border-l-4 border-orange-500 pl-3">
                            Quoted To:
                        </div>
                        <div class="text-gray-700 text-sm space-y-1">
                            <p class="font-semibold text-gray-800 text-base">
                                {{ $quotation->customer->name ?? 'N/A' }}
                                @if ($quotation->customer->company_name ?? false)
                                    <span class="font-normal text-gray-600">from {{ $quotation->customer->company_name }}</span>
                                @endif
                            </p>
                            <p>Phone: {{ $phones[0] ?? ($quotation->customer->phone ?? 'N/A') }}</p>
                            @if ($quotation->customer->email ?? false)
                                <p>Email: {{ $quotation->customer->email }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-orange-600 font-bold text-sm uppercase mb-3 border-l-4 border-orange-500 pl-3">
                            Quotation Details:
                        </div>
                        <div class="text-gray-700 text-sm space-y-1">
                            <p><span class="font-semibold text-gray-800">Type:</span> {{ ucfirst($quotation->type) }}</p>
                            @if ($isRent)
                                <p><span class="font-semibold text-gray-800">Rental Duration:</span>
                                    {{ $quotation->rent_duration ?? 1 }} day{{ ($quotation->rent_duration ?? 1) > 1 ? 's' : '' }}
                                </p>
                            @endif
                            <p>
                                <span class="font-semibold text-gray-800">Transport:</span>
                                {{ $quotation->transport_required ? 'Required' : 'Not Required' }}
                            </p>
                            @if ($quotation->transport_address)
                                <p><span class="font-semibold text-gray-800">Delivery To:</span> {{ $quotation->transport_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

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
                                    {{ $isRent ? 'Daily Rate (Ks)' : 'Unit Price (Ks)' }}
                                </th>
                                <th class="bg-orange-600 text-white rounded-r-lg text-right p-3 font-semibold text-sm w-[25%]">
                                    {{ $isRent ? 'Daily Total (Ks)' : 'Line Total (Ks)' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($quotation->items as $item)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="p-3 text-sm">
                                        <p class="font-semibold text-gray-800">
                                            {{ $item->productVariant->product->product_name ?? 'N/A' }}
                                        </p>
                                        @if ($item->productVariant->size ?? false)
                                            <p class="text-gray-500 text-xs mt-1">Size: {{ $item->productVariant->size }}</p>
                                        @endif
                                        @if ($item->productVariant->brand ?? false)
                                            <p class="text-gray-500 text-xs">Brand: {{ $item->productVariant->brand }}</p>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm text-center">
                                        {{ $item->qty }} {{ $item->unit }}
                                    </td>
                                    <td class="p-3 text-sm text-center">
                                        {{ number_format($item->unit_price, 0) }}
                                    </td>
                                    <td class="p-3 text-sm text-right font-semibold">
                                        {{ number_format($item->total, 0) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500">
                                        <i data-lucide="package" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                        No quotation items found
                                    </td>
                                </tr>
                            @endforelse

                            @if ($quotation->items->count() > 0)
                                <tr class="bg-gray-50">
                                    <td class="p-2 font-semibold text-sm" colspan="3">
                                        {{ $isRent ? 'Daily Quotation Subtotal' : 'Quotation Subtotal' }}
                                    </td>
                                    <td class="p-2 text-sm text-right font-semibold">
                                        {{ number_format($quotation->sub_total, 0) }} Ks
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-orange-600 font-bold mb-3 flex items-center gap-2">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                Quotation Notes & Terms
                            </h3>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex gap-2">- <span>This quotation is subject to final stock availability.</span></li>
                                <li class="flex gap-2">- <span>Transport charges may be confirmed after reviewing the delivery location.</span></li>
                                @if ($isRent)
                                    <li class="flex gap-2">- <span>Rental items must be returned in good condition.</span></li>
                                @endif
                            </ul>

                            @if ($quotation->notes)
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <h4 class="font-semibold text-gray-800 mb-2">Customer Notes:</h4>
                                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $quotation->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-gray-200">
                                        <td class="p-3 text-sm text-gray-600">
                                            {{ $isRent ? 'Daily Rental Subtotal' : 'Subtotal' }}
                                        </td>
                                        <td class="p-3 text-sm text-right font-semibold">
                                            {{ number_format($quotation->sub_total, 0) }} Ks
                                        </td>
                                    </tr>

                                    @if (($quotation->deposit ?? 0) > 0)
                                        <tr class="border-b border-gray-200">
                                            <td class="p-3 text-sm text-gray-600">Security Deposit</td>
                                            <td class="p-3 text-sm text-right text-orange-600 font-semibold">
                                                + {{ number_format($quotation->deposit, 0) }} Ks
                                            </td>
                                        </tr>
                                    @endif

                                    @if (($quotation->transport ?? 0) > 0)
                                        <tr class="border-b border-gray-200">
                                            <td class="p-3 text-sm text-gray-600">Transport Fee</td>
                                            <td class="p-3 text-sm text-right text-orange-600 font-semibold">
                                                + {{ number_format($quotation->transport, 0) }} Ks
                                            </td>
                                        </tr>
                                    @endif

                                    @if (($quotation->discount ?? 0) > 0)
                                        <tr class="border-b border-gray-200">
                                            <td class="p-3 text-sm text-gray-600">Discount</td>
                                            <td class="p-3 text-sm text-right text-green-600 font-semibold">
                                                - {{ number_format($quotation->discount, 0) }} Ks
                                            </td>
                                        </tr>
                                    @endif

                                    <tr class="border-b border-gray-200">
                                        <td class="p-3 text-sm text-gray-600">Tax</td>
                                        <td class="p-3 text-sm text-right">
                                            0 Ks
                                            <span class="text-gray-400 text-xs">(0%)</span>
                                        </td>
                                    </tr>

                                    <tr class="bg-orange-50">
                                        <td class="p-3 font-bold text-gray-800">TOTAL AMOUNT</td>
                                        <td class="p-3 text-right font-bold text-orange-600 text-lg">
                                            {{ number_format($quotation->total, 0) }} Ks
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-center pt-8 mt-8 border-t border-gray-200">
                    <h3 class="text-orange-600 font-bold mb-2">Thank you for choosing Kyaw Family Scaffolding!</h3>
                    <p class="text-gray-500 text-sm">For any inquiries regarding this quotation, please contact our customer support.</p>
                    <div class="flex justify-center gap-6 mt-3 text-xs text-gray-400">
                        <span>Phone: {{ $settings['phone'] ?? '09-428111750' }}</span>
                        <span>Email: {{ $settings['email'] ?? 'info@kfs-scaffolding.com' }}</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 print:hidden">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-all shadow-md">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print / Save as PDF
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
        </script>
    @endpush
@endsection
