@extends('layouts.app')

@section('title', 'System Settings - KFS Rental Management')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between md:flex-row flex-col items-start md:items-center mb-6">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">System Settings</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your system configuration and preferences</p>
            </div>

            <div class="mb-8">
                <form action="{{ route('system.clear') }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Clear system cache?')"
                        class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md shadow-sm transition">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6h18M9 6V4h6v2M6 6l1 14h10l1-14" />
                        </svg>

                        Clear Cache
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex flex-wrap -mb-px" aria-label="Tabs">
                    <button onclick="switchTab('general')" id="general-tab"
                        class="tab-button mr-2 inline-flex items-center py-4 px-6 text-sm font-medium text-center border-b-2 transition-colors active border-blue-500 text-blue-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        General Settings
                    </button>
                    <button onclick="switchTab('company')" id="company-tab"
                        class="tab-button mr-2 inline-flex items-center py-4 px-6 text-sm font-medium text-center border-b-2 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Company Info
                    </button>
                    {{-- <button onclick="switchTab('currency')" id="currency-tab"
                        class="tab-button mr-2 inline-flex items-center py-4 px-6 text-sm font-medium text-center border-b-2 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Currency Settings
                    </button> --}}
                    <button onclick="switchTab('system')" id="system-tab"
                        class="tab-button inline-flex items-center py-4 px-6 text-sm font-medium text-center border-b-2 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        System Settings
                    </button>
                </nav>
            </div>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- General Settings Tab -->
                <div id="general-panel" class="tab-panel p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="company_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Company Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('company_name') border-red-500 @enderror"
                                id="company_name" name="company_name"
                                value="{{ old('company_name', $settings['companyName']) }}" required>
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_tagline"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Company Tagline
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('company_tagline') border-red-500 @enderror"
                                id="company_tagline" name="company_tagline"
                                value="{{ old('company_tagline', $settings['companyTagline']) }}">
                            @error('company_tagline')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email_address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email_address') border-red-500 @enderror"
                                id="email_address" name="email_address"
                                value="{{ old('email_address', $settings['email']) }}" required>
                            @error('email_address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('phone_number') border-red-500 @enderror"
                                id="phone_number" name="phone_number"
                                value="{{ old('phone_number', $settings['phone']) }}" required>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('address') border-red-500 @enderror"
                                id="address" name="address" required>{{ old('address', $settings['address']) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Company Info Tab -->
                <div id="company-panel" class="tab-panel hidden p-6">
                    <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4 mb-6 rounded">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-blue-700 dark:text-blue-300">Company information appears on invoices,
                                quotes, and other documents.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Company Logo
                            </label>
                            <input type="file"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-200"
                                name="logo" accept="image/*">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recommended size: 200x50 pixels</p>

                            @if (isset($settings['logo']))
                                <div class="mt-3">
                                    <img src="{{ $settings['logo'] }}" alt="Logo" class="h-12 object-contain">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Favicon
                            </label>
                            <input type="file"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                name="favicon" accept="image/x-icon,image/png">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Recommended size: 16x16 or 32x32
                                pixels</p>
                            @if (isset($settings['logo']))
                                <div class="mt-3">
                                    <img src="{{ $settings['logo'] }}" alt="Logo" class="h-12 object-contain">
                                </div>
                            @endif
                        </div>

                        <div>
                            <label for="copyright"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Copyright Text
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('copyright') border-red-500 @enderror"
                                id="copyright" name="copyright" value="{{ old('copyright', $settings['copyright']) }}"
                                placeholder="&copy; 2024 KFS Rental Management. All rights reserved.">
                            @error('copyright')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Currency Settings Tab -->
                <div id="currency-panel" class="tab-panel hidden p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="currency_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Currency Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('currency_name') border-red-500 @enderror"
                                id="currency_name" name="currency_name"
                                value="{{ old('currency_name', $settings['currencyName']) }}"
                                placeholder="e.g., US Dollar, Euro, Pound Sterling" required>
                            @error('currency_name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency_symbol"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Currency Symbol <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('currency_symbol') border-red-500 @enderror"
                                id="currency_symbol" name="currency_symbol"
                                value="{{ old('currency_symbol', $settings['currencySymbol']) }}"
                                placeholder="e.g., $, €, £" required>
                            @error('currency_symbol')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Currency Preview -->
                    <div
                        class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</p>
                        <div class="flex items-center space-x-4">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white"
                                id="currency-preview-symbol">{{ $settings['currencySymbol'] }}</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">100</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">or</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">100</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white"
                                id="currency-preview-name">{{ $settings['currencyName'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- System Settings Tab -->
                <div id="system-panel" class="tab-panel hidden p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                System Timezone <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('timezone') border-red-500 @enderror"
                                id="timezone" name="timezone" required>
                                <option value="">Select Timezone</option>
                                @foreach (timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}"
                                        {{ $settings['timezone'] == $tz ? 'selected' : '' }}>
                                        {{ $tz }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Server Time
                            </label>
                            <input type="text"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed"
                                value="{{ now()->format('Y-m-d H:i:s') }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg border-t border-gray-200 dark:border-gray-600">
                    <div class="flex justify-end space-x-3">
                        <button type="reset"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="inline-block w-4 h-4 mr-2 -mt-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Tab Switching and Live Preview -->
    <script>
        function switchTab(tabName) {
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                    'hover:border-gray-300');
            });

            // Show selected panel
            document.getElementById(`${tabName}-panel`).classList.remove('hidden');

            // Add active class to selected tab
            const activeTab = document.getElementById(`${tabName}-tab`);
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700',
                'hover:border-gray-300');
        }

        // Live currency preview
        const currencySymbolInput = document.getElementById('currency_symbol');
        const currencyNameInput = document.getElementById('currency_name');
        const previewSymbol = document.getElementById('currency-preview-symbol');
        const previewName = document.getElementById('currency-preview-name');

        if (currencySymbolInput && previewSymbol) {
            currencySymbolInput.addEventListener('input', function() {
                previewSymbol.textContent = this.value || '$';
            });
        }

        if (currencyNameInput && previewName) {
            currencyNameInput.addEventListener('input', function() {
                previewName.textContent = this.value || 'USD';
            });
        }

        // Set active tab based on URL hash
        const hash = window.location.hash.substring(1);
        if (hash && ['general', 'company', 'currency', 'system'].includes(hash)) {
            switchTab(hash);
        }
    </script>

    <style>
        .tab-button.active {
            border-bottom-width: 2px;
        }
    </style>
@endsection
