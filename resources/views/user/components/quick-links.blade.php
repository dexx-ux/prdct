<!-- Quick Links Component -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="{{ route('user.products.browse') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Browse Products</h3>
            <svg class="w-6 h-6 text-[#991b1b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4m8 0a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm">Explore our product catalog and place new orders.</p>
        <div class="mt-4 text-[#991b1b] font-medium text-sm">Browse Now →</div>
    </a>

    <a href="{{ route('user.orders.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">My Orders</h3>
            <svg class="w-6 h-6 text-[#991b1b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm">View and manage all your orders in one place.</p>
        <div class="mt-4 text-[#991b1b] font-medium text-sm">View Orders →</div>
    </a>
</div>
