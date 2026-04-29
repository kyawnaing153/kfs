{{-- resources/views/frontend/quotations/track.blade.php --}}
@extends('layouts.frontend.app')

@section('title', 'Track Quotation - KFS')

@section('content')
<div class="pt-24 lg:pt-28 min-h-screen bg-navy-800 blueprint-grid-dark">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="text-center mb-12 fade-up">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-500/15 border border-orange-500/25 rounded-full mb-4">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-orange-400"></i>
                <span class="text-xs font-semibold text-orange-300 uppercase tracking-wider">Track Status</span>
            </div>
            <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4">
                Track Your <span class="text-orange-400">Quotation</span>
            </h1>
            <p class="text-lg text-steel-300 max-w-2xl mx-auto">
                Enter your quotation number or email to check the status
            </p>
        </div>

        {{-- Search Form --}}
        <div class="bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl mb-8">
            <form method="GET" action="#" class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Quotation Number</label>
                        <input type="text" name="quote_number" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500"
                            placeholder="e.g., KFS-2024-0001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Email Address</label>
                        <input type="email" name="email" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white focus:border-orange-500"
                            placeholder="your@email.com">
                    </div>
                </div>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition-all">
                    Track Quotation
                </button>
            </form>
        </div>

        {{-- Dummy Tracking Results --}}
        @if(request()->has('quote_number') || request()->has('email'))
        <div class="space-y-6 fade-up">
            {{-- Status Card --}}
            <div class="bg-navy-800/80 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                    <div>
                        <p class="text-sm text-steel-400">Quotation Number</p>
                        <p class="text-xl font-bold text-white">KFS-2024-0042</p>
                    </div>
                    <div class="px-4 py-2 bg-green-500/20 border border-green-500/30 rounded-lg">
                        <span class="text-green-400 font-semibold">● Approved</span>
                    </div>
                </div>

                {{-- Progress Timeline --}}
                <div class="relative mb-8">
                    <div class="absolute top-5 left-0 right-0 h-0.5 bg-white/10"></div>
                    <div class="relative flex justify-between">
                        @php
                            $statuses = [
                                ['icon' => 'file-text', 'label' => 'Submitted', 'date' => 'Dec 10, 2024', 'completed' => true],
                                ['icon' => 'eye', 'label' => 'Under Review', 'date' => 'Dec 11, 2024', 'completed' => true],
                                ['icon' => 'file-check', 'label' => 'Quotation Ready', 'date' => 'Dec 12, 2024', 'completed' => true],
                                ['icon' => 'check-circle', 'label' => 'Approved', 'date' => 'Dec 13, 2024', 'completed' => true],
                                ['icon' => 'truck', 'label' => 'Processing', 'date' => 'Pending', 'completed' => false],
                            ];
                        @endphp
                        @foreach($statuses as $index => $status)
                        <div class="text-center relative z-10 flex-1">
                            <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $status['completed'] ? 'bg-orange-500' : 'bg-white/10' }} mb-2">
                                <i data-lucide="{{ $status['icon'] }}" class="w-5 h-5 {{ $status['completed'] ? 'text-white' : 'text-steel-400' }}"></i>
                            </div>
                            <p class="text-sm font-medium text-white">{{ $status['label'] }}</p>
                            <p class="text-xs text-steel-400">{{ $status['date'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Quotation Details --}}
                <div class="border-t border-white/10 pt-6">
                    <h3 class="font-semibold text-white mb-4">Quotation Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-steel-400">Standard Scaffolding Frame (10 sets)</span>
                            <span class="text-white">Ks 25,000/day</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-steel-400">Scaffolding Boards (50 pieces)</span>
                            <span class="text-white">Ks 42,500/day</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-steel-400">Guard Rail System (20 pieces)</span>
                            <span class="text-white">Ks 24,000/day</span>
                        </div>
                        <div class="border-t border-white/10 pt-3 mt-3">
                            <div class="flex justify-between font-semibold">
                                <span class="text-white">Daily Total</span>
                                <span class="text-orange-400">Ks 91,500/day</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span class="text-steel-400">Project Duration</span>
                                <span class="text-white">15 days</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold mt-3 pt-3 border-t border-white/10">
                                <span class="text-white">Total Amount</span>
                                <span class="text-orange-400">Ks 1,372,500</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-4 mt-6 pt-6 border-t border-white/10">
                    <a href="#" class="flex-1 text-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-all">
                        Download PDF
                    </a>
                    <a href="#" class="flex-1 text-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-all">
                        Contact Support
                    </a>
                </div>
            </div>

            {{-- Support Message --}}
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 text-center">
                <p class="text-sm text-steel-300">
                    <i data-lucide="message-circle" class="w-4 h-4 inline mr-2"></i>
                    Have questions about your quotation? Our team is here to help! 
                    <a href="{{ route('frontend.contact') }}" class="text-orange-400 hover:underline">Contact us</a>
                </p>
            </div>
        </div>
        @endif

        {{-- Recent Quotations (Dummy Data) --}}
        <div class="mt-12">
            <h3 class="text-lg font-semibold text-white mb-4">Recent Quotations</h3>
            <div class="space-y-3">
                @php
                    $recentQuotes = [
                        ['number' => 'KFS-2024-0041', 'date' => 'Dec 09, 2024', 'amount' => 'Ks 892,500', 'status' => 'completed'],
                        ['number' => 'KFS-2024-0040', 'date' => 'Dec 08, 2024', 'amount' => 'Ks 2,145,000', 'status' => 'approved'],
                        ['number' => 'KFS-2024-0039', 'date' => 'Dec 07, 2024', 'amount' => 'Ks 567,800', 'status' => 'pending'],
                    ];
                @endphp
                @foreach($recentQuotes as $quote)
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 hover:bg-white/10 transition-all cursor-pointer">
                    <div class="flex justify-between items-center flex-wrap gap-3">
                        <div>
                            <p class="font-semibold text-white">{{ $quote['number'] }}</p>
                            <p class="text-xs text-steel-400">{{ $quote['date'] }}</p>
                        </div>
                        <div>
                            <p class="text-orange-400 font-semibold">{{ $quote['amount'] }}</p>
                        </div>
                        <div>
                            @if($quote['status'] == 'completed')
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Completed</span>
                            @elseif($quote['status'] == 'approved')
                                <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Pending Review</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection