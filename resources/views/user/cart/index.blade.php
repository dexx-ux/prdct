<x-app-layout>
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 16px;
            background: white;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 16px;
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .item-price {
            color: #6b7280;
            font-size: 14px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #d1d5db;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px;
        }

        .item-total {
            font-weight: 600;
            color: #1f2937;
            min-width: 80px;
            text-align: right;
        }

        .remove-btn {
            color: #ef4444;
            cursor: pointer;
            padding: 8px;
        }

        .cart-summary {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-top: 24px;
        }

        .checkout-btn {
            background: #1a2c3e;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
        }

        .checkout-btn:hover {
            background: #0f1e2c;
        }

        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
    </style>

    <div class="py-12">
        <div class="cart-container">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

            @if($cartData->count() > 0)
                <div id="cartItems">
                    @foreach($cartData as $item)
                        @php
                            $imageUrl = $item['product']->image 
                                ? Storage::url($item['product']->image) 
                                : "https://picsum.photos/id/" . ($item['product']->id % 100 + 1) . "/400/300";
                        @endphp
                        <div class="cart-item" data-id="{{ $item['id'] }}">
                            <img src="{{ $imageUrl }}" alt="{{ $item['product']->name }}">
                            <div class="item-details">
                                <div class="item-title">{{ $item['product']->name }}</div>
                                <div class="item-price">₱{{ number_format($item['product']->price, 2) }}</div>
                            </div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})">-</button>
                                <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->quantity }}" onchange="updateQuantity({{ $item['id'] }}, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})">+</button>
                            </div>
                            <div class="item-total">₱{{ number_format($item['product']->price * $item['quantity'], 2) }}</div>
                            <div class="remove-btn" onclick="removeItem({{ $item['id'] }})">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-summary">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-gray-900" id="cartTotal">₱{{ number_format($cartData->sum(function($item) { return $item['product']->price * $item['quantity']; }), 2) }}</span>
                    </div>
                    <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
                </div>
            @else
                <div class="empty-cart">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1 5M9 21h6m-6 0a2 2 0 11-4 0m6 0a2 2 0 114 0"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="mb-4">Add some products to get started!</p>
                    <a href="{{ route('user.products.browse') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Browse Products</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Checkout Modal --}}
    <div id="checkoutModal" class="fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-70 hidden z-50 items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full">
            
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-900 dark:to-blue-800 px-6 py-4 flex justify-between items-center border-b border-blue-700 dark:border-blue-900 rounded-t-lg">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Checkout Cart
                </h3>
                <button onclick="closeCheckoutModal()" class="text-white hover:bg-blue-700 dark:hover:bg-blue-900 p-1 rounded transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                {{-- Cart Items Summary --}}
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Order Summary</h4>
                    <div id="checkoutItems" class="space-y-3 max-h-60 overflow-y-auto">
                        @if($cartData->count() > 0)
                            @foreach($cartData as $item)
                                @php
                                    $imageUrl = $item['product']->image 
                                        ? Storage::url($item['product']->image) 
                                        : "https://picsum.photos/id/" . ($item['product']->id % 100 + 1) . "/400/300";
                                @endphp
                                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                    <img src="{{ $imageUrl }}" alt="{{ $item['product']->name }}" class="w-12 h-12 rounded-lg object-cover">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800 dark:text-white">{{ $item['product']->name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Qty: {{ $item['quantity'] }} × ₱{{ number_format($item['product']->price, 2) }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-800 dark:text-white">₱{{ number_format($item['product']->price * $item['quantity'], 2) }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Form Grid 2 Columns --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    {{-- Phone Number --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="checkoutPhone" placeholder="09123456789" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p id="checkoutPhoneError" class="text-xs text-red-500 hidden mt-1">Valid phone number required (10-11 digits)</p>
                    </div>

                    {{-- Email (Optional) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email (Optional)</label>
                        <input type="email" id="checkoutEmail" placeholder="your@email.com" 
                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                {{-- Address - Full Width --}}
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delivery Address <span class="text-red-500">*</span></label>
                    <textarea id="checkoutAddress" rows="2" placeholder="Street, Barangay, City, Province (min. 10 characters)"
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    <p id="checkoutAddressError" class="text-xs text-red-500 hidden mt-1">Delivery address required (min 10 characters)</p>
                </div>

                {{-- Saved Info Notice --}}
                <div id="checkoutSavedInfoNotice" class="flex items-center gap-2 text-sm text-green-600 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg mb-5 hidden">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Using your saved information</span>
                </div>

                {{-- Total Amount --}}
                <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-4 mt-2">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Amount:</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white" id="checkoutTotal">₱{{ number_format($cartData->sum(function($item) { return $item['product']->price * $item['quantity']; }), 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeCheckoutModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Cancel
                    </button>
                    <button type="button" onclick="submitCheckout()" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"></path>
                        </svg>
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateQuantity(cartItemId, newQuantity) {
            if (newQuantity < 1) return;

            fetch(`/user/cart/${cartItemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error updating quantity');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating quantity');
            });
        }

        function removeItem(cartItemId) {
            if (!confirm('Remove this item from cart?')) return;

            fetch(`/user/cart/${cartItemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error removing item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing item');
            });
        }

        function checkout() {
            // Check if cart is empty
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                alert('Your cart is empty');
                return;
            }

            // Clear form fields
            document.getElementById('checkoutPhone').value = '';
            document.getElementById('checkoutEmail').value = '';
            document.getElementById('checkoutAddress').value = '';
            document.getElementById('checkoutPhoneError').classList.add('hidden');
            document.getElementById('checkoutAddressError').classList.add('hidden');
            document.getElementById('checkoutSavedInfoNotice').classList.add('hidden');

            // Fetch user saved info if authenticated
            @auth
                fetchUserCheckoutInfo();
            @endauth

            // Show modal
            const modal = document.getElementById('checkoutModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeCheckoutModal() {
            const modal = document.getElementById('checkoutModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function fetchUserCheckoutInfo() {
            fetch('/user/info', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let hasSaved = false;
                    if (data.phone) {
                        document.getElementById('checkoutPhone').value = data.phone;
                        hasSaved = true;
                    }
                    if (data.email) {
                        document.getElementById('checkoutEmail').value = data.email;
                        hasSaved = true;
                    }
                    if (data.address) {
                        document.getElementById('checkoutAddress').value = data.address;
                        hasSaved = true;
                    }
                    if (hasSaved) {
                        const notice = document.getElementById('checkoutSavedInfoNotice');
                        notice.classList.remove('hidden');
                        setTimeout(() => notice.classList.add('hidden'), 3000);
                    }
                }
            })
            .catch(err => console.error('Error fetching user info:', err));
        }

        function validateCheckoutDetails() {
            const phone = document.getElementById('checkoutPhone').value.trim();
            const address = document.getElementById('checkoutAddress').value.trim();
            let isValid = true;
            
            const phoneDigits = phone.replace(/\D/g, '');
            if (!phone || phoneDigits.length < 10 || phoneDigits.length > 11) {
                document.getElementById('checkoutPhoneError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('checkoutPhoneError').classList.add('hidden');
            }
            
            if (!address || address.length < 10) {
                document.getElementById('checkoutAddressError').classList.remove('hidden');
                isValid = false;
            } else {
                document.getElementById('checkoutAddressError').classList.add('hidden');
            }
            
            return isValid;
        }

        function submitCheckout() {
            if (!validateCheckoutDetails()) {
                showToast('Please provide valid phone and address', 'error');
                return;
            }

            const phone = document.getElementById('checkoutPhone').value.trim();
            const email = document.getElementById('checkoutEmail').value.trim();
            const address = document.getElementById('checkoutAddress').value.trim();
            
            const submitBtn = event.target;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
            submitBtn.disabled = true;
            
            const requestData = {
                phone: phone,
                email: email,
                address: address
            };
            
            console.log('Sending checkout request:', requestData);
            
            fetch('/user/order/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(async response => {
                const data = await response.json();
                console.log('Checkout response status:', response.status, data);
                
                if (!response.ok) {
                    if (response.status === 422 && data.errors) {
                        const errorMessages = [];
                        for (const [field, messages] of Object.entries(data.errors)) {
                            errorMessages.push(`${field}: ${messages.join(', ')}`);
                        }
                        throw new Error(errorMessages.join('; '));
                    }
                    throw new Error(data.message || `Server error: ${response.status}`);
                }
                
                return data;
            })
            .then(data => {
                if (data.success) {
                    closeCheckoutModal();
                    showToast('✓ Order placed successfully!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(data.message || 'Failed to place order', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast(error.message, 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }

        function showToast(message, type = 'success') {
            // Remove existing toasts
            document.querySelectorAll('.custom-toast').forEach(t => t.remove());
            
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed bottom-5 right-5 z-50 px-4 py-2 rounded-lg shadow-lg text-white text-sm transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-600' : 'bg-red-600'
            }`;
            toast.innerText = message;
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
                toast.classList.add('translate-x-0');
            }, 10);
            
            // Animate out after 2.5 seconds
            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }
    </script>
</x-app-layout>