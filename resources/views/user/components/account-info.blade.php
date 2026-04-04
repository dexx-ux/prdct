<!-- Account Information Component -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Account Information</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Account Type</span>
                <span class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($user->role) }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Account Status</span>
                <span class="px-2 py-1 text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">Active</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-gray-600 dark:text-gray-400">Member Since</span>
                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-600 dark:text-gray-400">Last Updated</span>
                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
