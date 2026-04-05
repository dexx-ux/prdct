<x-app-layout>
    <style>
        /* Minimal Dark Blue Theme #1a2c3e */
        .page-header {
            margin-bottom: 32px;
            padding: 0 4px;
        }

        .page-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1a2c3e;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .filter-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #eef2f6;
        }

        .search-input-custom {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            width: 100%;
            transition: all 0.2s;
            font-size: 0.9rem;
            background: white;
        }

        .search-input-custom:focus {
            outline: none;
            border-color: #1a2c3e;
            box-shadow: 0 0 0 3px rgba(26,44,62,0.05);
        }

        .filter-select {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 14px;
            width: 100%;
            background: white;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .filter-select:focus {
            outline: none;
            border-color: #1a2c3e;
        }

        .btn-reset {
            background: transparent;
            color: #1a2c3e;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .btn-reset:hover {
            background: #f8fafc;
            border-color: #1a2c3e;
        }

        .filter-badge {
            padding: 6px 16px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 0.8rem;
            color: #475569;
        }

        .filter-badge.active {
            background: #1a2c3e;
            color: white;
            border-color: #1a2c3e;
        }

        .filter-badge:hover:not(.active) {
            border-color: #1a2c3e;
            color: #1a2c3e;
        }

        .results-info {
            padding: 8px 0;
            margin-bottom: 20px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.2s;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(26,44,62,0.85);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 500;
            z-index: 1;
        }

        .discount-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            z-index: 1;
        }

        .product-img-wrapper {
            height: 200px;
            overflow: hidden;
            background: #fafbfc;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-img-wrapper img {
            transform: scale(1.03);
        }

        .product-info {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .product-desc {
            color: #64748b;
            font-size: 0.75rem;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .price-container {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 12px;
        }

        .current-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a2c3e;
        }

        .original-price {
            font-size: 0.8rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .stock-status {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 0;
            margin-bottom: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .stock-status.in-stock {
            color: #10b981;
        }

        .stock-status.low-stock {
            color: #f59e0b;
        }

        .stock-status.out-stock {
            color: #ef4444;
        }

        .button-group {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .order-btn-cart {
            width: 48px;
            padding: 10px;
            background: white;
            color: #1a2c3e;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .order-btn-cart:hover:not(:disabled) {
            background: #f8fafc;
            border-color: #1a2c3e;
            transform: translateY(-2px);
        }

        .order-btn {
            flex: 1;
            padding: 10px;
            background: #1a2c3e;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .order-btn:hover:not(:disabled) {
            background: #0f1e2c;
            transform: translateY(-2px);
        }

        .order-btn:disabled {
            background: #e2e8f0;
            color: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        .empty-state {
            grid-column: 1/-1;
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .scroll-top {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: white;
            color: #1a2c3e;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: none;
            transition: all 0.2s;
            font-size: 18px;
        }

        .scroll-top:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="page-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h1>Products</h1>
                        <p>Browse our collection</p>
                    </div>
                    <a href="{{ route('user.cart.index') }}" 
                       class="flex items-center gap-2 bg-[#1a2c3e] text-white px-4 py-2 rounded-lg hover:bg-[#0f1e2c] transition-colors">
                        <i class="bi bi-cart3 text-lg"></i>
                        <span class="hidden sm:inline">View Cart</span>
                    </a>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="filter-card">
                <div class="space-y-4">
                    <div>
                        <input type="text" id="searchInput" placeholder="Search products..." class="search-input-custom">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select id="categoryFilter" class="filter-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <select id="sortFilter" class="filter-select">
                                <option value="newest">Newest</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                            </select>
                        </div>

                        <div>
                            <input type="number" id="priceFilter" placeholder="Max price" min="0" step="100" class="filter-select">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="clearFiltersBtn" class="btn-reset">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2 flex-wrap mb-5">
                <button class="filter-badge active" data-filter="all">All</button>
                <button class="filter-badge" data-filter="sale">Sale</button>
                <button class="filter-badge" data-filter="lowstock">Low Stock</button>
                <button class="filter-badge" data-filter="instock">In Stock</button>
            </div>

            <!-- Results Info -->
            <div class="results-info">
                <span id="resultsCount">Loading...</span>
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                @forelse($products as $product)
                    @php
                        $discount = (float)($product->discount_value ?? 0);
                        $hasDiscount = $discount > 0;
                        $finalPrice = $product->price - $discount;
                        $categoryId = $product->category ? $product->category->id : '';
                        $categoryName = $product->category ? $product->category->name : 'Uncategorized';
                        $quantity = (int)($product->quantity ?? 0);
                        $stockClass = $quantity <= 0 ? 'out-stock' : ($quantity <= 5 ? 'low-stock' : 'in-stock');
                        $stockText = $quantity <= 0 ? 'Out of Stock' : ($quantity <= 5 ? 'Low Stock' : 'In Stock');
                        $imageUrl = $product->image 
                            ? Storage::url($product->image) 
                            : "https://picsum.photos/id/" . ($product->id % 100 + 1) . "/400/300";
                    @endphp
                    <div class="product-card" 
                        data-product-id="{{ $product->id }}" 
                        data-category-id="{{ $categoryId }}"
                        data-price="{{ $finalPrice }}"
                        data-name="{{ strtolower($product->name) }}"
                        data-quantity="{{ $quantity }}"
                        data-discount="{{ $hasDiscount ? '1' : '0' }}">
                        
                        <span class="category-badge">{{ $categoryName }}</span>
                        
                        @if($hasDiscount)
                            <span class="discount-badge">-₱{{ number_format($discount, 2) }}</span>
                        @endif
                        
                        <div class="product-img-wrapper">
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-desc">{{ Str::limit($product->description ?? '', 70) }}</p>
                            
                            <div class="price-container">
                                <span class="current-price">₱{{ number_format($finalPrice, 2) }}</span>
                                @if($hasDiscount)
                                    <span class="original-price">₱{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <div class="stock-status {{ $stockClass }}">
                                <span>{{ $stockText }}</span>
                                @if($quantity > 0 && $quantity <= 10)
                                    <span>({{ $quantity }} left)</span>
                                @endif
                            </div>
                            
                            <div class="button-group">
                                @if($quantity > 0)
                                    <button class="order-btn-cart" onclick="addToCart({{ $product->id }})" title="Add to Cart">
                                        <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </button>
                                    
                                    <button class="order-btn" onclick="openOrderModal(
                                        {{ $product->id }}, 
                                        '{{ addslashes($product->name) }}', 
                                        {{ floatval($finalPrice) }}, 
                                        {{ $quantity }},
                                        '{{ $imageUrl }}'
                                    )">
                                        Buy Now
                                    </button>
                                @else
                                    <button class="order-btn" disabled style="width: 100%;">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No products found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <button class="scroll-top" id="scrollTopBtn">↑</button>

    {{-- Include the external order modal --}}
@include('user.products.modals.order-modal')

    <script>
        // Set current user ID for the modal
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof setCurrentUserId !== 'undefined') {
                setCurrentUserId({{ auth()->id() ?? 'null' }});
            }
        });

        const scrollBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            scrollBtn.style.display = window.scrollY > 300 ? 'block' : 'none';
        });
        scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        function filterProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategoryId = document.getElementById('categoryFilter').value;
            const maxPrice = parseFloat(document.getElementById('priceFilter').value) || Infinity;
            const activeFilter = document.querySelector('.filter-badge.active')?.dataset.filter || 'all';

            const products = Array.from(document.querySelectorAll('.product-card'));
            let visibleCount = 0;
            
            products.forEach(product => {
                const productName = product.getAttribute('data-name');
                const productCategoryId = product.getAttribute('data-category-id');
                const productPrice = parseFloat(product.getAttribute('data-price'));
                const productQuantity = parseInt(product.getAttribute('data-quantity'));
                const hasDiscount = product.getAttribute('data-discount') === '1';
                
                const matchesSearch = !searchTerm || productName.includes(searchTerm);
                const matchesCategory = !selectedCategoryId || productCategoryId === selectedCategoryId;
                const matchesPrice = productPrice <= maxPrice;
                
                let matchesFilter = true;
                if (activeFilter === 'sale') matchesFilter = hasDiscount;
                else if (activeFilter === 'lowstock') matchesFilter = productQuantity > 0 && productQuantity <= 5;
                else if (activeFilter === 'instock') matchesFilter = productQuantity > 0;
                
                const show = matchesSearch && matchesCategory && matchesPrice && matchesFilter;
                product.style.display = show ? 'flex' : 'none';
                if (show) visibleCount++;
            });
            
            document.getElementById('resultsCount').textContent = `${visibleCount} product${visibleCount !== 1 ? 's' : ''}`;
        }

        function sortProducts() {
            const sortBy = document.getElementById('sortFilter').value;
            const productsGrid = document.getElementById('productsGrid');
            const products = Array.from(document.querySelectorAll('.product-card'));
            
            if (sortBy === 'price_low') {
                products.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
            } else if (sortBy === 'price_high') {
                products.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
            } else {
                products.sort((a, b) => parseInt(b.dataset.productId) - parseInt(a.dataset.productId));
            }
            
            products.forEach(product => productsGrid.appendChild(product));
            filterProducts();
        }

        document.querySelectorAll('.filter-badge').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-badge').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filterProducts();
            });
        });

        document.getElementById('clearFiltersBtn')?.addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('sortFilter').value = 'newest';
            document.getElementById('priceFilter').value = '';
            document.querySelectorAll('.filter-badge').forEach(b => b.classList.remove('active'));
            document.querySelector('.filter-badge[data-filter="all"]').classList.add('active');
            sortProducts();
        });

        document.getElementById('searchInput').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('priceFilter').addEventListener('input', filterProducts);
        document.getElementById('sortFilter').addEventListener('change', sortProducts);

        sortProducts();

        // Note: addToCart, showToast, etc. are now in the external order-modal.js
        // Make sure addToCart is available globally
        window.addToCart = function(productId) {
            fetch('{{ route("user.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Added to cart!', 'success');
                } else {
                    showToast(data.message || 'Failed to add to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error adding to cart', 'error');
            });
        };
    </script>
</x-app-layout>