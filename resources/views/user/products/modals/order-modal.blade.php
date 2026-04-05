{{-- order-modal.blade.php --}}
{{-- SAME SIZE AS ADMIN ORDER MODAL (max-w-4xl) --}}

<div id="orderModal" class="fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-70 hidden z-50 items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full">
        
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-900 dark:to-blue-800 px-6 py-4 flex justify-between items-center border-b border-blue-700 dark:border-blue-900 rounded-t-lg">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Complete Order
            </h3>
            <button onclick="closeOrderModal()" class="text-white hover:bg-blue-700 dark:hover:bg-blue-900 p-1 rounded transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6">
            {{-- Product Row --}}
            <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-5">
                <div class="w-20 h-20 rounded-lg overflow-hidden bg-white shadow-sm flex-shrink-0">
                    <img id="modalProductImage" src="" alt="Product" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p id="modalProductName" class="font-semibold text-gray-800 dark:text-white text-base">Product</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" id="modalProductPrice">₱0</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500">Available Stock:</span>
                    <span class="text-lg font-semibold text-blue-600 block" id="maxQtyInfo"></span>
                </div>
            </div>

            {{-- Form Grid 2 Columns --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                {{-- Phone Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="modalPhone" placeholder="09123456789" 
                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p id="phoneError" class="text-xs text-red-500 hidden mt-1">Valid phone number required (10-11 digits)</p>
                </div>

                {{-- Quantity --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="decreaseQty()" class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 font-bold text-xl">−</button>
                        <input type="number" id="modalQuantity" value="1" min="1" class="w-20 text-center px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg font-semibold">
                        <button type="button" onclick="increaseQty()" class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 font-bold text-xl">+</button>
                    </div>
                </div>
            </div>

            {{-- Address - Full Width --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delivery Address <span class="text-red-500">*</span></label>
                <textarea id="modalAddress" rows="2" placeholder="Street, Barangay, City, Province (min. 10 characters)"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                <p id="addressError" class="text-xs text-red-500 hidden mt-1">Delivery address required (min 10 characters)</p>
            </div>

            {{-- Saved Info Notice --}}
            <div id="savedInfoNotice" class="flex items-center gap-2 text-sm text-green-600 bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg mb-5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Using your saved information</span>
            </div>

            {{-- Total Amount --}}
            <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Amount:</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white" id="modalTotal">₱0.00</p>
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeOrderModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="button" onclick="submitOrder()" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center gap-2 shadow-sm">
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
    let currentProductId = null;
    let currentProductPrice = null;
    let currentMaxQty = null;
    let currentUserId = null;

    function setCurrentUserId(userId) {
        currentUserId = userId;
    }

    function openOrderModal(productId, productName, price, maxQty, imageUrl) {
        currentProductId = productId;
        currentProductPrice = parseFloat(price);
        currentMaxQty = maxQty;
        
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductPrice').textContent = '₱' + currentProductPrice.toFixed(2);
        document.getElementById('modalProductImage').src = imageUrl;
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalQuantity').max = maxQty;
        document.getElementById('maxQtyInfo').innerHTML = maxQty;
        
        // Clear fields
        document.getElementById('modalPhone').value = '';
        document.getElementById('modalAddress').value = '';
        document.getElementById('phoneError').classList.add('hidden');
        document.getElementById('addressError').classList.add('hidden');
        document.getElementById('savedInfoNotice').classList.add('hidden');
        
        if (currentUserId) fetchUserSavedInfo();
        
        updateModalTotal();
        
        const modal = document.getElementById('orderModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fetchUserSavedInfo() {
        if (!currentUserId) return;
        
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
                    document.getElementById('modalPhone').value = data.phone;
                    hasSaved = true;
                }
                if (data.address) {
                    document.getElementById('modalAddress').value = data.address;
                    hasSaved = true;
                }
                if (hasSaved) {
                    const notice = document.getElementById('savedInfoNotice');
                    notice.classList.remove('hidden');
                    setTimeout(() => notice.classList.add('hidden'), 3000);
                }
            }
        })
        .catch(err => console.error('Error fetching user info:', err));
    }

    function closeOrderModal() {
        const modal = document.getElementById('orderModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function increaseQty() {
        const input = document.getElementById('modalQuantity');
        let val = parseInt(input.value);
        let max = parseInt(input.max);
        if (val < max) {
            input.value = val + 1;
            updateModalTotal();
        } else {
            showToast(`Only ${max} units available`, 'error');
        }
    }

    function decreaseQty() {
        const input = document.getElementById('modalQuantity');
        let val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
            updateModalTotal();
        }
    }

    function updateModalTotal() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const total = currentProductPrice * qty;
        document.getElementById('modalTotal').textContent = '₱' + total.toFixed(2);
    }

    function validateOrderDetails() {
        const phone = document.getElementById('modalPhone').value.trim();
        const address = document.getElementById('modalAddress').value.trim();
        let isValid = true;
        
        const phoneDigits = phone.replace(/\D/g, '');
        if (!phone || phoneDigits.length < 10 || phoneDigits.length > 11) {
            document.getElementById('phoneError').classList.remove('hidden');
            isValid = false;
        } else {
            document.getElementById('phoneError').classList.add('hidden');
        }
        
        if (!address || address.length < 10) {
            document.getElementById('addressError').classList.remove('hidden');
            isValid = false;
        } else {
            document.getElementById('addressError').classList.add('hidden');
        }
        
        return isValid;
    }

    function submitOrder() {
        if (!validateOrderDetails()) {
            showToast('Please provide valid phone and address', 'error');
            return;
        }
        
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const totalPrice = currentProductPrice * qty;
        const phone = document.getElementById('modalPhone').value.trim();
        const address = document.getElementById('modalAddress').value.trim();
        
        if (!currentProductId || qty < 1) {
            showToast('Invalid order details', 'error');
            return;
        }
        
        const submitBtn = event.target;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>';
        submitBtn.disabled = true;
        
        const requestData = {
            product_id: parseInt(currentProductId),
            quantity: parseInt(qty),
            total_price: parseFloat(totalPrice),
            phone: phone,
            address: address
        };
        
        console.log('Sending order request:', requestData);
        
        fetch('/user/order', {
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
            console.log('Response status:', response.status, data);
            
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
                closeOrderModal();
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

    function addToCart(productId) {
        fetch('{{ route("user.cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.success ? 'Added to cart!' : (data.message || 'Failed'), data.success ? 'success' : 'error');
        })
        .catch(err => showToast('Error adding to cart', 'error'));
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