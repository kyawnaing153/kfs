@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Return Details - ' . $rent->rent_code" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Return Details Card -->
        <x-common.component-card title="Return Details">
            <!-- Return Header -->
            <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            Return #{{ $return->id }}
                        </h2>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <!-- Status Badge -->
                            @php
                                $statusColors = [
                                    'partial' =>
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$return->status] ?? $statusColors['partial'] }}">
                                {{ ucfirst($return->status) }} Return
                            </span>

                            <!-- Rent Badge -->
                            <span
                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Rent: {{ $rent->rent_code }}
                            </span>

                            <!-- Late Return Badge -->
                            @if ($return->is_late_return)
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Late Return
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Return Date: {{ $return->return_date }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Processed By: {{ $return->processedBy->name ?? 'System' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Left Column: Rent Information -->
                <div class="space-y-6">
                    <!-- Customer Information -->
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="mb-4 font-bold text-gray-900 dark:text-white">Customer Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Customer Name:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $rent->customer->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Phone:</span>
                                <a href="tel:{{ $rent->customer->phone_number }}"
                                    class="font-medium text-gray-900 dark:text-white hover:underline">
                                    {{ $rent->customer->phone_number }}
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Email:</span>
                                <a href="mailto:{{ $rent->customer->email }}"
                                    class="font-medium text-gray-900 dark:text-white hover:underline">
                                    {{ $rent->customer->email }}
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Address:</span>
                                <span
                                    class="text-right font-medium text-gray-900 dark:text-white">{{ $rent->customer->address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    @php
                        $dailyRate = $rent->sub_total * $return->total_days;
                        $damageTotal = 0;
                    @endphp
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="mb-4 font-bold text-gray-900 dark:text-white">Financial Summary</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Daily Rental Subtotal:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ number_format($rent->sub_total, 0) }}
                                    * {{ $return->total_days }} = {{ number_format($dailyRate, 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Security Deposit:</span>
                                <span
                                    class="font-medium text-green-600 dark:text-green-400">{{ number_format($rent->deposit, 0) }}</span>
                            </div>

                            @if ($rent->transport || $return->transport)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Transport Fee:</span>
                                    <span class="font-medium text-yellow-600 dark:text-yellow-400">
                                        @if ($rent->transport > 0)
                                            {{ number_format($rent->transport, 0) }}(rent)
                                            @endif + @if ($return->transport > 0)
                                                {{ number_format($return->transport, 0) }}(return)
                                            @endif
                                    </span>
                                </div>
                            @endif

                            @foreach ($return->items->where('damage_fee', '>', 0) as $item)
                                @php
                                    $damageTotal += $item->damage_fee;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        Total Damage Fee:
                                    </span>
                                    <span class="font-medium text-red-600 dark:text-red-400">
                                        {{ number_format($item->damage_fee, 0) }}
                                    </span>
                                </div>
                            @endforeach

                            @forelse($return->rent->payments as $key => $payment)
                                <div class="flex items-center justify-between text-sm ml-8">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        Payment [{{ ++$key }}]
                                    </span>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        Ks {{ number_format($payment->amount, 0) }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-sm text-gray-400 italic">
                                    No payments recorded
                                </div>
                            @endforelse

                            @if ($return->refund_amount > 0)
                                <div
                                    class="flex items-center justify-between border-t border-gray-200 pt-3 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Refund Amount:</span>
                                    <span
                                        class="font-medium text-blue-600 dark:text-blue-400">{{ number_format($return->refund_amount, 0) }}</span>
                                </div>
                            @endif

                            @if ($return->collect_amount > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Collect Fee:</span>
                                    <span
                                        class="font-medium text-red-600 dark:text-red-400">{{ number_format($return->collect_amount, 0) }}</span>
                                </div>
                            @endif

                            @if ($return->damage_fee > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Damage Fee:</span>
                                    <span
                                        class="font-medium text-red-600 dark:text-red-400">{{ number_format($return->damage_fee, 0) }}</span>
                                </div>
                            @endif

                            @php
                                $finalBalance =
                                    $dailyRate +
                                    ($damageTotal ?? 0) +
                                    ($return->transport ?? 0) -
                                    (($rent->deposit ?? 0) - ($return->refund_amount ?? 0)) -
                                    ($totalPaymentByRentId ?? 0);
                            @endphp
                            <div
                                class="flex items-center justify-between border-t border-gray-200 pt-3 dark:border-gray-700">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Final Balance:</span>
                                <span
                                    class="text-lg font-bold {{ $finalBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ number_format($finalBalance, 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Return Details -->
                <div class="space-y-6">
                    <!-- Return Summary -->
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="mb-4 font-bold text-gray-900 dark:text-white">Return Summary</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Rent Start Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $rent->rent_date }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Actual Return Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->return_date }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Rental Days:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->total_days }}
                                    days</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Return Transport</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->transport }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information & Actions -->
                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Additional Notes -->
                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="mb-4 font-bold text-gray-900 dark:text-white">Additional Notes</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Return Condition:</span>
                                    <p class="mt-1 font-medium text-gray-900 dark:text-white">
                                        {{ $return->note ?? 'No specific notes' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Remarks:</span>
                                    <p class="mt-1 font-medium text-gray-900 dark:text-white">
                                        {{ $return->remarks ?? 'No remarks' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                            <h3 class="mb-4 font-bold text-gray-900 dark:text-white">Actions</h3>
                            <div class="flex flex-wrap gap-3">
                                <!-- Print Receipt -->
                                <a href="{{ route('rents.returns.print', [$rent->id, $return->id, 'autoprint' => true]) }}" target="_blank"
                                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print Receipt
                                </a>

                                <!-- Optional: Direct print version -->
                                {{-- <a href="{{ route('rents.returns.print', [$rent->id, $return->id, 'autoprint' => true]) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-green-600 bg-white px-4 py-2 text-sm font-medium text-green-600 hover:bg-green-50"
                                    target="_blank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print & Auto-Print
                                </a> --}}

                                <!-- Edit Return -->
                                @if ($return->status !== 'completed')
                                    <a href="#" {{-- route('backend.returns.edit', $return->id) --}}
                                        class="inline-flex items-center rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 dark:bg-yellow-500 dark:hover:bg-yellow-600">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit Return
                                    </a>
                                @endif

                                <!-- View Rent -->
                                <a href="{{ route('rents.show', $rent->id) }}"
                                    class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Rent
                                </a>
                                {{-- {{ route('backend.returns.complete', $return->id) }} --}}
                                <!-- Complete Return -->
                                @if ($return->status !== 'completed')
                                    <form action="#" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600"
                                            onclick="return confirm('Are you sure you want to mark this return as completed?')">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Complete Return
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </x-common.component-card>

        <!-- Returned Items/Vehicles Table -->
        @if (isset($return->items) && $return->items->count() > 0)
            <x-common.component-card title="Returned Items">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Item</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">Condition</th>
                                <th scope="col" class="px-6 py-3">Notes</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($return->items as $item)
                                <tr
                                    class="border-b bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $item->rentItem->productVariant->product->product_name }}
                                    </td>
                                    <td class="px-6 py-4">{{ $item->qty }} {{ $item->rentItem->productVariant->unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-medium {{ $item->damage_fee > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                            {{ number_format($item->damage_fee, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $item->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>
        @endif

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('rents.index', ['activeTab' => 'rents']) }}"
                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Returns
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // You can add JavaScript functionality here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Print button confirmation
            const printBtn = document.querySelector('a[href*="print"]');
            if (printBtn) {
                printBtn.addEventListener('click', function(e) {
                    // Optional: Add print dialog customization
                    console.log('Opening print preview...');
                });
            }
        });
    </script>
@endpush
