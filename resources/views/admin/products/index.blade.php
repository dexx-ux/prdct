<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Products Management
                </h2>
            </div>
        </div>
    </x-slot>

    {{-- ✅ FULL WIDTH FIX --}}
    <div class="w-full px-6 py-8">

        {{-- Stats Cards --}}
        @include('admin.products.partials.stats-cards', ['products' => $products, 'allProducts' => $allProducts])

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-box mr-2" style="color: #1a2c3e;"></i>
                Products List
            </h1>
            
            <div class="flex gap-3">
                <button id="deleteSelectedBtn" 
                        class="hidden bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200"
                        onclick="showDeleteModal()">
                    <i class="fas fa-trash mr-2"></i>Delete Selected
                </button>
                <button type="button" onclick="openCreateModal()" 
                   class="px-4 py-2 rounded-lg transition duration-200"
                   style="background: #1a2c3e; color: white; hover:background: #0f1e2c;">
                    <i class="fas fa-plus mr-2"></i>Add Product
                </button>
            </div>
        </div>

        {{-- Search --}}
        @include('admin.products.partials.search-bar')

        {{-- ✅ TABLE WRAPPER FIX --}}
        <div class="mb-6">
            @include('admin.products.partials.products-table', ['products' => $products])
        </div>

        @if(isset($products) && method_exists($products, 'links'))
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Delete</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Delete <span id="deleteCount" class="text-red-600 font-bold">0</span> product(s)?
                </p>

                <div class="flex justify-center gap-3 mt-4">
                    <button onclick="closeBulkDeleteModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-700 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button onclick="confirmDeleteSelected()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ REAL FORM --}}
    <form id="deleteSelectedForm" method="POST" action="{{ route('admin.products.delete-selected') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="product_ids" id="product_ids">
    </form>

    {{-- INCLUDE ALL MODALS --}}
    @include('admin.products.create-modal')
    @include('admin.products.edit-modal')
    @include('admin.products.delete-modal')

</x-app-layout>

{{-- Additional Styles to match guest layout --}}
<style>
    /* Match guest layout color scheme */
    .stats-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .stats-card:hover {
        transform: translateY(-3px);
    }
    .stats-card i {
        color: #1a2c3e;
    }
    
    /* Table styling */
    .products-table {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .products-table th {
        background: #f8f9fa;
        color: #1a2c3e;
        font-weight: 600;
    }
    .products-table td {
        vertical-align: middle;
    }
    
    /* Button and input focus states */
    input:focus, select:focus, button:focus {
        outline: none;
        ring: 2px solid #1a2c3e;
    }
    
    /* Search bar styling */
    .search-wrapper {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .search-wrapper input, .search-wrapper select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    .search-wrapper input:focus, .search-wrapper select:focus {
        border-color: #1a2c3e;
        box-shadow: 0 0 0 2px rgba(26, 44, 62, 0.1);
    }
    
    /* Pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    .pagination .page-item.active .page-link {
        background: #1a2c3e;
        border-color: #1a2c3e;
    }
    .pagination .page-link {
        color: #1a2c3e;
        border-radius: 8px;
    }
    .pagination .page-link:hover {
        background: #f0f2f5;
        color: #0f1e2c;
    }
    
    /* Modal styling */
    .modal-content {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        background: #f8f9fa;
        border-radius: 10px 10px 0 0;
    }
    .modal-footer {
        border-top: 1px solid #e2e8f0;
    }
    .btn-primary {
        background: #1a2c3e;
        border: none;
    }
    .btn-primary:hover {
        background: #0f1e2c;
    }
    
    /* Status badges */
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-warning {
        background: #fed7aa;
        color: #92400e;
    }
    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Action buttons */
    .action-btn {
        padding: 0.35rem 0.7rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
    .edit-btn {
        background: #e0e7ff;
        color: #1e3a8a;
    }
    .edit-btn:hover {
        background: #c7d2fe;
    }
    .delete-btn {
        background: #fee2e2;
        color: #991b1b;
    }
    .delete-btn:hover {
        background: #fecaca;
    }
</style>

<script>
let pendingSelectedProducts = [];

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('deleteSelectedForm');
    if (!form) {
        console.error('Error: Delete form not found in DOM');
    }

    const input = document.getElementById('product_ids');
    if (!input) {
        console.error('Error: Hidden product_ids input not found in DOM');
    }

    initializeSelectAll();
    initializeCheckboxes();
    initializeSearch();
    initializeFilter();
    initializeCategoryFilter();
    updateDeleteButton();

    console.log('Delete functionality initialized');
});

/* ===============================
   SELECT ALL FUNCTION
================================ */
function initializeSelectAll() {
    const selectAll = document.getElementById('selectAll');

    if (!selectAll) return;

    selectAll.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.product-checkbox');

        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });

        updateDeleteButton();
    });
}

