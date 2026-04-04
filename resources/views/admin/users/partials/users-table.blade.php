{{-- resources/views/admin/users/partials/users-table.blade.php --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                <tr>
                    <th class="px-4 py-3 text-left w-10">
                        <input type="checkbox" id="selectAll" 
                               class="rounded border-gray-300 text-[#1a2c3e] focus:ring-[#1a2c3e]">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Profile
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Email
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Joined Date
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="usersTableBody">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 user-row" 
                    data-name="{{ strtolower($user->name) }}" 
                    data-email="{{ strtolower($user->email) }}"
                    data-id="{{ $user->id }}">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <input type="checkbox" class="user-checkbox rounded border-gray-300 text-[#1a2c3e] focus:ring-[#1a2c3e]" 
                               data-user-id="{{ $user->id }}"
                               data-user-status="{{ $user->status ?? 'active' }}">
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white">
                        #{{ $user->id }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($user->profile_photo_path)
                            <img class="w-10 h-10 rounded-full object-cover border-2 border-[#1a2c3e] shadow-sm" 
                                 src="{{ Storage::url($user->profile_photo_path) }}" 
                                 alt="{{ $user->name }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-[#1a2c3e]/10 dark:bg-[#1a2c3e]/20 flex items-center justify-center border-2 border-[#1a2c3e] shadow-sm">
                                <span class="text-sm font-semibold text-[#1a2c3e] dark:text-[#1a2c3e]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-xs font-medium text-gray-800 dark:text-gray-200">
                            {{ $user->name }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if(($user->status ?? 'active') === 'active')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <i class="bi bi-check-circle-fill mr-1 text-xs"></i> Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                <i class="bi bi-x-circle-fill mr-1 text-xs"></i> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                        <i class="bi bi-calendar3 mr-1"></i>
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if(($user->status ?? 'active') === 'inactive')
                            <button onclick="deleteUser({{ $user->id }})" 
                                    class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition duration-200 inline-flex items-center gap-1 text-xs">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 text-xs inline-flex items-center gap-1">
                                <i class="bi bi-lock"></i> Cannot delete
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-people text-5xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No users found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>