<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'PRODUCTS') }} - Shop Quality Products</title>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v={{ filemtime(public_path('css/welcome.css')) }}">
    <style>
        #clearFiltersBtn.clear-filters-btn {
            background: #1a2c3e !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 10px 24px !important;
            gap: 8px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
        }
        #clearFiltersBtn.clear-filters-btn:hover {
            background: #0f1e2c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(26, 44, 62, 0.3) !important;
        }
    </style>
</head>
<body>

    <!-- Enhanced Header -->
    <div class="header">
        <div class="header-content">
            <div class="logo-area">
                <img src="{{ asset('images/logos.png') }}" alt="HMR Logo">
                <div>
                    <h1>PRODUCTS</h1>
                    <p>Shop Quality Products Online</p>
                </div>
            </div>
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn-register">
                    <i class="bi bi-person-plus"></i> Register
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Search & Sort Section -->
        <div class="search-section">
            <div class="search-bar">
                <div style="flex: 2; min-width: 300px; position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #aaa;"></i>
                    <input type="text" id="searchInput" placeholder="Search products by name or description..." class="search-input" style="padding-left: 36px; width: 100%;">
                </div>
                <select id="categoryFilter" class="category-select">
                    <option value="">All Categories</option>
                    @php
                        $categories = \App\Models\Category::has('products')->orderBy('name', 'asc')->get()->pluck('name')->unique();
                    @endphp
                    @foreach($categories as $category)
                    <option value="{{ strtolower($category) }}">{{ $category }}</option>
                    @endforeach
                </select>
                <select id="sortSelect" class="sort-select">
                    <option value="default">Default (ID Order)</option>
                    <option value="newest">Newest First</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
               <button type="button" id="clearFiltersBtn" class="clear-filters-btn">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </button>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-section">
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all">All Products</button>
                <button class="filter-btn" data-filter="sale">On Sale</button>
                <button class="filter-btn" data-filter="lowstock">Low Stock</button>
                <button class="filter-btn" data-filter="instock">In Stock</button>
            </div>
        </div>

        <!-- Results Info -->
        <div class="results-info">
            <i class="bi bi-info-circle"></i>
            <span id="resultsCount">Showing products</span>
        </div>

        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            @forelse($products as $product)
                @php
                    $category = $product->category;
                    $categoryName = $category ? $category->name : 'Uncategorized';
                    $price = floatval($product->price ?? 0);
                    
                    // Handle discount value (can be percentage like "10%" or fixed amount like "500")
                    $discountValue = $product->discount_value;
                    $discountPercent = 0;
                    $discountAmount = 0;
                    $hasDiscount = false;
                    
                    if ($discountValue) {
                        $discountValue = trim($discountValue);
                        if (str_ends_with($discountValue, '%')) {
                            // Percentage discount
                            $discountPercent = floatval(rtrim($discountValue, '%'));
                            $hasDiscount = $discountPercent > 0;
                            $finalPrice = $price * (1 - $discountPercent / 100);
                        } else {
                            // Fixed amount discount
                            $discountAmount = floatval($discountValue);
                            $hasDiscount = $discountAmount > 0 && $discountAmount < $price;
                            $finalPrice = $price - $discountAmount;
                        }
                    } else {
                        $finalPrice = $price;
                    }
                    
                    $quantity = intval($product->quantity ?? 0);
                    $stockClass = $quantity <= 0 ? 'out-stock' : ($quantity <= 5 ? 'low-stock' : 'in-stock');
                    $stockText = $quantity <= 0 ? 'Out of Stock' : ($quantity <= 5 ? 'Low Stock' : 'In Stock');
                    
                    // Use real image from database or fallback to placeholder
                    $imageUrl = $product->image 
                        ? Storage::url($product->image) 
                        : "https://picsum.photos/id/" . ($product->id % 100 + 1) . "/400/300";
                @endphp
                <div class="product-card" 
                    data-product-id="{{ $product->id }}" 
                    data-category="{{ strtolower($categoryName) }}"
                    data-discount="{{ $hasDiscount ? '1' : '0' }}" 
                    data-quantity="{{ $quantity }}" 
                    data-price="{{ $finalPrice }}" 
                    data-name="{{ strtolower($product->name) }}"
                    data-created-at="{{ $product->created_at }}">
                    
                    <span class="category-badge">{{ $categoryName }}</span>
                    
                    @if($hasDiscount)
                        @if($discountPercent > 0)
                            <span class="discount-badge-top">-{{ $discountPercent }}%</span>
                        @else
                            <span class="discount-badge-top">-₱{{ number_format($discountAmount, 2) }}</span>
                        @endif
                    @endif
                    
                    <div class="product-image">
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="lazy">
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <p class="product-description">{{ Str::limit($product->description ?? 'Quality product', 80) }}</p>
                        
                        <div class="price-wrapper">
                            <span class="current-price">₱{{ number_format($finalPrice, 2) }}</span>
                            @if($hasDiscount)
                                <span class="original-price">₱{{ number_format($price, 2) }}</span>
                            @endif
                        </div>
                        
                        <div class="stock {{ $stockClass }}">
                            <i class="bi bi-{{ $quantity <= 0 ? 'exclamation-circle' : ($quantity <= 5 ? 'exclamation-triangle' : 'check-circle') }}"></i>
                            <span>{{ $stockText }}</span>
                            @if($quantity > 0 && $quantity <= 10)
                                <span style="margin-left: auto; font-size: 0.65rem;">{{ $quantity }} left</span>
                            @endif
                        </div>
                        
                        @if($quantity > 0)
                            <button class="order-now-btn" onclick="openOrderModal(
                                {{ $product->id }}, 
                                '{{ addslashes($product->name) }}', 
                                {{ floatval($finalPrice) }}, 
                                {{ $quantity }},
                                '{{ $imageUrl }}'
                            )">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                        @else
                            <button class="order-now-btn" disabled>
                                <i class="bi bi-ban"></i> Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>No products found</p>
                </div>
            @endforelse
        </div>
    </div>

    <button class="scroll-top" id="scrollTopBtn">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Pass PHP variables to JavaScript -->
    <script>
        window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        window.user = {
            id: {{ auth()->id() ?? 'null' }},
            name: '{{ auth()->user()->name ?? '' }}',
            email: '{{ auth()->user()->email ?? '' }}',
            phone: '{{ auth()->user()->phone ?? '' }}'
        };
    </script>
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/welcome.js') }}"></script>
    
    <!-- Clear Filters Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clearBtn = document.getElementById('clearFiltersBtn');
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortSelect = document.getElementById('sortSelect');
            const filterBtns = document.querySelectorAll('.filter-btn');
            
            function clearAllFilters() {
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
                
                // Reset sort select to default
                if (sortSelect) {
                    sortSelect.value = 'default';
                    sortSelect.dispatchEvent(new Event('change'));
                }
                
                // Reset filter buttons
                filterBtns.forEach(btn => {
                    btn.classList.remove('active');
                    if (btn.getAttribute('data-filter') === 'all') {
                        btn.classList.add('active');
                    }
                });
                
                // Trigger a custom event for other filters
                window.dispatchEvent(new CustomEvent('filtersCleared'));
            }
            
            if (clearBtn) {
                clearBtn.addEventListener('click', clearAllFilters);
            }
        });
    </script>
</body>
</html>