<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 relative">
        <!-- Visible Top Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-800 dark:bg-gray-900 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <h2 class="text-xl font-bold text-white">Order Product</h2>
                </div>
                <button onclick="closeOrderModal()" class="text-white hover:text-gray-300 text-2xl leading-none">&times;</button>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Product</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modalProductName">-</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Price per Unit</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white" id="modalProductPrice">₱0.00</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quantity</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="decreaseQty()" class="w-10 h-10 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 transition flex items-center justify-center text-xl font-bold">-</button>
                    <input type="number" id="modalQuantity" value="1" min="1" class="w-20 text-center px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-lg font-semibold">
                    <button type="button" onclick="increaseQty()" class="w-10 h-10 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 transition flex items-center justify-center text-xl font-bold">+</button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3" id="maxQtyInfo"></p>
            </div>

            <div class="border-t-2 border-gray-200 dark:border-gray-700 pt-4 mt-2">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Amount:</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white" id="modalTotal">₱0.00</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3 bg-gray-50 dark:bg-gray-800 rounded-b-lg">
            <button type="button" onclick="closeOrderModal()" class="flex-1 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg transition font-medium">
                Cancel
            </button>
            <button type="button" onclick="submitOrder()" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium flex items-center justify-center gap-2 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Place Order
            </button>
        </div>
    </div>
</div>

<script>
    let currentProductId = null;
    let currentProductPrice = null;
    let currentMaxQty = null;

    function openOrderModal(productId, productName, price, maxQty) {
        currentProductId = productId;
        currentProductPrice = parseFloat(price);
        currentMaxQty = maxQty;
        
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductPrice').textContent = '₱' + currentProductPrice.toFixed(2);
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalQuantity').max = maxQty;
        document.getElementById('maxQtyInfo').innerHTML = `<span class="text-blue-600 dark:text-blue-400">✓ Available stock: ${maxQty} units</span>`;
        
        updateModalTotal();
        
        // Show modal with flex centering
        const modal = document.getElementById('orderModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeOrderModal() {
        const modal = document.getElementById('orderModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function increaseQty() {
        const input = document.getElementById('modalQuantity');
        let currentValue = parseInt(input.value);
        let maxValue = parseInt(input.max);
        
        if (currentValue < maxValue) {
            input.value = currentValue + 1;
            updateModalTotal();
        } else {
            showToast(`Only ${maxValue} units available`, 'error');
        }
    }

    function decreaseQty() {
        const input = document.getElementById('modalQuantity');
        let currentValue = parseInt(input.value);
        
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateModalTotal();
        }
    }

    // Add event listener for manual quantity input
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('modalQuantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                let min = parseInt(this.min);
                let max = parseInt(this.max);
                
                if (isNaN(value)) {
                    this.value = min;
                } else if (value < min) {
                    this.value = min;
                } else if (value > max) {
                    this.value = max;
                    showToast(`Only ${max} units available`, 'error');
                }
                
                updateModalTotal();
            });
        }
    });

    function updateModalTotal() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const total = currentProductPrice * qty;
        document.getElementById('modalTotal').textContent = '₱' + total.toFixed(2);
    }

    function submitOrder() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const totalPrice = currentProductPrice * qty;
        
        if (!currentProductId || qty < 1) {
            showToast('Invalid order details', 'error');
            return;
        }
        
        // Show loading state on button
        const submitBtn = event.target;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div> Processing...';
        submitBtn.disabled = true;
        
        fetch('{{ route("user.order.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: currentProductId,
                quantity: qty,
                total_price: totalPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeOrderModal();
                showToast('✓ Order placed successfully! Order #' + data.order_id, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showToast(data.message || 'Failed to place order', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred. Please try again.', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    function showToast(message, type = 'success') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `custom-toast fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                ${type === 'success' ? 
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' : 
                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                }
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
</script>