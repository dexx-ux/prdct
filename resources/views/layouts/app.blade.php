<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    
    <!-- Bootstrap Icons (required for sidebar) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js for sidebar functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>

<body class="font-sans antialiased">

<div class="flex h-screen bg-gray-100 dark:bg-gray-900 overflow-hidden" 
      x-data="{ 
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' 
      }"
      x-init="
        // Watch for changes to sidebarCollapsed
        $watch('sidebarCollapsed', value => {
            localStorage.setItem('sidebarCollapsed', value);
            // Dispatch event for sidebar component to react
            window.dispatchEvent(new CustomEvent('sidebar-toggle', { detail: { collapsed: value } }));
        });
        
        // Listen for sidebar toggle events from the sidebar component
        window.addEventListener('sidebar-toggle', (event) => {
            if (event.detail.collapsed !== undefined && event.detail.collapsed !== sidebarCollapsed) {
                sidebarCollapsed = event.detail.collapsed;
            }
        });
        
        // Initialize sidebar visibility
        window.dispatchEvent(new CustomEvent('sidebar-toggle', { detail: { collapsed: sidebarCollapsed } }));
      ">

    {{-- Sidebar - Role based --}}
    @auth
        @if(Auth::user()->role === 'admin')
            @include('layouts.admin.sidebar')
        @else
            @include('layouts.users.sidebar')
        @endif
    @endauth

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col transition-all duration-300"
         :style="{ marginLeft: sidebarCollapsed ? '85px' : '256px' }"
         style="margin-left: 256px;">

        {{-- Mobile Menu Button (only visible on mobile) --}}
        <button class="md:hidden fixed top-4 left-4 z-50 w-10 h-10 flex items-center justify-center rounded-lg bg-[#a30000] text-white shadow-lg hover:bg-[#7a0000] transition-all duration-300"
                @click="sidebarCollapsed = false; document.getElementById('default-sidebar').style.transform = 'translateX(0)'"
                x-show="sidebarCollapsed && window.innerWidth < 768"
                x-cloak>
            <i class="bi bi-list text-2xl"></i>
        </button>

        {{-- Page Header --}}
        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="px-6 py-6">
                {{ $header }}
            </div>
        </header>
        @endisset

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot ?? '' }}
        </main>

    </div>

</div>

<script>
// Add responsive behavior
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        const sidebar = document.getElementById('default-sidebar');
        if (sidebar) sidebar.style.transform = '';
    }
});
</script>

@stack('scripts')
@stack('modals')

</body>
</html>