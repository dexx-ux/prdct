{{-- resources/views/admin/users/partials/stats-cards.blade.php --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Users</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $users->total() }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                <i class="bi bi-people text-blue-600 dark:text-blue-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Active Users</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $users->where('status', 'active')->count() }}
                </p>
            </div>
            <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                <i class="bi bi-check-circle text-green-600 dark:text-green-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Inactive Users</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $users->where('status', 'inactive')->count() }}
                </p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                <i class="bi bi-x-circle text-red-600 dark:text-red-300 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm">This Month</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}
                </p>
            </div>
            <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                <i class="bi bi-calendar-check text-purple-600 dark:text-purple-300 text-xl"></i>
            </div>
        </div>
    </div>
</div>