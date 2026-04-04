{{-- resources/views/admin/products/partials/search-bar.blade.php --}}
<div class="mb-6 flex flex-col md:flex-row gap-4 items-center">
    <!-- Search Input -->
    <div class="relative flex-1">
        <input type="text" 
               id="searchInput" 
               placeholder="Search by name or description..." 
               class="w-full px-4 py-2 pl-10 pr-4 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#a30000] focus:border-transparent">
        <i class="bi bi-search absolute left-3 top-3 text-gray-400"></i>
    </div>

    <!-- Category Filter -->
    <div class="w-full md:w-40">
        <select id="categoryFilter" 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#a30000] focus:border-transparent cursor-pointer text-sm">
            <option value="">All Categories</option>
        </select>
    </div>

    <!-- Stock Filter Dropdown -->
    <div class="w-full md:w-48">
        <select id="filterSelect" 
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#a30000] focus:border-transparent cursor-pointer">
            <option value="all">All Stock Status</option>
            <option value="in-stock">In Stock</option>
            <option value="low-stock">Low Stock (1-10)</option>
            <option value="out-of-stock">Out of Stock</option>
        </select>
    </div>

    <!-- Reset Filters Button - Normal Size -->
    <button id="clearFiltersBtn"
            class="px-6 py-2 bg-[#1a2c3e] hover:bg-[#0f1e2c] text-white rounded-lg transition font-medium text-sm whitespace-nowrap">
        <i class="bi bi-arrow-counterclockwise mr-1"></i>
        Reset
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetBtn = document.getElementById('clearFiltersBtn');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const stockFilter = document.getElementById('filterSelect');
    
    // Function to reset all filters
    function resetAllFilters() {
        // Clear search input
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
        
        // Reset category filter
        if (categoryFilter) {
            categoryFilter.value = '';
            categoryFilter.dispatchEvent(new Event('change'));
        }
        
        // Reset stock filter
        if (stockFilter) {
            stockFilter.value = 'all';
            stockFilter.dispatchEvent(new Event('change'));
        }
        
        // Call applyFilters function if it exists
        if (typeof applyFilters === 'function') {
            applyFilters();
        }
        
        // Clear table search if exists
        const searchInputInTable = document.getElementById('productSearchInput');
        if (searchInputInTable) {
            searchInputInTable.value = '';
            searchInputInTable.dispatchEvent(new Event('input'));
        }
    }
    
    // Add click event to reset button
    if (resetBtn) {
        resetBtn.addEventListener('click', resetAllFilters);
    }
});
</script>