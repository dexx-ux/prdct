<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card Component -->
            @include('user.components.welcome-card')

            <!-- Dashboard Stats Component -->
            @include('user.components.stats-cards')

            <!-- Quick Links Component -->
            @include('user.components.quick-links')

            <!-- Recent Orders Component -->
            @include('user.components.recent-orders')

            <!-- Account Information Component -->
            @include('user.components.account-info')
        </div>
    </div>

    <!-- Dashboard Scripts -->
    @include('user.components.dashboard-scripts')
</x-app-layout>
