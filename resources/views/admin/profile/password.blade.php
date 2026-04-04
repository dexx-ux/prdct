<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Change Password') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Update Password</h3>
                    
                    {{-- Back Link --}}
                    <div class="mb-6">
                        <a href="{{ route('admin.profile.edit') }}" 
                           class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-[#1a2c3e] dark:hover:text-[#1a2c3e] transition-colors duration-200">
                            <i class="bi bi-arrow-left"></i>
                            Back to Profile
                        </a>
                    </div>

                    {{-- Success Message --}}
                    @if (session('password_status'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-md">
                            {{ session('password_status') }}
                        </div>
                    @endif

                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.profile.password.update') }}" id="passwordForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                            <input type="password" name="current_password" id="current_password" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]">
                            @error('current_password') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Password must be at least 8 characters long.</p>
                            
                            {{-- Password Warning --}}
                            <div id="passwordWarning" class="mt-2 hidden">
                                <p class="text-sm text-red-600 dark:text-red-400">Password must be at least 8 characters long!</p>
                            </div>
                            @error('password') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]">
                            <div id="confirmWarning" class="mt-2 hidden">
                                <p class="text-sm text-red-600 dark:text-red-400">Passwords do not match!</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" id="submitBtn" disabled
                                    class="px-6 py-2 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-medium rounded-md cursor-not-allowed transition-all duration-200 flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle"></i>
                                Update Password
                            </button>
                            
                            <a href="{{ route('admin.profile.edit') }}" 
                               class="px-6 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                <i class="bi bi-x-circle mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');
        const passwordWarning = document.getElementById('passwordWarning');
        const confirmWarning = document.getElementById('confirmWarning');
        
        function validatePassword() {
            return password.value.length >= 8;
        }
        
        function validateConfirm() {
            const pass = password.value;
            const confirm = confirmPassword.value;
            
            if (confirm.length > 0 && pass !== confirm) {
                confirmWarning.classList.remove('hidden');
                return false;
            } else {
                confirmWarning.classList.add('hidden');
                return true;
            }
        }
        
        function enableSubmit() {
            const isPasswordValid = validatePassword();
            const isConfirmValid = validateConfirm();
            
            // Show warning if password is less than 8 characters
            if (password.value.length > 0 && password.value.length < 8) {
                passwordWarning.classList.remove('hidden');
            } else {
                passwordWarning.classList.add('hidden');
            }
            
            if (isPasswordValid && isConfirmValid && password.value.length > 0) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-300', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-[#1a2c3e]', 'hover:bg-[#0f1e2c]', 'text-white', 'cursor-pointer');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-gray-300', 'dark:bg-gray-700', 'text-gray-500', 'dark:text-gray-400', 'cursor-not-allowed');
                submitBtn.classList.remove('bg-[#1a2c3e]', 'hover:bg-[#0f1e2c]', 'text-white', 'cursor-pointer');
            }
        }
        
        password.addEventListener('input', () => {
            enableSubmit();
        });
        
        confirmPassword.addEventListener('input', () => {
            enableSubmit();
        });
        
        // Initial validation
        enableSubmit();
    </script>
</x-app-layout>