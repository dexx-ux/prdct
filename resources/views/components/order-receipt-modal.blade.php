@props(['order' => null])

<!-- ORDER RECEIPT MODAL -->
<div id="orderReceiptModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="receipt-modal-title">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm transition-opacity duration-300"
         onclick="closeOrderReceiptModal()"
         aria-hidden="true"></div>

    <!-- Modal Panel -->
    <div class="relative transform overflow-hidden rounded-2xl
                bg-white dark:bg-gray-900
                shadow-xl
                w-full max-w-2xl
                max-h-[90vh] overflow-y-auto
                transition-all duration-300
                scale-95 opacity-0 translate-y-4
                data-[state=open]:scale-100 data-[state=open]:opacity-100 data-[state=open]:translate-y-0">

        <!-- Header -->
        <div class="sticky top-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
            <h3 id="receipt-modal-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Order Receipt
            </h3>
            <button onclick="closeOrderReceiptModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div id="receiptContent" class="p-6">
            <div class="text-center py-8">
                <i class="bi bi-hourglass-split animate-spin text-3xl text-blue-600"></i>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Loading order details...</p>
            </div>
        </div>
    </div>
</div>

<script>
function openOrderReceiptModal(orderId) {
    const modal = document.getElementById('orderReceiptModal');
    const modalPanel = modal.querySelector('.transform');
    const content = document.getElementById('receiptContent');
    
    // Show loading state
    content.innerHTML = `
        <div class="text-center py-8">
            <i class="bi bi-hourglass-split animate-spin text-3xl text-blue-600"></i>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Loading order details...</p>
        </div>
    `;
    
    // Fetch order details
    fetch(`/orders/${orderId}/receipt`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = generateReceiptHTML(data.order);
            } else {
                content.innerHTML = `
                    <div class="text-center py-8 text-red-600">
                        <i class="bi bi-exclamation-triangle text-3xl"></i>
                        <p class="mt-2">Failed to load order details</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-8 text-red-600">
                    <i class="bi bi-exclamation-triangle text-3xl"></i>
                    <p class="mt-2">Error loading order details</p>
                </div>
            `;
        });
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modalPanel.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
        modalPanel.classList.add('scale-100', 'opacity-100', 'translate-y-0');
    }, 10);
    
    document.body.style.overflow = 'hidden';
    document.addEventListener('keydown', handleReceiptEscapeKey);
}

function closeOrderReceiptModal() {
    const modal = document.getElementById('orderReceiptModal');
    const modalPanel = modal.querySelector('.transform');
    
    modalPanel.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    modalPanel.classList.add('scale-95', 'opacity-0', 'translate-y-4');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
    
    document.body.style.overflow = '';
    document.removeEventListener('keydown', handleReceiptEscapeKey);
}

function handleReceiptEscapeKey(e) {
    if (e.key === 'Escape') {
        closeOrderReceiptModal();
    }
}

function generateReceiptHTML(order) {
    const unitPrice = order.unit_price || (order.total_price / order.quantity);
    
    return `
        <div class="space-y-6">
            <!-- Receipt Header -->
            <div class="text-center border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">ORDER RECEIPT</h2>
                <p class="text-gray-500 dark:text-gray-400">Order #${order.id}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Date: ${new Date(order.created_at).toLocaleString()}</p>
            </div>
            
            <!-- Store Info -->
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">PRODUCTS STORE</p>
                <p>123 Main Street, City, Country</p>
                <p>Tel: (123) 456-7890 | Email: support@products.com</p>
            </div>
            
            <!-- Customer Info -->
            <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Bill To:</h3>
                <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> ${escapeHtml(order.customer_name)}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> ${escapeHtml(order.customer_email)}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Phone:</strong> ${escapeHtml(order.customer_phone)}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Address:</strong> ${escapeHtml(order.customer_address)}</p>
            </div>
            
            <!-- Order Items -->
            <div>
                <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Order Items:</h3>
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm">Item</th>
                                <th class="px-4 py-2 text-center text-sm">Qty</th>
                                <th class="px-4 py-2 text-right text-sm">Price</th>
                                <th class="px-4 py-2 text-right text-sm">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-4 py-3 text-sm">${escapeHtml(order.product.name)}</td>
                                <td class="px-4 py-3 text-sm text-center">${order.quantity}</td>
                                <td class="px-4 py-3 text-sm text-right">₱${unitPrice.toFixed(2)}</td>
                                <td class="px-4 py-3 text-sm text-right font-semibold">₱${order.total_price.toFixed(2)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Total -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>TOTAL:</span>
                    <span class="text-red-600 dark:text-red-400">₱${order.total_price.toFixed(2)}</span>
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg text-center">
                <p class="text-green-700 dark:text-green-400 font-semibold">✓ Payment Status: Paid</p>
                <p class="text-sm text-green-600 dark:text-green-500 mt-1">Payment Method: Cash on Delivery</p>
            </div>
            
            <!-- Thank You -->
            <div class="text-center pt-4">
                <p class="text-gray-600 dark:text-gray-400">Thank you for your purchase!</p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">For inquiries, please contact support@products.com</p>
            </div>
        </div>
    `;
}

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>