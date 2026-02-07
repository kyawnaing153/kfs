@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Payment Receipt" :crumbs="[
        ['label' => 'Rents', 'url' => route('rents.index')],
        ['label' => 'Rent #' . $rent->rent_code, 'url' => route('rents.show', $rent->id)],
        ['label' => 'Payment Receipt'],
    ]" />

    <div class="max-w-4xl mx-auto">
        <!-- Print Container -->
        <div id="printable-content">
            <x-common.component-card title="Payment Receipt" class="print:shadow-none print:border-0">
                <!-- Receipt Header -->
                <div class="flex justify-between items-start mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payment Receipt</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Receipt #{{ $payment->id }} • Issued on {{ date('F d, Y', strtotime($payment->created_at)) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            Ks {{ number_format($payment->amount, 1) }}
                        </div>
                        <div class="mt-1">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $payment->payment_method_badge }}">
                                {{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Rent Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rent Information</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rent Code</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $rent->rent_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $rent->customer->name }}</p>
                                @if($rent->customer->phone_number)
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rent->customer->phone_number }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rent Status</p>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($rent->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($rent->status === 'ongoing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($rent->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                    {{ ucfirst($rent->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Payment Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Details</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ date('F d, Y', strtotime($payment->payment_date)) }}</p>
                            </div>
                            @if($payment->payment_for)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment For</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $payment->payment_for }}</p>
                            </div>
                            @endif
                            @if($payment->period_start && $payment->period_end)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Period Covered</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ date('M d, Y', strtotime($payment->period_start)) }} - {{ date('M d, Y', strtotime($payment->period_end)) }}
                                </p>
                            </div>
                            @endif
                            @if($payment->note)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Notes</p>
                                <p class="font-medium text-gray-900 dark:text-white whitespace-pre-line">{{ $payment->note }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Summary</h3>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Rent Amount:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    Ks {{ number_format($rent->total, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Previously Paid:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    Ks {{ number_format($rent->total_paid - $payment->amount, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-3">
                                <span class="text-gray-600 dark:text-gray-400">This Payment:</span>
                                <span class="font-bold text-green-600 dark:text-green-400 text-lg">
                                    Ks {{ number_format($payment->amount, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Paid Now:</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">
                                    Ks {{ number_format($rent->total_paid, 1) }}
                                </span>
                            </div>
                            <div class="flex justify-between border-t border-gray-300 dark:border-gray-600 pt-3">
                                <span class="text-gray-800 dark:text-gray-300 font-semibold">Remaining Due:</span>
                                <span class="font-bold text-lg {{ $rent->total_due > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Ks {{ number_format($rent->total_due, 1) }}
                                </span>
                            </div>
                        </div>
                        
                        @if($rent->total_due == 0)
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:border-green-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-800 dark:text-green-300 font-medium">Rent is fully paid!</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>Thank you for your payment!</p>
                    <p class="mt-1">This is an official receipt. Please keep it for your records.</p>
                    <p class="mt-2">Generated on {{ date('F d, Y \a\t h:i A') }}</p>
                </div>
            </x-common.component-card>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-center gap-4 print:hidden">
            <a href="{{ route('rents.show', $rent->id) }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Back to Rent
            </a>
            <button onclick="printReceipt()"
                class="rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-700">
                Print Receipt
            </button>
            @if($rent->total_due > 0)
            <a href="{{ route('rents.payments.create', $rent->id) }}"
                class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                Record Another Payment
            </a>
            @endif
        </div>
    </div>

    <script>
        function printReceipt() {
            const printContent = document.getElementById('printable-content').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-content, #printable-content * {
                visibility: visible;
            }
            #printable-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none;
            }
            .print\:shadow-none {
                box-shadow: none;
            }
            .print\:border-0 {
                border: none;
            }
        }
    </style>
@endsection