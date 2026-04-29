<div class="card-lift fade-up bg-white rounded-2xl border border-steel-200 overflow-hidden group" 
     style="transition-delay: {{ $delay ?? '0s' }}">
    <div class="aspect-[4/3] overflow-hidden relative">
        <img src="{{ $image }}" alt="{{ $title }}" 
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute top-3 right-3 px-2.5 py-1 {{ $stock === 'Low Stock' ? 'bg-yellow-500 text-navy-800' : 'bg-green-500 text-white' }} text-[10px] font-bold uppercase tracking-wider rounded-full">
            {{ $stock }}
        </div>
    </div>
    <div class="p-6">
        <h3 class="font-display text-lg font-bold text-navy-800 mb-1">{{ $title }}</h3>
        <p class="text-sm text-steel-500 mb-4">{{ $description }}</p>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-orange-500 ml-1">{{ $price }}</span>
            </div>
            <a href="{{ route('frontend.products.show', $product->id) }}" 
                class="px-4 py-2 bg-navy-800 text-white text-sm font-medium rounded-lg hover:bg-navy-700 transition-colors">
                View Details
            </a>
        </div>
    </div>
</div>