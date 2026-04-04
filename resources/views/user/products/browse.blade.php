<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Shop Products</h1>
                <p class="text-gray-600 dark:text-gray-400">Browse and purchase from our catalog</p>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <div class="space-y-4">
                    <!-- Search Bar -->
                    <div>
                        <input type="text" id="searchInput" placeholder="Search products..." 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select id="categoryFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                            <select id="sortFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                                <option value="newest">Newest</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Price</label>
                            <input type="number" id="priceFilter" placeholder="Maximum price" min="0" step="100"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @forelse($products as $product)
                    @php
                        $discount = (float)($product->discount_value ?? 0);
                        $finalPrice = $product->price - $discount;
                        $categoryId = $product->category ? $product->category->id : '';
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col product-card" 
                        data-product-id="{{ $product->id }}" 
                        data-category-id="{{ $categoryId }}"
                        data-price="{{ $finalPrice }}"
                        data-name="{{ $product->name }}">
                        <!-- Product Image Placeholder -->
                        <div class="h-48 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <!-- Product Details -->
                        <div class="p-6 flex flex-col flex-grow">
                            @if($product->category)
                                <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase mb-2">{{ $product->category->name }}</p>
                            @endif
                            
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h3>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex-grow">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <!-- Pricing -->
                            <div class="mb-4">
                                @if($discount > 0)
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm line-through text-gray-500 dark:text-gray-400">₱{{ number_format($product->price, 2) }}</span>
                                        <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">
                                            Save ₱{{ number_format($discount, 2) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    ₱{{ number_format($finalPrice, 2) }}
                                </p>
                            </div>

                            <!-- Stock Status -->
                            <div class="mb-4">
                                @if($product->quantity > 5)
                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">✓ In Stock ({{ $product->quantity }})</span>
                                @elseif($product->quantity > 0)
                                    <span class="text-xs font-semibold text-orange-600 dark:text-orange-400">⚠ Low Stock ({{ $product->quantity }} left)</span>
                                @else
                                    <span class="text-xs font-semibold text-red-600 dark:text-red-400">✗ Out of Stock</span>
                                @endif
                            </div>

                            <!-- Order Button -->
                            <button onclick="openOrderModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $finalPrice }}, {{ $product->quantity }})"
                                @if($product->quantity <= 0) disabled @endif
                                class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed shadow-sm">
                                @if($product->quantity > 0)
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Order Now
                                    </span>
                                @else
                                    Out of Stock
                                @endif
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No products</h3>
                        <p class="text-gray-600 dark:text-gray-400">No products available in your search.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @include('user.products.modals.order-modal')

    <script>
        function sortProducts() {
            const sortBy = document.getElementById('sortFilter').value;
            const productsGrid = document.querySelector('.grid');
            const products = Array.from(document.querySelectorAll('.product-card'));
            
            if (sortBy === 'price_low') {
                products.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                });
            } else if (sortBy === 'price_high') {
                products.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceB - priceA;
                });
            } else {
                // For 'newest' or default, keep original order (by ID descending)
                products.sort((a, b) => {
                    const idA = parseInt(a.getAttribute('data-product-id'));
                    const idB = parseInt(b.getAttribute('data-product-id'));
                    return idB - idA;
                });
            }
            
            // Reorder the DOM elements
            products.forEach(product => {
                productsGrid.appendChild(product);
            });
            
            // Re-apply filters after sorting
            filterProducts();
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            document.getElementById('priceFilter').value = '';
            sortProducts();
        }

        function filterProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategoryId = document.getElementById('categoryFilter').value;
            const maxPrice = parseFloat(document.getElementById('priceFilter').value) || Infinity;

            const products = Array.from(document.querySelectorAll('.product-card'));
            
            products.forEach(product => {
                const productName = product.getAttribute('data-name').toLowerCase();
                const productCategoryId = product.getAttribute('data-category-id');
                const productPrice = parseFloat(product.getAttribute('data-price'));
                
                // Check search term
                const matchesSearch = !searchTerm || productName.includes(searchTerm);
                
                // Check category filter
                const matchesCategory = !selectedCategoryId || productCategoryId === selectedCategoryId;
                
                // Check price filter
                const matchesPrice = productPrice <= maxPrice;
                
                const show = matchesSearch && matchesCategory && matchesPrice;
                product.style.display = show ? 'flex' : 'none';
            });
        }

        // Event Listeners
        document.getElementById('searchInput').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('priceFilter').addEventListener('input', filterProducts);
        document.getElementById('sortFilter').addEventListener('change', sortProducts);

        // Initialize sorting
        sortProducts();
    </script>
</x-app-layout>