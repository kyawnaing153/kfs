@extends('layouts.frontend.app')

@section('title', 'Customer Dashboard - KFS Scaffolding')

@section('content')
    <div id="page-dashboard" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-gray-50">
            <div class="relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">

                    {{-- Welcome Header --}}
                    <div class="mb-8">
                        <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">
                            Welcome back, <span class="text-orange-600">{{ $customer->name }}</span>
                        </h1>
                        <p class="text-gray-600">Manage your quotations, rentals, and account settings</p>
                    </div>

                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-6 h-6 text-orange-600"></i>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $stats['total_quotations'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600">Total Quotations</p>
                            <div class="flex gap-3 mt-2 text-xs">
                                <span class="text-yellow-600">{{ $stats['pending_quotations'] }} pending</span>
                                <span class="text-green-600">{{ $stats['approved_quotations'] }} approved</span>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">{{ $stats['total_rents'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600">Total Rentals</p>
                            <div class="flex gap-3 mt-2 text-xs">
                                <span class="text-green-600">{{ $stats['active_rents'] }} active</span>
                                <span class="text-gray-500">{{ $stats['completed_rents'] }} completed</span>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-600"></i>
                                </div>
                                <span class="text-2xl font-bold text-gray-900">Ks
                                    {{ number_format($stats['total_spent'], 0) }}</span>
                            </div>
                            <p class="text-sm text-gray-600">Total Spent</p>
                        </div>

                    </div>

                    {{-- Tabs Navigation --}}
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="flex gap-6">
                                <button type="button"
                                    class="tab-btn active pb-3 text-sm font-medium text-orange-600 border-b-2 border-orange-500 transition-colors"
                                    data-tab="quotations">
                                    <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                                    Quotations
                                </button>
                                <button type="button"
                                    class="tab-btn pb-3 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors"
                                    data-tab="rentals">
                                    <i data-lucide="package" class="w-4 h-4 inline mr-2"></i>
                                    Rentals
                                </button>
                                <button type="button"
                                    class="tab-btn pb-3 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors"
                                    data-tab="profile">
                                    <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                    Profile
                                </button>
                            </nav>
                        </div>
                    </div>

                    {{-- Quotations Tab --}}
                    <div id="quotationsTab" class="tab-content active">
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-4 h-4 text-orange-600"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">Quotations</span>
                                </div>
                                <a href="{{ route('frontend.quotations.create') }}"
                                    class="px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                    <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i>
                                    New Quotation
                                </a>
                            </div>

                            @if ($quotations->isEmpty())
                                <div class="text-center py-12">
                                    <i data-lucide="file-text" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                                    <p class="text-gray-500">No quotations yet</p>
                                    <a href="{{ route('frontend.quotations.create') }}"
                                        class="inline-block mt-4 text-orange-600 hover:text-orange-700">
                                        Create your first quotation →
                                    </a>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100">
                                    @foreach ($quotations as $quotation)
                                        <div class="p-6 hover:bg-gray-50 transition-colors">
                                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <span
                                                            class="font-mono text-sm text-gray-900">{{ $quotation->quotation_code }}</span>
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                                        {{ $quotation->type === 'rent' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' }}">
                                                            <i data-lucide="{{ $quotation->type === 'rent' ? 'calendar' : 'shopping-cart' }}"
                                                                class="w-3 h-3"></i>
                                                            {{ ucfirst($quotation->type) }}
                                                        </span>
                                                        @php
                                                            $statusColors = [
                                                                'submitted' => 'yellow',
                                                                'approved' => 'green',
                                                                'rejected' => 'red',
                                                                'expired' => 'gray',
                                                            ];
                                                            $color = $statusColors[$quotation->status] ?? 'steel';
                                                        @endphp
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-500/20 text-{{ $color }}-400">
                                                            <span
                                                                class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-400"></span>
                                                            {{ ucfirst($quotation->status) }}
                                                        </span>
                                                    </div>

                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Quotation Date</p>
                                                            <p class="text-gray-900">
                                                                {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('M d, Y') }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Deposit</p>
                                                            <p class="text-gray-900 capitalize">
                                                                Ks {{ number_format($quotation->deposit, 0) }}</p>
                                                            @if ($quotation->type === 'rent')
                                                                <p class="mt-1 text-xs text-gray-400">
                                                                    {{ $quotation->rent_duration ?? 1 }}
                                                                    day{{ ($quotation->rent_duration ?? 1) > 1 ? 's' : '' }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Total Amount</p>
                                                            <p class="text-gray-900 font-semibold">Ks
                                                                {{ number_format($quotation->total, 0) }}</p>
                                                            <p class="mt-1 text-xs text-gray-400">
                                                                Daily Rental: Ks
                                                                {{ number_format($quotation->sub_total, 0) }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Transport</p>
                                                            <p
                                                                class="{{ $quotation->transport_required ? 'text-orange-600' : 'text-gray-600' }}">
                                                                {{ $quotation->transport_required ? 'Required' : 'Not Required' }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    @if ($quotation->items && $quotation->items->count() > 0)
                                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                                            <p class="text-xs text-gray-500 mb-2">Quoted Items:</p>
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach ($quotation->items as $item)
                                                                    @php
                                                                        $productName =
                                                                            $item->productVariant->product
                                                                                ->product_name ?? 'Product';
                                                                        $size = $item->productVariant->size ?? '';
                                                                    @endphp
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">
                                                                        {{ $productName }} {{ $size }}
                                                                        ({{ $item->qty }} items)
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <button onclick="viewQuotation({{ $quotation->id }})"
                                                        class="px-4 py-2 bg-white text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 shadow-sm">
                                                        <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Rentals Tab --}}
                    <div id="rentalsTab" class="tab-content hidden">
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">Your Rentals</span>
                                </div>
                            </div>

                            @if ($rents->isEmpty())
                                <div class="text-center py-12">
                                    <i data-lucide="package" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                                    <p class="text-gray-500">No rentals yet</p>
                                    <a href="{{ route('frontend.quotations.create') }}"
                                        class="inline-block mt-4 text-orange-600 hover:text-orange-700">
                                        Start renting →
                                    </a>
                                </div>
                            @else
                                <div class="divide-y divide-gray-100">
                                    @foreach ($rents as $rent)
                                        <div class="p-6 hover:bg-gray-50 transition-colors">
                                            <div
                                                class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-3 mb-2">
                                                        <span
                                                            class="font-mono text-sm text-gray-900">{{ $rent->rent_code }}</span>
                                                        @php
                                                            $rentStatusColors = [
                                                                'ongoing' => 'green',
                                                                'completed' => 'blue',
                                                                'cancelled' => 'red',
                                                                'pending' => 'yellow',
                                                            ];
                                                            $rentColor = $rentStatusColors[$rent->status] ?? 'steel';
                                                        @endphp
                                                        <span
                                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-{{ $rentColor }}-500/20 text-{{ $rentColor }}-400">
                                                            <span
                                                                class="w-1.5 h-1.5 rounded-full bg-{{ $rentColor }}-400"></span>
                                                            {{ ucfirst($rent->status) }}
                                                        </span>
                                                    </div>
                                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Rental Date</p>
                                                            <p class="text-gray-900">
                                                                {{ \Carbon\Carbon::parse($rent->rent_date)->format('M d, Y') }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Deposit</p>
                                                            <p class="text-gray-900 capitalize">
                                                                Ks {{ number_format($rent->deposit, 0) }}</p>
                                                            <p class="mt-1 text-xs text-gray-400">
                                                                Payment: {{ $rent->payment_type }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Total Amount</p>
                                                            <p class="text-gray-900 font-semibold">Ks
                                                                {{ number_format($rent->total, 0) }}</p>
                                                            <p class="mt-1 text-xs text-gray-400">
                                                                Daily Rental: Ks {{ number_format($rent->sub_total, 0) }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-500 text-xs">Total Paid</p>
                                                            <p class="text-green-600">Ks
                                                                {{ number_format($rent->total_paid, 0) }}</p>
                                                        </div>
                                                    </div>

                                                    {{-- Rental Items --}}
                                                    @if ($rent->items && $rent->items->count() > 0)
                                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                                            <p class="text-xs text-gray-500 mb-2">Items Rented:</p>
                                                            <div class="flex flex-wrap gap-2">
                                                                @foreach ($rent->items as $item)
                                                                    @php
                                                                        $productName =
                                                                            $item->productVariant->product
                                                                                ->product_name ?? 'Product';
                                                                        $size = $item->productVariant->size ?? '';
                                                                    @endphp
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-lg text-xs text-gray-700">
                                                                        {{ $productName }} {{ $size }}
                                                                        ({{ $item->rent_qty }} items)
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <button onclick="viewRental({{ $rent->id }})"
                                                        class="px-4 py-2 bg-white text-gray-900 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors border border-gray-200 shadow-sm">
                                                        <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                                                        View Details
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Profile Tab --}}
                    <div id="profileTab" class="tab-content hidden">
                        <div class="grid lg:grid-cols-3 gap-6">
                            {{-- Profile Info --}}
                            <div class="lg:col-span-2">
                                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>

                                    <form action="{{ route('customers.update', $customer) }}" method="POST"
                                        class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="status" value="1">
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Full
                                                    Name</label>
                                                <input type="text" name="name" value="{{ $customer->name }}"
                                                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Email
                                                    Address</label>
                                                <input type="email" name="email" value="{{ $customer->email }}"
                                                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500">
                                            </div>
                                        </div>

                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone
                                                    Number</label>
                                                <input type="tel" name="phone_number"
                                                    value="{{ $customer->phone_number ?? '' }}"
                                                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name
                                                    (Optional)</label>
                                                <input type="text" name="company_name"
                                                    value="{{ $customer->company_name ?? '' }}"
                                                    class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Home
                                                Address</label>
                                            <textarea name="address" rows="3"
                                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-1 focus:ring-orange-500/50 focus:border-orange-500 resize-none">{{ $customer->address ?? '' }}</textarea>
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit"
                                                class="px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                                Update Profile
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Account Stats --}}
                            <div class="space-y-6">
                                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                    <h3 class="text-sm font-semibold text-orange-600 uppercase tracking-wider mb-4">Account
                                        Summary</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Member Since</span>
                                            <span
                                                class="text-gray-900">{{ $customer->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Total Quotations</span>
                                            <span class="text-gray-900">{{ $stats['total_quotations'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Total Rentals</span>
                                            <span class="text-gray-900">{{ $stats['total_rents'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Lifetime Spending</span>
                                            <span class="text-orange-600 font-semibold">Ks
                                                {{ number_format($stats['total_spent'], 0) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                    <h3 class="text-sm font-semibold text-orange-600 uppercase tracking-wider mb-4">Quick
                                        Actions</h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('frontend.quotations.create') }}"
                                            class="flex items-center gap-3 px-4 py-2 bg-orange-50 border border-orange-200 rounded-lg text-orange-700 hover:bg-orange-100 transition-colors">
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">Create New Quotation</span>
                                        </a>
                                        <button type="button" data-modal-open="changePasswordModal"
                                            class="flex w-full items-center gap-3 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                            <i data-lucide="key" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">Change Password</span>
                                        </button>
                                        <button type="button" data-modal-open="termsConditionModal"
                                            class="flex w-full items-center gap-3 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                                            <i data-lucide="scroll-text" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">Terms & Conditions</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('pages.frontend.customers.partials.change-password-modal')
    @include('pages.frontend.customers.partials.terms-condition-modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tab switching
            $('.tab-btn').on('click', function() {
                const tabId = $(this).data('tab');

                // Update active states
                $('.tab-btn')
                    .removeClass('active text-orange-600 border-b-2 border-orange-500')
                    .addClass('text-gray-500');

                $(this)
                    .removeClass('text-gray-500')
                    .addClass('active text-orange-600 border-b-2 border-orange-500');

                // Show/hide content
                $('.tab-content').removeClass('active').addClass('hidden');
                $(`#${tabId}Tab`).removeClass('hidden').addClass('active');

                // Reinitialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            $('[data-modal-open]').on('click', function() {
                const modalId = $(this).data('modal-open');
                openDashboardModal(modalId);
            });

            $('[data-modal-close]').on('click', function() {
                const modalId = $(this).data('modal-close');
                closeDashboardModal(modalId);
            });

            $('[data-modal]').on('click', function(event) {
                if (event.target === this) {
                    closeDashboardModal($(this).attr('id'));
                }
            });

            $(document).on('keydown', function(event) {
                if (event.key === 'Escape') {
                    $('[data-modal]').each(function() {
                        closeDashboardModal($(this).attr('id'));
                    });
                }
            });

            @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                openDashboardModal('changePasswordModal');
            @endif
        });

        function openDashboardModal(modalId) {
            $(`#${modalId}`).removeClass('hidden').addClass('flex');
            $('body').addClass('overflow-hidden');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        function closeDashboardModal(modalId) {
            const modal = $(`#${modalId}`);
            modal.addClass('hidden').removeClass('flex');

            if ($('[data-modal].flex').length === 0) {
                $('body').removeClass('overflow-hidden');
            }
        }

        // View Quotation Details
        function viewQuotation(id) {
            window.location.href = `{{ url('/home/customer/quotations') }}/${id}`;
        }

        // View Rental Details
        function viewRental(id) {
            // Implement modal or redirect to rental details page
            window.location.href = `/home/customer/rents/${id}/invoice`;
        }
    </script>
@endpush
