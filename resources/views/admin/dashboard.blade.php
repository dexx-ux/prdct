<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('F d, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Containers -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Products Count -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Products</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalProducts ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="bi bi-box text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Products →</a>
                    </div>
                </div>

                <!-- Orders Count -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Orders</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalOrders ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="bi bi-cart text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Orders →</a>
                    </div>
                </div>

                <!-- Users Count -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <i class="bi bi-people text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">View Users →</a>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">₱{{ number_format($totalRevenue ?? 0, 0) }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1">₱{{ number_format($this_month_revenue ?? 0, 0) }} this month</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <i class="bi bi-currency-exchange text-2xl text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather + Recent Orders Row (Same Height) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Weather (Reduced Height) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 border border-gray-200 dark:border-gray-700 h-[260px] flex flex-col">
                    <div class="mb-3 flex-shrink-0">
                        <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                            <input type="text" 
                                   name="city" 
                                   value="{{ request('city', $city) }}"
                                   placeholder="Search city..."
                                   class="flex-1 px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-300 text-sm">
                                <i class="bi bi-cloud"></i>
                            </button>
                        </form>
                    </div>

                    <div class="flex-1">
                        @if(isset($weatherData) && isset($weatherData['cod']) && $weatherData['cod'] == 200)
                        <div>
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $weatherData['name'] ?? $city }}</h3>
                            
                            <div class="flex items-center gap-3 mb-3">
                                <div class="text-3xl">
                                    <i class="{{ $weatherData['weather_icon'] ?? 'bi bi-cloud' }}"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $weatherData['main']['temp_celsius'] ?? 0 }}°C</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 capitalize">{{ $weatherData['weather'][0]['description'] ?? '' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div class="text-center p-1.5 bg-gray-50 dark:bg-gray-700 rounded">
                                    <i class="bi bi-droplet text-blue-500 text-sm"></i>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">{{ $weatherData['main']['humidity'] }}%</p>
                                </div>
                                <div class="text-center p-1.5 bg-gray-50 dark:bg-gray-700 rounded">
                                    <i class="bi bi-wind text-blue-500 text-sm"></i>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">{{ $weatherData['wind']['speed_kmh'] }} km/h</p>
                                </div>
                                <div class="text-center p-1.5 bg-gray-50 dark:bg-gray-700 rounded">
                                    <i class="bi bi-thermometer-half text-blue-500 text-sm"></i>
                                    <p class="text-gray-600 dark:text-gray-400 text-xs">{{ $weatherData['main']['feels_like_celsius'] }}°C</p>
                                </div>
                            </div>
                        </div>
                        @elseif(isset($weatherData) && isset($weatherData['message']))
                        <div class="text-center py-3">
                            <i class="bi bi-exclamation-circle text-2xl text-orange-400 mb-1"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $weatherData['message'] }}</p>
                        </div>
                        @else
                        <div class="text-center py-3">
                            <i class="bi bi-cloud text-2xl text-gray-400 mb-1"></i>
                            <p class="text-xs text-gray-600 dark:text-gray-400">No weather data</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Orders - Scrollable (Same height as weather) -->
              <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 h-[260px] flex flex-col">
                <div class="flex items-center justify-between mb-3 flex-shrink-0">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">
                        <i class="bi bi-clock-history mr-2"></i>Recent Orders
                    </h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        View All Orders →
                    </a>
                </div>
                    <!-- Scrollable Orders Container -->
                    <div class="flex-1 overflow-y-auto pr-1 space-y-2 custom-scrollbar">
                        @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between p-2.5 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-sm">Order #{{ $order->id }} - {{ $order->customer_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->product->name ?? 'N/A' }} (x{{ $order->quantity }})</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">₱{{ number_format($order->total_price, 2) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4 text-sm">No recent orders</p>
                        @endforelse
                        
                      
                    </div>
                </div>
            </div>

            <!-- Low Stock & Categories Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Low Stock Products -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                        <i class="bi bi-exclamation-triangle text-orange-500 mr-2"></i>Low Stock Alert
                    </h3>
                    <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1 custom-scrollbar">
                        @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $product->name }}</p>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-1">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ ($product->quantity / 50) * 100 }}%"></div>
                                </div>
                            </div>
                            <span class="ml-3 inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200">
                                {{ $product->quantity }} left
                            </span>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4 text-sm">All products have good stock</p>
                        @endforelse
                    </div>
                </div>

                <!-- Categories Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                        <i class="bi bi-tag mr-2"></i>Category Breakdown
                    </h3>
                    <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1 custom-scrollbar">
                        @forelse($categories as $category)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $category->name }}</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                {{ $category->product_count }} products
                            </span>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4 text-sm">No categories</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="bi bi-star-fill text-yellow-500 mr-2"></i>Top Selling Products
                </h3>
                <div class="overflow-x-auto max-h-[300px] overflow-y-auto custom-scrollbar">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Product</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Orders</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($topProducts as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        {{ $product->order_count }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">₱{{ number_format($product->revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No products</td>
                            </tr>
                            @endforelse
                        </tbody>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Custom scrollbar for better appearance */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Dark mode scrollbar */
    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
    }
    
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
    
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
</style>