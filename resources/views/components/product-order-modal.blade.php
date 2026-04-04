<!-- Order Modal -->
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeOrderModal(event)">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-[#991b1b] to-red-800 rounded-t-lg">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Order Product</h2>
                <button onclick="closeOrderModal()" class="text-white hover:text-gray-200 text-2xl">×</button>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Product</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modalProductName">-</p>
            </div>

            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Price per Unit</p>
                <p class="text-2xl font-bold text-[#991b1b]" id="modalProductPrice">₱0.00</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity</label>
                <div class="flex items-center gap-3">
                    <button onclick="decreaseQty()" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">-</button>
                    <input type="number" id="modalQuantity" value="1" min="1" class="w-20 text-center px-3 py-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded">
                    <button onclick="increaseQty()" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">+</button>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2" id="maxQtyInfo"></p>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-gray-600 dark:text-gray-400">Total:</p>
                    <p class="text-2xl font-bold text-[#991b1b]" id="modalTotal">₱0.00</p>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex gap-3">
            <button onclick="closeOrderModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition font-medium">
                Cancel
            </button>
            <button onclick="submitOrder()" class="flex-1 px-4 py-2 bg-[#991b1b] hover:bg-red-800 dark:hover:bg-red-900 text-white rounded-lg transition font-medium flex items-center justify-center gap-2">
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
        currentProductPrice = price;
        currentMaxQty = maxQty;
        
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductPrice').textContent = '₱' + parseFloat(price).toFixed(2);
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalQuantity').max = maxQty;
        document.getElementById('maxQtyInfo').textContent = `Available: ${maxQty} units`;
        
        updateModalTotal();
        document.getElementById('orderModal').classList.remove('hidden');
    }

    function closeOrderModal(event) {
        if (event && event.target !== document.getElementById('orderModal')) return;
        document.getElementById('orderModal').classList.add('hidden');
    }

    function increaseQty() {
        const input = document.getElementById('modalQuantity');
        if (parseInt(input.value) < parseInt(input.max)) {
            input.value = parseInt(input.value) + 1;
            updateModalTotal();
        }
    }

    function decreaseQty() {
        const input = document.getElementById('modalQuantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            updateModalTotal();
        }
    }

    document.getElementById('modalQuantity').addEventListener('change', updateModalTotal);

    function updateModalTotal() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        const total = currentProductPrice * qty;
        document.getElementById('modalTotal').textContent = '₱' + parseFloat(total).toFixed(2);
    }

    function submitOrder() {
        const qty = parseInt(document.getElementById('modalQuantity').value);
        
        fetch('{{ route("user.order.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: currentProductId,
                quantity: qty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeOrderModal();
                alert('Order placed successfully! Order #' + data.order_id);
                setTimeout(() => location.reload(), 500);
            } else {
                alert('Error: ' + (data.message || 'Failed to place order'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
</script>
