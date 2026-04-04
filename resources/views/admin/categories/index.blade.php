<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Categories Management
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="w-full px-6 py-8">
        <!-- Statistics Cards (3 containers) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Total Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Categories</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalCategories ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                        <i class="bi bi-tags text-lg text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Products</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalProductsInCategories ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i class="bi bi-box text-lg text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <!-- Avg Products/Category -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Avg Products/Category</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $averageProductsPerCategory ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <i class="bi bi-bar-chart text-lg text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Title and Add Button Row - AFTER the 3 containers -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="bi bi-tags mr-2 text-[#1a2c3e]"></i>
                Categories List
            </h1>
            
            <button type="button" onclick="openCreateCategoryModal()" 
               class="bg-[#1a2c3e] hover:bg-[#0f1e2c] text-white px-5 py-2.5 rounded-lg transition duration-200 flex items-center gap-2 shadow-md">
                <i class="bi bi-plus-circle"></i>
                Add New Category
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" 
                       id="categorySearch" 
                       placeholder="Search categories by name or description..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a2c3e]">
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Category Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Description
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                Products
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="categoriesTableBody">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 category-row" 
                            data-name="{{ strtolower($category->name) }}"
                            data-description="{{ strtolower($category->description ?? '') }}">
                            <td class="px-6 py-3 whitespace-nowrap text-xs text-gray-900 dark:text-gray-300">
                                #{{ $category->id }}
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $category->name }}
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 max-w-xs">
                                    {{ $category->description ?? '—' }}
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                    {{ $category->products_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm">
                                <div class="flex gap-3 justify-center">
                                    <button onclick="openEditCategoryModal({{ $category->id }})" 
                                            title="Edit category"
                                            class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition p-1">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button onclick="openDeleteCategoryModal({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                            title="Delete category"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition p-1">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-folder-x text-5xl text-gray-400 mb-3"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No categories found</p>
                                    <button onclick="openCreateCategoryModal()" 
                                        class="mt-3 text-[#1a2c3e] hover:text-[#0f1e2c] text-sm font-medium flex items-center gap-1">
                                        <i class="bi bi-plus-circle"></i> Create your first category
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE MODAL -->
    @include('admin.categories.create-modal')

    <!-- EDIT MODAL -->
    @include('admin.categories.edit-modal')

    <!-- DELETE MODAL -->
    @include('admin.categories.delete-modal')

    <script>
        // Category Search Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('categorySearch');
            const tableBody = document.getElementById('categoriesTableBody');
            const rows = tableBody.querySelectorAll('tr.category-row');

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase().trim();

                    rows.forEach(row => {
                        const name = row.dataset.name || '';
                        const description = row.dataset.description || '';

                        const matches = name.includes(searchTerm) || description.includes(searchTerm);

                        if (searchTerm === '' || matches) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                    const emptyMessage = tableBody.querySelector('tr:not(.category-row)');
                    
                    if (visibleRows.length === 0 && searchTerm !== '') {
                        if (emptyMessage) {
                            emptyMessage.style.display = 'none';
                        }
                        const noResultsRow = document.getElementById('noSearchResults');
                        if (!noResultsRow) {
                            const newRow = document.createElement('tr');
                            newRow.id = 'noSearchResults';
                            newRow.innerHTML = `
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="bi bi-search text-5xl text-gray-400 mb-3"></i>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm">No categories match your search</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">Try a different keyword</p>
                                    </div>
                                </td>
                            `;
                            tableBody.appendChild(newRow);
                        }
                    } else {
                        const noResults = document.getElementById('noSearchResults');
                        if (noResults) {
                            noResults.remove();
                        }
                        if (emptyMessage && visibleRows.length === 0) {
                            emptyMessage.style.display = '';
                        }
                    }
                });
            }
        });
    </script>

</x-app-layout>