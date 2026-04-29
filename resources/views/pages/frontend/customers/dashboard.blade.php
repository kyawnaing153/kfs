@extends('layouts.frontend.app')

@section('title', 'Customer Dashboard - KFS Scaffolding')

@section('content')
    <div id="page-dashboard" class="page active">
        <div class="pt-20 lg:pt-24 min-h-screen bg-navy-800 blueprint-grid-dark">
            <div class="relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-yellow-400/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
                    
                    {{-- Welcome Header --}}
                    <div class="mb-8">
                        <h1 class="font-display text-3xl font-bold text-white mb-2">
                            Welcome back, <span class="text-orange-400">{{ $customer->name }}</span>
                        </h1>
                        <p class="text-steel-300">Manage your quotations, rentals, and account settings</p>
                    </div>

                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-navy-700/50 to-navy-800/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-6 h-6 text-orange-400"></i>
                                </div>
                                <span class="text-2xl font-bold text-white">{{ $stats['total_quotations'] }}</span>
                            </div>
                            <p class="text-sm text-steel-300">Total Quotations</p>
                            <div class="flex gap-3 mt-2 text-xs">
                                <span class="text-yellow-400">{{ $stats['pending_quotations'] }} pending</span>
                                <span class="text-green-400">{{ $stats['approved_quotations'] }} approved</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-navy-700/50 to-navy-800/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="package" class="w-6 h-6 text-blue-400"></i>
                                </div>
                                <span class="text-2xl font-bold text-white">{{ $stats['total_rents'] }}</span>
                            </div>
                            <p class="text-sm text-steel-300">Total Rentals</p>
                            <div class="flex gap-3 mt-2 text-xs">
                                <span class="text-green-400">{{ $stats['active_rents'] }} active</span>
                                <span class="text-steel-400">{{ $stats['completed_rents'] }} completed</span>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-navy-700/50 to-navy-800/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-400"></i>
                                </div>
                                <span class="text-2xl font-bold text-white">${{ number_format($stats['total_spent'], 0) }}</span>
                            </div>
                            <p class="text-sm text-steel-300">Total Spent</p>
                        </div>

                        {{-- <div class="bg-gradient-to-br from-navy-700/50 to-navy-800/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                                    <i data-lucide="user" class="w-6 h-6 text-purple-400"></i>
                                </div>
                                <span class="text-2xl font-bold text-white">{{ $customer->email ?? 'N/A' }}</span>
                            </div>
                            <p class="text-sm text-steel-300">Email Address</p>
                        </div> --}}
                    </div>

                    {{-- Tabs Navigation --}}
                    <div class="mb-6">
                        <div class="border-b border-white/10">
                            <nav class="flex gap-6">
                                <button class="tab-btn active pb-3 text-sm font-medium text-orange-400 border-b-2 border-orange-400 transition-colors" data-tab="quotations">
                                    <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                                    Quotations
                                </button>
                                <button class="tab-btn pb-3 text-sm font-medium text-steel-400 hover:text-steel-300 transition-colors" data-tab="rentals">
                                    <i data-lucide="package" class="w-4 h-4 inline mr-2"></i>
                                    Rentals
                                </button>
                                <button class="tab-btn pb-3 text-sm font-medium text-steel-400 hover:text-steel-300 transition-colors" data-tab="profile">
                                    <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                    Profile
                                </button>
                            </nav>
                        </div>
                    </div>

                    {{-- Quotations Tab --}}
                    <div id="quotationsTab" class="tab-content active">
                        <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-4 h-4 text-orange-400"></i>
                                    </div>
                                    <span class="font-semibold text-white">Your Quotations</span>
                                </div>
                                <a href="{{ route('frontend.quotations.create') }}" class="px-4 py-2 bg-orange-500 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                    <i data-lucide="plus" class="w-4 h-4 inline mr-1"></i>
                                    New Quotation
                                </a>
                            </div>

                            @if($quotations->isEmpty())
                                <div class="text-center py-12">
                                    <i data-lucide="file-text" class="w-16 h-16 text-steel-600 mx-auto mb-4"></i>
                                    <p class="text-steel-400">No quotations yet</p>
                                    <a href="{{ route('frontend.quotations.create') }}" class="inline-block mt-4 text-orange-400 hover:text-orange-300">
                                        Create your first quotation →
                                    </a>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-navy-800/50 border-b border-white/10">
                                            <tr>
                                                <th class="text-left px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Code</th>
                                                <th class="text-left px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Type</th>
                                                <th class="text-left px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Date</th>
                                                <th class="text-right px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Total</th>
                                                <th class="text-center px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Status</th>
                                                <th class="text-right px-6 py-4 text-xs font-semibold text-steel-400 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-white/5">
                                            @foreach($quotations as $quotation)
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="px-6 py-4">
                                                    <span class="text-white font-mono text-sm">{{ $quotation->quotation_code }}</span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                                        {{ $quotation->type === 'rent' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400' }}">
                                                        <i data-lucide="{{ $quotation->type === 'rent' ? 'calendar' : 'shopping-cart' }}" class="w-3 h-3"></i>
                                                        {{ ucfirst($quotation->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-steel-300 text-sm">
                                                    {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-right text-white font-semibold">
                                                    ${{ number_format($quotation->total, 0) }}
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @php
                                                        $statusColors = [
                                                            'submitted' => 'yellow',
                                                            'approved' => 'green',
                                                            'rejected' => 'red',
                                                            'expired' => 'gray'
                                                        ];
                                                        $color = $statusColors[$quotation->status] ?? 'steel';
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-{{ $color }}-500/20 text-{{ $color }}-400">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-400"></span>
                                                        {{ ucfirst($quotation->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <button onclick="viewQuotation({{ $quotation->id }})" class="text-steel-400 hover:text-orange-400 transition-colors">
                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Rentals Tab --}}
                    <div id="rentalsTab" class="tab-content hidden">
                        <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl overflow-hidden">
                            <div class="px-6 py-4 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-4 h-4 text-blue-400"></i>
                                    </div>
                                    <span class="font-semibold text-white">Your Rentals</span>
                                </div>
                            </div>

                            @if($rents->isEmpty())
                                <div class="text-center py-12">
                                    <i data-lucide="package" class="w-16 h-16 text-steel-600 mx-auto mb-4"></i>
                                    <p class="text-steel-400">No rentals yet</p>
                                    <a href="{{ route('frontend.quotations.create') }}" class="inline-block mt-4 text-orange-400 hover:text-orange-300">
                                        Start renting →
                                    </a>
                                </div>
                            @else
                                <div class="divide-y divide-white/5">
                                    @foreach($rents as $rent)
                                    <div class="p-6 hover:bg-white/5 transition-colors">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="font-mono text-sm text-white">{{ $rent->rent_code }}</span>
                                                    @php
                                                        $rentStatusColors = [
                                                            'active' => 'green',
                                                            'completed' => 'blue',
                                                            'cancelled' => 'red',
                                                            'pending' => 'yellow'
                                                        ];
                                                        $rentColor = $rentStatusColors[$rent->status] ?? 'steel';
                                                    @endphp
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-{{ $rentColor }}-500/20 text-{{ $rentColor }}-400">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $rentColor }}-400"></span>
                                                        {{ ucfirst($rent->status) }}
                                                    </span>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                    <div>
                                                        <p class="text-steel-500 text-xs">Rental Date</p>
                                                        <p class="text-white">{{ \Carbon\Carbon::parse($rent->rent_date)->format('M d, Y') }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-steel-500 text-xs">Payment Type</p>
                                                        <p class="text-white capitalize">{{ $rent->payment_type }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-steel-500 text-xs">Total Amount</p>
                                                        <p class="text-white font-semibold">${{ number_format($rent->total, 0) }}</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-steel-500 text-xs">Total Paid</p>
                                                        <p class="text-green-400">${{ number_format($rent->total_paid, 0) }}</p>
                                                    </div>
                                                </div>
                                                
                                                {{-- Rental Items --}}
                                                @if($rent->items && $rent->items->count() > 0)
                                                <div class="mt-4 pt-4 border-t border-white/10">
                                                    <p class="text-xs text-steel-500 mb-2">Items Rented:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($rent->items as $item)
                                                            @php
                                                                $productName = $item->productVariant->product->product_name ?? 'Product';
                                                                $size = $item->productVariant->size ?? '';
                                                            @endphp
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-navy-800 rounded-lg text-xs text-steel-300">
                                                                {{ $productName }} {{ $size }}  ({{ $item->rent_qty }} items)
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <button onclick="viewRental({{ $rent->id }})" class="px-4 py-2 bg-navy-800 text-white text-sm font-semibold rounded-lg hover:bg-navy-700 transition-colors border border-white/10">
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
                                <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                                    <h3 class="text-lg font-semibold text-white mb-4">Profile Information</h3>
                                    
                                    <form action="#" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-steel-300 mb-2">Full Name</label>
                                                <input type="text" name="name" value="{{ $customer->name }}" class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-steel-300 mb-2">Email Address</label>
                                                <input type="email" name="email" value="{{ $customer->email }}" class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                            </div>
                                        </div>
                                        
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-steel-300 mb-2">Phone Number</label>
                                                <input type="tel" name="phone" value="{{ $customer->phone ?? '' }}" class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-steel-300 mb-2">Company Name (Optional)</label>
                                                <input type="text" name="company" value="{{ $customer->company ?? '' }}" class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-steel-300 mb-2">Default Address</label>
                                            <textarea name="address" rows="3" class="w-full px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-orange-500/50 resize-none">{{ $customer->address ?? '' }}</textarea>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <button type="submit" class="px-6 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-colors">
                                                Update Profile
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            {{-- Account Stats --}}
                            <div class="space-y-6">
                                <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                                    <h3 class="text-sm font-semibold text-orange-400 uppercase tracking-wider mb-4">Account Summary</h3>
                                    <div class="space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-steel-400">Member Since</span>
                                            <span class="text-white">{{ $customer->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-steel-400">Total Quotations</span>
                                            <span class="text-white">{{ $stats['total_quotations'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-steel-400">Total Rentals</span>
                                            <span class="text-white">{{ $stats['total_rents'] }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-steel-400">Lifetime Spending</span>
                                            <span class="text-orange-400 font-semibold">${{ number_format($stats['total_spent'], 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-navy-700/50 backdrop-blur-sm border border-white/10 rounded-2xl p-6">
                                    <h3 class="text-sm font-semibold text-orange-400 uppercase tracking-wider mb-4">Quick Actions</h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('frontend.quotations.create') }}" class="flex items-center gap-3 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-lg text-orange-400 hover:bg-orange-500/20 transition-colors">
                                            <i data-lucide="file-text" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">Create New Quotation</span>
                                        </a>
                                        <a href="#" class="flex items-center gap-3 px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-steel-300 hover:text-white hover:bg-navy-700 transition-colors">
                                            <i data-lucide="history" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">View Rental History</span>
                                        </a>
                                        <a href="#" class="flex items-center gap-3 px-4 py-2 bg-navy-800 border border-white/10 rounded-lg text-steel-300 hover:text-white hover:bg-navy-700 transition-colors">
                                            <i data-lucide="key" class="w-4 h-4"></i>
                                            <span class="text-sm font-medium">Change Password</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tab switching
        $('.tab-btn').on('click', function() {
            const tabId = $(this).data('tab');
            
            // Update active states
            $('.tab-btn').removeClass('active text-orange-400 border-orange-400').addClass('text-steel-400 border-transparent');
            $(this).addClass('active text-orange-400 border-orange-400');
            
            // Show/hide content
            $('.tab-content').addClass('hidden');
            $(`#${tabId}Tab`).removeClass('hidden');
            
            // Reinitialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });
    
    // View Quotation Details
    function viewQuotation(id) {
        // Implement modal or redirect to quotation details page
        window.location.href = `/customer/quotations/${id}`;
    }
    
    // View Rental Details
    function viewRental(id) {
        // Implement modal or redirect to rental details page
        window.location.href = `/home/customer/rents/${id}/invoice`;
    }
</script>
@endpush