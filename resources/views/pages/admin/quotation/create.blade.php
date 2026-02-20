@extends('layouts.app')

@section('content')
 <x-common.page-breadcrumb pageTitle="Create New Quotation" />
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-red-800 font-medium">Please fix the following errors:</h3>
                    </div>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                        <form method="POST" action="{{ route('quotation.preview') }}" id="quotationForm">
                            @csrf

                            <!-- Company & Client Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Company Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Your Company Email
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="company_email"
                                        value="{{ old('company_email', $formData['company_email']) }}" required
                                        class="w-full px-3 py-2 border {{ $errors->has('company_email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('company_email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Client Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Client Email
                                    </label>
                                    <input type="email" name="client_email"
                                        value="{{ old('client_email', $formData['client_email']) }}"
                                        class="w-full px-3 py-2 border {{ $errors->has('client_email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('client_email')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Client Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Client Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="client_name"
                                        value="{{ old('client_name', $formData['client_name']) }}" required
                                        class="w-full px-3 py-2 border {{ $errors->has('client_name') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('client_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Client Phone
                                    </label>
                                    <input type="text" name="client_phone"
                                        value="{{ old('client_phone', $formData['client_phone']) }}"
                                        class="w-full px-3 py-2 border {{ $errors->has('client_phone') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('client_phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Client Address
                                </label>
                                <textarea name="client_address" rows="2"
                                    class="w-full px-3 py-2 border {{ $errors->has('client_address') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('client_address', $formData['client_address']) }}</textarea>
                                @error('client_address')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Quotation Header -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quotation Title
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="quotation_title"
                                        value="{{ old('quotation_title', $formData['quotation_title']) }}" required
                                        class="w-full px-3 py-2 border {{ $errors->has('quotation_title') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('quotation_title')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Quotation No 
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="text" name="quotation_no"
                                            value="{{ old('quotation_no', $formData['quotation_no']) }}" required
                                            class="flex-1 px-3 py-2 border {{ $errors->has('quotation_no') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        {{-- <button type="button" id="generateNumber"
                                            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                            ↻
                                        </button> --}}
                                    </div>
                                    @error('quotation_no')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Date *
                                    </label>
                                    <input type="date" name="date" value="{{ old('date', $formData['date']) }}"
                                        required
                                        class="w-full px-3 py-2 border {{ $errors->has('date') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('date')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Items Section -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Items</h3>
                                    <button type="button" id="addItemBtn"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        + Add Item
                                    </button>
                                </div>

                                <div id="itemsContainer" class="space-y-4">
                                    @php
                                        $oldItems = old('items', $formData['items']);
                                    @endphp

                                    @foreach ($oldItems as $index => $item)
                                        <div
                                            class="item-row p-4 border {{ $errors->has('items.' . $index . '.name') || $errors->has('items.' . $index . '.quantity') || $errors->has('items.' . $index . '.unit_price') ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-lg">
                                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                                <div class="md:col-span-2">
                                                    <label class="block text-sm text-gray-600 mb-1">Item Name
                                                        <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" name="items[{{ $index }}][name]"
                                                        value="{{ $item['name'] }}" required
                                                        class="w-full px-3 py-2 border {{ $errors->has('items.' . $index . '.name') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 item-name">
                                                    @error('items.' . $index . '.name')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Quantity
                                                        <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        value="{{ $item['quantity'] }}" min="0.01" step="0.01"
                                                        required
                                                        class="w-full px-3 py-2 border {{ $errors->has('items.' . $index . '.quantity') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 quantity">
                                                    @error('items.' . $index . '.quantity')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Unit</label>
                                                    <select name="items[{{ $index }}][unit]"
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        @foreach (['pcs' => 'Pcs', 'set' => 'Set', 'kg' => 'Kg', 'm' => 'M', 'day' => 'Day'] as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ $item['unit'] == $value ? 'selected' : '' }}>
                                                                {{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm text-gray-600 mb-1">Unit Price
                                                        <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" name="items[{{ $index }}][unit_price]"
                                                        value="{{ $item['unit_price'] }}" min="0" step="0.01"
                                                        required
                                                        class="w-full px-3 py-2 border {{ $errors->has('items.' . $index . '.unit_price') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 unit-price">
                                                    @error('items.' . $index . '.unit_price')
                                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mt-2 flex justify-between items-center">
                                                <span class="text-sm text-gray-500">Line Total: $<span
                                                        class="line-total">{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</span></span>
                                                @if ($index > 0)
                                                    <button type="button"
                                                        class="remove-item text-red-600 hover:text-red-800 text-sm">
                                                        Remove
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('items')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Charges -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Secure Deposit
                                    </label>
                                    <input type="number" name="secure_deposit"
                                        value="{{ old('secure_deposit', $formData['secure_deposit']) }}" min="0"
                                        step="0.01"
                                        class="w-full px-3 py-2 border {{ $errors->has('secure_deposit') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('secure_deposit')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Transport Fee
                                    </label>
                                    <input type="number" name="transport_fee"
                                        value="{{ old('transport_fee', $formData['transport_fee']) }}" min="0"
                                        step="0.01"
                                        class="w-full px-3 py-2 border {{ $errors->has('transport_fee') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('transport_fee')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Discount
                                    </label>
                                    <input type="number" name="discount"
                                        value="{{ old('discount', $formData['discount']) }}" min="0"
                                        step="0.01"
                                        class="w-full px-3 py-2 border {{ $errors->has('discount') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('discount')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tax (%)
                                    </label>
                                    <input type="number" name="tax_percentage"
                                        value="{{ old('tax_percentage', $formData['tax_percentage']) }}" min="0"
                                        max="100" step="0.1"
                                        class="w-full px-3 py-2 border {{ $errors->has('tax_percentage') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('tax_percentage')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Terms & Conditions
                                </label>
                                <textarea name="terms" rows="4"
                                    class="w-full px-3 py-2 border {{ $errors->has('terms') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('terms', $formData['terms']) }}</textarea>
                                @error('terms')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <button type="submit" name="action" value="preview"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    👁 Preview Quotation
                                </button>

                                <button type="submit" formaction="{{ route('quotation.download') }}"
                                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    📄 Download PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Live Preview Column -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-lg rounded-lg p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Live Preview</h3>

                        <div id="livePreview" class="border border-gray-200 rounded-lg p-4 bg-gray-50 min-h-[400px]">
                            <!-- Preview will be updated by jQuery -->
                            <div class="text-center text-gray-500 py-20">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Fill in the form to see preview</p>
                            </div>
                        </div>

                        <!-- Email Form -->
                        <div class="mt-6 p-4 border border-gray-200 rounded-lg bg-blue-50">
                            <h4 class="font-semibold text-gray-900 mb-3">📧 Send via Email</h4>
                            <form method="POST" action="{{ route('quotation.email') }}" id="emailForm">
                                @csrf
                                <input type="hidden" name="company_email" value="{{ $formData['company_email'] }}">
                                <input type="hidden" name="client_name" value="{{ $formData['client_name'] }}">
                                <input type="hidden" name="quotation_no" value="{{ $formData['quotation_no'] }}">

                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Recipient Email
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="recipient_email" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Subject
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="subject"
                                            value="Quotation {{ $formData['quotation_no'] }}" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-1">Message</label>
                                        <textarea name="message" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                        Send Quotation
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Define global rentData variable
        window.items = {
            items: {{ count($formData['items']) }},
        };
    </script>
    <script src="{{ asset('Backend/js/quotation/quotation.js') }}" defer></script>
@endpush
