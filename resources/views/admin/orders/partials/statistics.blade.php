<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Orders Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-3 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Orders</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalOrders }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="bi bi-arrow-up-right text-green-500 text-xs"></i>
                    {{ $ordersThisMonth }} this month
                </p>
            </div>
            <div class="text-3xl text-blue-500">
                <i class="bi bi-cart-fill"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-3 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">₱{{ number_format($totalRevenue, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="bi bi-arrow-up-right text-green-500 text-xs"></i>
                    ₱{{ number_format($revenueThisMonth, 2) }} this month
                </p>
            </div>
            <div class="text-3xl text-green-500">
                <i class="bi bi-currency-exchange"></i>
            </div>
        </div>
    </div>

    <!-- Average Order Value Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-3 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Avg Order Value</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">₱{{ number_format($averageOrderValue, 2) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="bi bi-graph-up text-xs"></i> Per order
                </p>
            </div>
            <div class="text-3xl text-purple-500">
                <i class="bi bi-bar-chart-fill"></i>
            </div>
        </div>
    </div>

    <!-- Orders This Week Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-3 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">This Week</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $ordersThisWeek }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    ₱{{ number_format($revenueThisWeek, 2) }} revenue
                </p>
            </div>
            <div class="text-3xl text-orange-500">
                <i class="bi bi-calendar-week"></i>
            </div>
        </div>
    </div>
</div>

<!-- Collapsible Analytics Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-6">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <button onclick="toggleAnalytics()" class="w-full flex items-center justify-between text-left">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="bi bi-bar-chart-line"></i>Analytics Dashboard
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">(Click to expand)</span>
            </h3>
            <i id="analyticsIcon" class="bi bi-chevron-down text-gray-500 transition-transform duration-200"></i>
        </button>
    </div>

    <div id="analyticsContent" class="hidden overflow-hidden">
        <!-- Three Column Layout with Scrollable Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-4">
            <!-- Most Ordered Products -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg flex flex-col h-[320px]">
                <div class="p-3 border-b border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
                        <i class="bi bi-star-fill text-yellow-500 mr-1"></i>Most Ordered Products
                    </h4>
                </div>
                <div class="overflow-y-auto flex-1 p-3">
                    <div class="space-y-2">
                        @forelse($mostOrderedProducts as $product)
                        <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-500 transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white text-xs truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->order_count }} orders</p>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="font-semibold text-gray-900 dark:text-white text-xs">₱{{ number_format($product->total_revenue, 2) }}</p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    #{{ $loop->iteration }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 dark:text-gray-400 text-xs text-center py-2">No order data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Revenue Products -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg flex flex-col h-[320px]">
                <div class="p-3 border-b border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
                        <i class="bi bi-trophy-fill text-amber-500 mr-1"></i>Top Revenue Products
                    </h4>
                </div>
                <div class="overflow-y-auto flex-1 p-3">
                    <div class="space-y-2">
                        @forelse($revenueByProduct as $product)
                        <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-600 rounded hover:bg-gray-100 dark:hover:bg-gray-500 transition">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white text-xs truncate">{{ $product->name }}</p>
                                <div class="w-full bg-gray-200 dark:bg-gray-500 rounded-full h-1 mt-0.5">
                                    <div class="bg-green-500 h-1 rounded-full" style="width: {{ $revenueByProduct->isNotEmpty() ? min(100, ($product->revenue / $revenueByProduct->first()->revenue) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="text-right ml-2 flex-shrink-0">
                                <p class="font-semibold text-gray-900 dark:text-white text-xs">₱{{ number_format($product->revenue, 2) }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 dark:text-gray-400 text-xs text-center py-2">No revenue data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

         <!-- Top Customers -->
<div class="bg-gray-50 dark:bg-gray-700 rounded-lg flex flex-col h-[320px]">
    <div class="p-3 border-b border-gray-200 dark:border-gray-600 flex-shrink-0">
        <h4 class="text-sm font-semibold text-gray-800 dark:text-white">
            <i class="bi bi-people-fill text-indigo-500 mr-1"></i>Top Customers
        </h4>
    </div>
    <div class="overflow-y-auto flex-1">
        <table class="w-full text-sm">
            <thead class="bg-white dark:bg-gray-600 sticky top-0">
                <tr>
                    <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</th>
                    <th class="px-2 py-1.5 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Orders</th>
                    <th class="px-2 py-1.5 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                @forelse($topCustomers as $customer)
                <tr class="hover:bg-white dark:hover:bg-gray-600">
                    <td class="px-2 py-1.5">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-xs truncate max-w-[120px]">
                                @if($customer->customer_type === 'guest')
                                    <span class="text-orange-500">👤 Guest:</span>
                                @endif
                                {{ $customer->customer_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[120px]">
                                {{ $customer->customer_email }}
                                @if($customer->customer_type === 'guest')
                                    <span class="text-xs text-orange-400">(Guest)</span>
                                @endif
                            </p>
                        </div>
                    </td>
                    <td class="px-2 py-1.5 text-center">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                            {{ $customer->order_count }}
                        </span>
                    </td>
                    <td class="px-2 py-1.5 text-right font-semibold text-gray-900 dark:text-white text-xs">
                        ₱{{ number_format($customer->total_spent, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-2 py-3 text-center text-gray-500 dark:text-gray-400 text-xs">No customer data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
        </div>
    </div>
</div>

<script>
function toggleAnalytics() {
    const content = document.getElementById('analyticsContent');
    const icon = document.getElementById('analyticsIcon');

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        content.style.maxHeight = content.scrollHeight + 'px';
        icon.classList.add('rotate-180');
    } else {
        content.style.maxHeight = '0px';
        setTimeout(() => {
            content.classList.add('hidden');
        }, 200);
        icon.classList.remove('rotate-180');
    }
}
</script>