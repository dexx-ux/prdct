<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Success Message --}}
            @if (session('status') === 'profile-updated' || session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Profile updated successfully!</span>
                        </div>
                        <button onclick="this.closest('[role=alert]').remove()" class="text-green-700 dark:text-green-400 hover:text-green-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @if (session('status') === 'photo-updated')
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Profile photo updated successfully!</span>
                        </div>
                        <button onclick="this.closest('[role=alert]').remove()" class="text-green-700 dark:text-green-400 hover:text-green-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            @if (session('status') === 'photo-removed')
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium">Profile picture removed successfully!</span>
                        </div>
                        <button onclick="this.closest('[role=alert]').remove()" class="text-green-700 dark:text-green-400 hover:text-green-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            
            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 rounded-lg shadow-md" role="alert">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left Column --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Profile Picture Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Profile Picture</h3>
                            
                            <div class="flex flex-col items-center">
                                @php $initial = strtoupper(substr(Auth::user()->name, 0, 1)); @endphp
                                
                                @if(Auth::user()->profile_photo_path)
                                    <img class="h-28 w-28 rounded-full object-cover border-2 border-[#1a2c3e]" 
                                         src="{{ Storage::url(Auth::user()->profile_photo_path) }}"
                                         alt="Profile Picture">
                                @else
                                    <div class="h-28 w-28 rounded-full bg-[#1a2c3e]/10 dark:bg-[#1a2c3e]/20 flex items-center justify-center border-2 border-[#1a2c3e]">
                                        <span class="text-3xl font-semibold text-[#1a2c3e] dark:text-[#1a2c3e]">{{ $initial }}</span>
                                    </div>
                                @endif
                                
                                <div class="mt-4 w-full">
                                    <form method="POST" action="{{ route('user.profile.upload-photo') }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload New Picture</label>
                                        <input type="file" name="profile_photo" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                            file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                            file:text-sm file:font-semibold file:bg-[#1a2c3e]/10 file:text-[#1a2c3e] dark:file:bg-[#1a2c3e]/20 dark:file:text-[#1a2c3e]
                                            hover:file:bg-[#1a2c3e]/20 dark:hover:file:bg-[#1a2c3e]/30 cursor-pointer">                                        
                                        <button type="submit" class="mt-3 w-full px-4 py-2 bg-[#1a2c3e] hover:bg-[#0f1e2c] text-white text-sm rounded-md transition duration-200">
                                            Upload Picture
                                        </button>
                                    </form>
                                    
                                    @if(Auth::user()->profile_photo_path)
                                    <button type="button" 
                                            onclick="openModal()" 
                                            class="mt-2 w-full px-4 py-2 border border-red-500 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 text-sm rounded-md transition duration-200">
                                        Remove Picture
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Account Information Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Account Information</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Member Since</span>
                                    <span class="text-sm text-gray-800 dark:text-gray-200">{{ Auth::user()->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Last Updated</span>
                                    <span class="text-sm text-gray-800 dark:text-gray-200">{{ Auth::user()->updated_at->format('F d, Y') }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Email Status</span>
                                    <span class="text-sm {{ Auth::user()->email_verified_at ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ Auth::user()->email_verified_at ? 'Verified' : 'Not Verified' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Right Column --}}
                <div class="lg:col-span-2">
                    {{-- Profile Information Card --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Profile Information</h3>
                            
                            <form method="POST" action="{{ route('user.profile.update') }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="space-y-5">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]">
                                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]">
                                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                        <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]"
                                               placeholder="+63 (XXX) XXX-XXXX">
                                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delivery Address</label>
                                        <textarea name="address" rows="4" 
                                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-[#1a2c3e]"
                                                  placeholder="House/Unit #, Street, Barangay, City, Province, Zip Code">{{ old('address', Auth::user()->address) }}</textarea>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Please provide your complete delivery address for accurate order delivery</p>
                                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                                        <input type="text" value="{{ ucfirst(Auth::user()->role) }}" disabled 
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                    </div>
                                </div>
                                
                                <div class="mt-8 flex gap-4">
                                    <button type="submit" class="px-6 py-2 bg-[#1a2c3e] hover:bg-[#0f1e2c] text-white rounded-md transition duration-200">
                                        Update Profile
                                    </button>
                                    <a href="{{ route('user.profile.password') }}" 
                                       class="px-6 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                        Change Password
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="removeModal" class="fixed inset-0 hidden items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Remove Profile Picture</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to remove your profile picture?</p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition">
                        Cancel
                    </button>
                    <form method="POST" action="{{ route('user.profile.remove-photo') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('removeModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            document.getElementById('removeModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        document.getElementById('removeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</x-app-layout>