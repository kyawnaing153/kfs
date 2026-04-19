@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Purchase Details" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Purchase Header Card -->
        <x-common.component-card title="Purchase Information">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $purchase->purchase_code }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Created by: {{ $purchase->creator->name ?? 'N/A' }} |
                        Date: {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                    </p>
                </div>
                <div class="flex gap-2">
                    @if ($purchase->status == App\Models\Backend\Purchase::STATUS_PENDING)
                        <a href="{{ route('purchases.edit', $purchase->id) }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this purchase?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">
                                Delete
                            </button>
                        </form>
                    @endif

                    @if ($purchase->status == App\Models\Backend\Purchase::STATUS_PENDING)
                        <!-- Delivery Status -->
                        <form method="POST" action="{{ route('purchases.mark-as-delivered', $purchase->id) }}"
                            class="inline">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                class="flex w-full items-center gap-2 px-4 py-2 text-sm bg-gray-600 text-white hover:bg-gray-500 dark:text-gray-300 dark:hover:bg-gray-700 rounded-lg transition"
                                role="menuitem">
                                <i class="fas fa-window-close text-red-500"></i>
                                {{ $purchase->status == 1 ? 'Delivered' : 'Delivered' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Supplier Info -->
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Supplier</p>
                    <p class="font-medium">{{ $purchase->supplier->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">
                        <a href="tel:+{{ $purchase->supplier->phone_number }}" class="text-blue-600 hover:text-blue-800">
                            {{ $purchase->supplier->phone_number ?? 'No phone' }}
                        </a>
                    </p>
                    @if ($purchase->supplier->email)
                        <p class="text-sm text-gray-600">{{ $purchase->supplier->email }}</p>
                    @endif
                </div>

                <!-- Status Badges -->
                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span
                        class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $purchase->status_badge_class }}">
                        {{ $purchase->status_text }}
                    </span>
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Status</p>
                    <span
                        class="inline-block px-3 py-1 mb-1 rounded-full text-xs font-semibold {{ $purchase->payment_badge_class }}">
                        {{ $purchase->payment_status_text }}
                    </span>
                    @if ($purchase->payment_status == App\Models\Backend\Purchase::PAYMENT_UNPAID)
                        <form method="POST" action="{{ route('purchases.update-payment', $purchase->id) }}"
                            class="inline pt-1">
                            @csrf
                            @method('POST')
                            <button type="submit"
                                class="flex w-auto items-center gap-2 px-4 py-2 text-sm bg-gray-600 text-white hover:bg-gray-500 dark:text-gray-300 dark:hover:bg-gray-700 transition rounded-lg"
                                role="menuitem">
                                <i class="fas fa-window-close text-red-500"></i>
                                {{ $purchase->payment_status == 1 ? 'Mark as Unpaid' : 'Mark as Paid' }}
                            </button>
                        </form>
                    @endif
                </div>

                <div class="border rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                    <p class="text-xl font-bold text-green-600">Ks {{ number_format($purchase->total_amount, 0) }}</p>
                </div>
            </div>

            @if ($purchase->notes)
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Notes</p>
                    <p class="text-sm">{{ $purchase->notes }}</p>
                </div>
            @endif
        </x-common.component-card>

        <!-- Purchase Items Card -->
        <x-common.component-card title="Purchase Items">
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="min-w-[120px] px-4 py-3 text-left">Product</th>
                            <th class="px-4 py-3 text-right">Quantity</th>
                            <th class="min-w-[100px] px-4 py-3 text-right">Unit Price</th>
                            <th class="min-w-[100px] px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($purchase->items as $index => $item)
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">{{ $item->product->product_name ?? 'N/A' }}
                                    @if ($item->productVariant)
                                        {{ $item->productVariant->size ?? '' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->received_qty) }}
                                    {{ $item->productVariant->unit ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-right">Ks {{ number_format($item->unit_price, 0) }}</td>
                                <td class="px-4 py-3 text-right font-medium">Ks {{ number_format($item->total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right">Sub Total:</td>
                            <td class="px-4 py-3 text-right">Ks {{ number_format($purchase->sub_total, 0) }}</td>
                        </tr>
                        @if ($purchase->transport > 0)
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right">Transport Cost:</td>
                                <td class="px-4 py-3 text-right">Ks {{ number_format($purchase->transport, 0) }}</td>
                            </tr>
                        @endif
                        @if ($purchase->discount > 0)
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right">Discount:</td>
                                <td class="px-4 py-3 text-right">Ks -{{ number_format($purchase->discount, 0) }}</td>
                            </tr>
                        @endif
                        @if ($purchase->tax > 0)
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right">Tax:</td>
                                <td class="px-4 py-3 text-right">Ks {{ number_format($purchase->tax, 0) }}</td>
                            </tr>
                        @endif
                        <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                            <td colspan="4" class="px-4 py-3 text-right text-lg">Grand Total:</td>
                            <td class="px-4 py-3 text-right text-lg font-bold text-green-600">
                                Ks {{ number_format($purchase->total_amount, 0) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-common.component-card>
    </div>
@endsection
