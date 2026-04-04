{{-- Stats Cards for Products --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Products</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $products->total() }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                <i class="bi bi-box text-blue-600 dark:text-blue-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Low Stock</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ isset($allProducts) ? $allProducts->where('quantity', '<', 5)->where('quantity', '>', 0)->count() : 0 }}
                </p>
            </div>
            <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                <i class="bi bi-exclamation-circle text-yellow-600 dark:text-yellow-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Out of Stock</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ isset($allProducts) ? $allProducts->where('quantity', 0)->count() : 0 }}
                </p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                <i class="bi bi-x-circle text-red-600 dark:text-red-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Value</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    ₱{{ isset($allProducts) ? number_format($allProducts->sum(function($p) { return $p->quantity * $p->price; }), 2) : 0 }}
                </p>
            </div>
            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                <i class="bi bi-graph-up text-green-600 dark:text-green-300 text-xl"></i>
            </div>
        </div>
    </div>
</div>
