<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Orders Management
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="w-full px-6 py-8">
        <!-- Statistics Section -->
        @include('admin.orders.partials.statistics')

        <!-- Filters Section -->
        @include('admin.orders.partials.filters')

        <!-- Orders Table Section -->
    
    </div>

    <!-- Modals -->
    @include('admin.orders.partials.modal')

    <!-- Scripts -->
    @include('admin.orders.partials.scripts')
</x-app-layout>