/* ===============================
   INDIVIDUAL CHECKBOX
================================ */
function initializeCheckboxes() {
    document.querySelectorAll('.product-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            updateSelectAllState();
            updateDeleteButton();
        });
    });
}

/* ===============================
   UPDATE SELECT ALL STATE
================================ */
function updateSelectAllState() {
    const all = document.querySelectorAll('.product-checkbox');
    const checked = document.querySelectorAll('.product-checkbox:checked');
    const selectAll = document.getElementById('selectAll');

    if (!selectAll) return;

    selectAll.checked = all.length > 0 && all.length === checked.length;
}

/* ===============================
   TOGGLE DELETE BUTTON
================================ */
function updateDeleteButton() {
    const btn = document.getElementById('deleteSelectedBtn');
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');

    btn.classList.toggle('hidden', checkboxes.length === 0);
}

/* ===============================
   SHOW DELETE MODAL
================================ */
function showDeleteModal() {
    const checkboxes = document.querySelectorAll('.product-checkbox:checked');
    const count = checkboxes.length;

    if (count === 0) {
        alert('Please select at least one product');
        return;
    }

    pendingSelectedProducts = Array.from(checkboxes).map(cb => cb.dataset.productId);
    document.getElementById('deleteCount').textContent = count;
    document.getElementById('deleteModal').classList.remove('hidden');
}

/* ===============================
   CLOSE DELETE MODAL
================================ */
function closeBulkDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    pendingSelectedProducts = [];
}

/* ===============================
   CONFIRM DELETE SELECTED
================================ */
function confirmDeleteSelected() {
    if (pendingSelectedProducts.length === 0) return;

    document.getElementById('product_ids').value = pendingSelectedProducts.join(',');
    document.getElementById('deleteSelectedForm').submit();
}

/* ===============================
   SEARCH FUNCTIONALITY
================================ */
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function (e) {
        applyFilters();
    });
}

/* ===============================
   FILTER FUNCTIONALITY
================================ */
function initializeFilter() {
    const filterSelect = document.getElementById('filterSelect');
    if (!filterSelect) return;

    filterSelect.addEventListener('change', function (e) {
        applyFilters();
    });
}

/* ===============================
   CATEGORY FILTER FUNCTIONALITY
================================ */
function initializeCategoryFilter() {
    const categoryFilter = document.getElementById('categoryFilter');
    if (!categoryFilter) return;

    // Load categories from API
    fetch('/admin/categories/list', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(categories => {
        // Add category options
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categoryFilter.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error loading categories:', error);
    });

    // Add event listener for category filter changes
    categoryFilter.addEventListener('change', function (e) {
        applyFilters();
    });
}


/* ===============================
   APPLY BOTH SEARCH AND FILTER
================================ */
function applyFilters() {
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');
    const categoryFilter = document.getElementById('categoryFilter');
    const searchTerm = searchInput.value.toLowerCase().trim();
    const filterValue = filterSelect.value;
    const categoryId = categoryFilter.value;
    const rows = document.querySelectorAll('.product-row');

    rows.forEach(row => {
        const name = row.dataset.name || '';
        const description = row.dataset.description || '';
        const stock = parseInt(row.dataset.stock) || 0;
        const productCategoryId = row.dataset.categoryId || '';
        
        // Check search criteria
        const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
        
        // Check stock filter criteria
        let matchesFilter = true;
        if (filterValue === 'in-stock') {
            matchesFilter = stock > 10;
        } else if (filterValue === 'low-stock') {
            matchesFilter = stock > 0 && stock <= 10;
        } else if (filterValue === 'out-of-stock') {
            matchesFilter = stock === 0;
        }
        // 'all' shows everything
        
        // Check category filter criteria
        let matchesCategory = true;
        if (categoryId !== '') {
            matchesCategory = productCategoryId === categoryId;
        }
        // empty value shows all categories
        
        // Show row only if search, stock filter, AND category filter match
        row.style.display = (matchesSearch && matchesFilter && matchesCategory) ? '' : 'none';
    });
}
</script>