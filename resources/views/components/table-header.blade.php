@props([
    'searchPlaceholder' => 'Search products...',
    'hasProducts' => true,
    'products'
])

<div class="bg-white dark:bg-gray-800
            rounded-lg shadow-sm
            border border-gray-200 dark:border-gray-700
            p-4 mb-4 space-y-4">

    <!-- TOP ROW -->
    <div class="flex flex-wrap items-center justify-between gap-4">

        <!-- LEFT: Add Product Button (opens create modal) -->
        <button type="button"
                onclick="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                       text-gray-700 dark:text-gray-200
                       bg-gray-100 dark:bg-gray-700
                       border border-gray-300 dark:border-gray-600
                       rounded-md
                       hover:bg-gray-200 dark:hover:bg-gray-600
                       transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </button>

        <div class="relative w-full flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>

            <input
                id="searchInput"
                type="text"
                placeholder="{{ $hasProducts ? $searchPlaceholder : 'No products to search' }}"
                {{ $hasProducts ? '' : 'disabled' }}
                class="w-full pl-10 pr-10 py-2.5 text-sm
                       border border-gray-300 dark:border-gray-600
                       rounded-lg
                       bg-gray-50 dark:bg-gray-700
                       text-gray-900 dark:text-gray-100
                       placeholder-gray-500 dark:placeholder-gray-400
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                       transition {{ $hasProducts ? '' : 'opacity-50 cursor-not-allowed' }}"
            />

            <!-- Clear Button -->
            <button id="clearSearchBtn"
                    type="button"
                    class="absolute inset-y-0 right-3 hidden
                           text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- RIGHT: Filters -->
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Product Count -->
            <span class="text-sm text-gray-700 dark:text-gray-300">
                Total Product: <strong>{{ $products->count() }}</strong>
            </span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearchBtn');

    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const term = this.value;

        window.dispatchEvent(new CustomEvent('product-search', {
            detail: { searchTerm: term }
        }));

        if (clearBtn) {
            if (term.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('hidden');

            window.dispatchEvent(new CustomEvent('product-search', {
                detail: { searchTerm: '' }
            }));

            searchInput.focus();
        });
    }
});
</script>