<!-- Welcome Card Component -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h1>
                <p class="text-gray-600 dark:text-gray-400">Here's your account overview and activity</p>
            </div>
            <div>
                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>
</div>
