@props(['products'])

<div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg">
    <!-- Header with Select All and Bulk Delete -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" id="selectAllProducts" 
                           class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2 cursor-pointer transition"
                           onchange="toggleSelectAllProducts(this)">
                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Select All</span>
                </label>
                <span id="selectedCount" class="text-xs text-gray-500 dark:text-gray-400 hidden bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-full">
                    <i class="fas fa-check-circle text-blue-500 text-xs mr-1"></i>
                    <span>0</span> selected
                </span>
            </div>
            <div class="flex gap-3">
                <button id="bulkDeleteBtn" 
                        onclick="confirmBulkDelete()"
                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium text-sm flex items-center gap-1">
                    <i class="fas fa-trash-alt text-xs"></i>
                    Delete Selected (<span id="bulkDeleteCount">0</span>)
                </button>
                <button type="button" onclick="openCreateModal()" 
                        class="px-3 py-1.5 rounded-lg transition font-medium text-sm flex items-center gap-1"
                        style="background: #3b82f6; color: white;">
                    <i class="fas fa-plus text-xs"></i>
                    Add Product
                </button>
            </div>
        </div>
    </div>

    <!-- Search Bar with Clear Filter Button -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" 
                       id="productSearchInput" 
                       placeholder="Search products by name, description..." 
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button id="clearFiltersBtn"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition font-medium text-sm flex items-center gap-2">
                <i class="fas fa-times-circle text-sm"></i>
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto" style="max-height: 500px; overflow-y: auto;">
        <table class="w-full table-auto min-w-[800px]">
            <!-- Head -->
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 text-left w-10">
                        <input type="checkbox" id="selectAllHeader" 
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                               onchange="toggleSelectAllProducts(this)">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Product</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Discount</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            
            <!-- Body -->
            <tbody id="productsTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($products as $product)
                    <tr class="product-row hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ strtolower($product->name) }}"
                        data-product-description="{{ strtolower($product->description ?? '') }}">
                        <td class="px-4 py-3 text-left">
                            <input type="checkbox" class="product-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" 
                                   value="{{ $product->id }}" onchange="updateBulkDeleteButton()">
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white">
                            #{{ $product->id }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            <div class="flex items-center gap-2">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-8 h-8 rounded object-cover">
                                @else
                                    <div class="w-8 h-8 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400 text-xs"></i>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-800 dark:text-gray-200">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate">
                            {{ Str::limit($product->description ?? 'No description', 50) }}
                        </td>
                        <td class="px-4 py-3 text-center text-xs">
                            @php
                                $stock = $product->stock ?? 0;
                                $stockClass = $stock > 10 ? 'text-green-600 dark:text-green-400' : ($stock > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400');
                            @endphp
                            <span class="font-semibold {{ $stockClass }}">{{ $stock }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs font-semibold text-gray-900 dark:text-white">
                            ₱{{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            @if($product->discount > 0)
                                <span class="text-green-600 dark:text-green-400 font-medium">-{{ $product->discount }}%</span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex gap-1.5 justify-center">
                                <button onclick="openEditModal({{ $product->id }})" 
                                        title="Edit product"
                                        class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition p-1">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $product->id }})" 
                                        title="Delete product"
                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition p-1">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyStateRow">
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <i class="fas fa-box-open text-4xl text-gray-400"></i>
                                <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                    No products available
                                </p>
                                <button type="button"
                                        onclick="openCreateModal()"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg"
                                        style="background: #3b82f6; color: white;">
                                    <i class="fas fa-plus text-xs"></i>
                                    Add your first product
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Bulk Delete Confirmation Modal --}}
<div id="bulkDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="relative top-20 mx-auto p-5 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-3"></i>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Bulk Delete</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Are you sure you want to delete <span id="bulkDeleteConfirmCount" class="text-red-600 font-bold">0</span> product(s)?
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This action cannot be undone.</p>

            <div class="flex justify-center gap-3 mt-4">
                <button onclick="closeBulkDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button onclick="confirmBulkDeleteAction()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete All
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form for bulk delete --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.products.bulk-delete') }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="product_ids" id="bulkProductIds">
</form>

<script>
let selectedProductIds = [];

function toggleSelectAllProducts(checkbox) {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const isChecked = checkbox.checked;
    
    checkboxes.forEach(cb => {
        cb.checked = isChecked;
    });
    
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');
    const countSpan = selectedCountSpan?.querySelector('span');
    const bulkDeleteCount = document.getElementById('bulkDeleteCount');
    const selectAllHeader = document.getElementById('selectAllHeader');
    const selectAllProducts = document.getElementById('selectAllProducts');
    
    const count = checkboxes.length;
    
    // Update selected products array
    selectedProductIds = Array.from(checkboxes).map(cb => cb.value);
    
    // Show/hide bulk delete button
    if (count > 0 && bulkBtn) {
        bulkBtn.classList.remove('hidden');
        if (bulkDeleteCount) bulkDeleteCount.textContent = count;
    } else if (bulkBtn) {
        bulkBtn.classList.add('hidden');
    }
    
    // Update selected count display with badge styling
    if (selectedCountSpan) {
        if (count > 0) {
            selectedCountSpan.classList.remove('hidden');
            if (countSpan) countSpan.textContent = count;
        } else {
            selectedCountSpan.classList.add('hidden');
        }
    }
    
    // Update select all checkboxes state
    const totalCheckboxes = document.querySelectorAll('.product-checkbox').length;
    const allChecked = totalCheckboxes > 0 && count === totalCheckboxes;
    
    if (selectAllHeader) selectAllHeader.checked = allChecked;
    if (selectAllProducts) selectAllProducts.checked = allChecked;
}

function confirmBulkDelete() {
    if (selectedProductIds.length === 0) {
        alert('Please select at least one product to delete.');
        return;
    }
    
    const confirmCount = document.getElementById('bulkDeleteConfirmCount');
    if (confirmCount) confirmCount.textContent = selectedProductIds.length;
    
    document.getElementById('bulkDeleteModal').classList.remove('hidden');
}

function closeBulkDeleteModal() {
    document.getElementById('bulkDeleteModal').classList.add('hidden');
}

function confirmBulkDeleteAction() {
    if (selectedProductIds.length === 0) return;
    
    document.getElementById('bulkProductIds').value = selectedProductIds.join(',');
    document.getElementById('bulkDeleteForm').submit();
}

// Product Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('productSearchInput');
    const clearFiltersBtn = document.getElementById('clearFiltersBtn');
    
    if (!searchInput) return;
    
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.product-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const productName = row.getAttribute('data-product-name') || '';
            const productDesc = row.getAttribute('data-product-description') || '';
            
            const matchesSearch = searchTerm === '' || 
                                 productName.includes(searchTerm) || 
                                 productDesc.includes(searchTerm);
            
            if (matchesSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide empty state message for search
        const emptyStateRow = document.getElementById('emptyStateRow');
        const tbody = document.getElementById('productsTableBody');
        
        // Remove existing no results row if any
        const existingNoResultsRow = document.getElementById('noSearchResultsRow');
        if (existingNoResultsRow) existingNoResultsRow.remove();
        
        if (visibleCount === 0 && rows.length > 0) {
            const noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noSearchResultsRow';
            noResultsRow.innerHTML = `
                <td colspan="8" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No products match "${searchTerm}"</p>
                        <button onclick="clearAllFilters()" 
                                class="text-xs text-blue-600 hover:underline">
                            Clear search
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(noResultsRow);
        }
    }
    
    // Clear all filters function
    window.clearAllFilters = function() {
        if (searchInput) {
            searchInput.value = '';
        }
        filterProducts();
        
        // Also uncheck all checkboxes if needed
        const selectAllHeader = document.getElementById('selectAllHeader');
        const selectAllProducts = document.getElementById('selectAllProducts');
        if (selectAllHeader) selectAllHeader.checked = false;
        if (selectAllProducts) selectAllProducts.checked = false;
        
        // Uncheck all product checkboxes
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        updateBulkDeleteButton();
    };
    
    // Clear filters button click handler
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            clearAllFilters();
        });
    }
    
    // Debounced search
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterProducts, 300);
    });
    
    window.filterProducts = filterProducts;
    
    // Initialize checkboxes after AJAX reloads
    const observer = new MutationObserver(function() {
        document.querySelectorAll('.product-checkbox').forEach(cb => {
            cb.removeEventListener('change', updateBulkDeleteButton);
            cb.addEventListener('change', updateBulkDeleteButton);
        });
    });
    
    const productsTableBody = document.getElementById('productsTableBody');
    if (productsTableBody) {
        observer.observe(productsTableBody, { childList: true, subtree: true });
    }
});
</script>

<style>
    /* Custom checkbox styling - Blue theme */
    input[type="checkbox"] {
        accent-color: #3b82f6;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    input[type="checkbox"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    /* Smooth transitions */
    .product-row {
        transition: background-color 0.2s ease;
    }
    
    /* Scrollbar styling */
    .overflow-x-auto::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: #1f2937;
    }
    
    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
    
    /* Focus ring for inputs */
    input:focus, select:focus {
        outline: none;
    }
    
    /* Selected count badge animation */
    #selectedCount {
        transition: all 0.2s ease;
    }
    
    /* Button hover effects */
    .transition {
        transition: all 0.2s ease;
    }
    
    /* Clear button hover effect */
    #clearFiltersBtn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>