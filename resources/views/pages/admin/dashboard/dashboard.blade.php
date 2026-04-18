@extends('layouts.app')

@section('content')
    <div class="px-4 py-6">
        <x-common.page-breadcrumb pageTitle="Dashboard Management" />

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <x-common.component-card title="Today's Rents">
                <div class="space-y-0">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ number_format($quickStats['today_rents'], 0) }} Ks
                        </h4>
                        <span class="text-sm font-semibold text-yellow-600">{{ $quickStats['pending_rents'] }} pending</span>
                    </div>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Today's Sales">
                <div class="space-y-0">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ number_format($quickStats['today_sales'], 0) }} Ks
                        </h4>
                        <span class="text-sm font-semibold text-yellow-600">{{ $quickStats['pending_sales'] }}
                            pending</span>
                    </div>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Today's Expenses">
                <div class="space-y-0">
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ number_format($quickStats['today_expenses'], 0) }} Ks
                        </h4>
                    </div>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Total Rent Income">
                <div class="flex items-center text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    <h4 class="text-lg font-bold text-gray-900">
                        {{ number_format($dashboardData['summary']['total_rents'], 0) }} Ks</h4>
                </div>
            </x-common.component-card>
        </div>

        <!-- Second Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
            <x-common.component-card title="Sales">
                <div class="flex items-center text-green-600 mt-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span class="text-sm">This Month: {{ number_format($dashboardData['current_month_sales'], 0) }}
                        Ks</span>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Expenses" :value="number_format($dashboardData['summary']['total_expenses'], 0) . ' Ks'">
                <div class="flex items-center text-red-600 mt-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                    <span class="text-sm">This Month: {{ number_format($dashboardData['current_month_expenses'], 0) }}
                        Ks</span>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Total Customers">
                <div class="flex items-center text-green-600 mt-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="text-sm">Active Customers: {{ $dashboardData['summary']['total_customers'] }}</span>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Total Products">
                <div class="flex items-center text-indigo-600 mt-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="text-sm">Available Products: {{ $dashboardData['summary']['total_products'] }}</span>
                </div>
            </x-common.component-card>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2">
                <x-common.component-card title="Financial Overview">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <button type="button"
                            class="chart-period-btn px-3 py-1.5 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                            data-period="week">
                            This Week
                        </button>
                        <button type="button"
                            class="chart-period-btn px-3 py-1.5 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                            data-period="month">
                            This Month
                        </button>
                        <button type="button"
                            class="chart-period-btn px-3 py-1.5 text-sm font-medium rounded-md border border-blue-500 text-blue-700 bg-blue-50 transition-colors"
                            data-period="year">
                            This Year
                        </button>
                    </div>
                    <canvas id="financialChart" height="300"></canvas>
                </x-common.component-card>
            </div>

            <div class="lg:col-span-1">
                <x-common.component-card title="Financial Summary of Current Month">
                    @php
                        $currentMonth = now()->month;
                        $salesTotal = $dashboardData['charts']['sales_data'][$currentMonth - 1] ?? [];
                        $rentsTotal = $dashboardData['charts']['rents_data'][$currentMonth - 1] ?? [];
                        $expensesTotal = $dashboardData['charts']['expenses_data'][$currentMonth - 1] ?? [];
                        $financialTotal = $salesTotal + $rentsTotal - $expensesTotal;

                        $salesPercent = $financialTotal > 0 ? ($salesTotal / $financialTotal) * 100 : 0;
                        $rentsPercent = $financialTotal > 0 ? ($rentsTotal / $financialTotal) * 100 : 0;
                        $expensesPercent = $financialTotal > 0 ? ($expensesTotal / $financialTotal) * 100 : 0;
                    @endphp

                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Sales:</span>
                                <strong class="text-sm text-green-600">{{ number_format($salesTotal, 0) }} Ks</strong>
                            </div>
                            {{-- <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $salesPercent }}%"></div>
                            </div> --}}
                        </div>

                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Rents:</span>
                                <strong class="text-sm text-blue-600">{{ number_format($rentsTotal, 0) }} Ks</strong>
                            </div>
                            {{-- <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $rentsPercent }}%"></div>
                            </div> --}}
                        </div>

                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm text-gray-600">Expenses:</span>
                                <strong class="text-sm text-red-600">{{ number_format($expensesTotal, 0) }} Ks</strong>
                            </div>
                            {{-- <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ $expensesPercent }}%"></div>
                            </div> --}}
                        </div>
                    </div>
                </x-common.component-card>
            </div>
        </div>

        <!-- Recent Activities Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <x-common.component-card title="Recent Sales">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['recent_activities']['sales'] as $sale)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $sale['sale_code'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $sale['customer']['name'] ?? 'Walk-in Customer' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($sale['sale_date'])->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ number_format($sale['total'], 0) }} Ks</td>
                                    <td class="px-4 py-3">
                                        @if (($sale['status'] ?? '') === 'completed')
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">No recent sales</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Recent Rents">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Due</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['recent_activities']['rents'] as $rent)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $rent['rent_code'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $rent['customer']['name'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($rent['rent_date'])->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ number_format($rent['total'], 0) }} Ks</td>
                                    <td class="px-4 py-3 text-sm font-medium text-red-600">
                                        {{ number_format($rent['total_due'], 0) }} Ks</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">No recent rents</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>
        </div>

        <!-- Products and Outstanding Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <x-common.component-card title="Top Selling Products">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity Sold</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['top_products'] as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ $product['product_name'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $product['total_quantity'] }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ number_format($product['total_amount'], 0) }} Ks</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Low Stock Alert">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Variants</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['low_stock_products'] as $product)
                                @forelse ($product['variants'] as $variant)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $product['product_name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $variant['name'] ?? 'Default' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $variant['qty'] }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Low
                                                Stock</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">No low stock variants found</td>
                                    </tr>
                                @endforelse
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">All products have
                                        sufficient stock</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>
        </div>

        <!-- Recent Purchases and Expenses Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <x-common.component-card title="Recent Purchases">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Supplier</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['recent_activities']['purchases'] as $purchase)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $purchase['purchase_code'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $purchase['supplier']['name'] ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($purchase['purchase_date'])->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ number_format($purchase['total_amount'], 0) }} Ks</td>
                                    <td class="px-4 py-3">
                                        @if ($purchase['payment_status'] == 1)
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-gray-500">No recent purchases
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>

            <x-common.component-card title="Recent Expenses">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Note</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($dashboardData['recent_activities']['expenses'] as $expense)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $expense['expense_title'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($expense['expense_date'])->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-red-600">
                                        {{ number_format($expense['amount'], 0) }} Ks</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ Str::limit($expense['note'] ?? '-', 30) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-center text-gray-500">No recent expenses</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-common.component-card>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="{{ asset('Backend/js/chart.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const ctx = document.getElementById('financialChart').getContext('2d');

            const chartPeriodButtons = document.querySelectorAll('.chart-period-btn');
            const chartDataUrl = @json(route('admin.dashboard.chart-data'));

            const initialChartData = {
                labels: @json($dashboardData['charts']['months']),
                sales: @json($dashboardData['charts']['sales_data']),
                expenses: @json($dashboardData['charts']['expenses_data']),
                rents: @json($dashboardData['charts']['rents_data']),
            };

            // Create Chart
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initialChartData.labels,
                    datasets: [{
                            label: 'Sales (Ks)',
                            data: initialChartData.sales,
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Expenses (Ks)',
                            data: initialChartData.expenses,
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Rents (Ks)',
                            data: initialChartData.rents,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 10
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += new Intl.NumberFormat().format(context.parsed.y) + ' Ks';
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat().format(value) + ' Ks';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Change active button style
            function setActivePeriodButton(activePeriod) {
                chartPeriodButtons.forEach((button) => {

                    const isActive = button.dataset.period === activePeriod;

                    button.classList.toggle('border-blue-500', isActive);
                    button.classList.toggle('text-blue-700', isActive);
                    button.classList.toggle('bg-blue-50', isActive);

                    button.classList.toggle('border-gray-300', !isActive);
                    button.classList.toggle('text-gray-700', !isActive);
                    button.classList.toggle('bg-white', !isActive);
                });
            }

            // Apply new chart data
            function applyChartData(chartData) {

                chart.data.labels = chartData.labels ?? [];

                chart.data.datasets[0].data = chartData.sales ?? [];
                chart.data.datasets[1].data = chartData.expenses ?? [];
                chart.data.datasets[2].data = chartData.rents ?? [];

                chart.update();
            }

            // Load data from Laravel controller
            async function loadChartData(period) {

                if (period === 'year') {
                    applyChartData(initialChartData);
                    return;
                }

                try {

                    const response = await fetch(`${chartDataUrl}?period=${period}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to load chart data');
                    }

                    const payload = await response.json();

                    if (!payload.success || !payload.data) {
                        throw new Error('Invalid chart response');
                    }

                    applyChartData(payload.data);

                } catch (error) {
                    console.error('Chart load error:', error);
                }
            }

            // Period button click
            chartPeriodButtons.forEach((button) => {

                button.addEventListener('click', async function() {

                    const period = this.dataset.period;

                    setActivePeriodButton(period);

                    await loadChartData(period);

                });

            });

        });
    </script>
@endpush
