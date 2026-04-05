<!-- Order Details Modal -->
<div id="orderModal" class="hidden fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-70 z-50 items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-900 dark:to-blue-800 px-6 py-4 flex justify-between items-center border-b border-blue-700 dark:border-blue-900">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="bi bi-box-seam"></i>Order Details
            </h3>
            <button onclick="closeOrderModal()" class="text-white hover:bg-blue-700 dark:hover:bg-blue-900 p-1 rounded transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="modalContent" class="p-6">
            <!-- Loading State -->
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin">
                    <i class="bi bi-hourglass text-blue-600 dark:text-blue-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Modal Footer with Actions -->
        <div id="modalFooter" class="hidden px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <button onclick="updateOrderStatusFromModal('pending')"
                            class="px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:hover:bg-yellow-800 dark:text-yellow-200 rounded text-sm font-medium transition">
                        <i class="bi bi-clock mr-1"></i>Pending
                    </button>
                    <button onclick="updateOrderStatusFromModal('processing')"
                            class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-blue-200 rounded text-sm font-medium transition">
                        <i class="bi bi-gear mr-1"></i>Processing
                    </button>
                    <button onclick="updateOrderStatusFromModal('completed')"
                            class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 dark:bg-green-900 dark:hover:bg-green-800 dark:text-green-200 rounded text-sm font-medium transition">
                        <i class="bi bi-check-circle mr-1"></i>Completed
                    </button>
                    <button onclick="cancelOrderFromModal()"
                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-200 rounded text-sm font-medium transition">
                        <i class="bi bi-x-circle mr-1"></i>Cancel
                    </button>
                </div>
                <button onclick="closeOrderModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variable to store current order ID and status
let currentOrderId = null;
let currentOrderStatus = null;

function openOrderModal(orderId) {
    currentOrderId = orderId;
    
    const modal = document.getElementById('orderModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin">
                <i class="bi bi-hourglass text-blue-600 dark:text-blue-400 text-2xl"></i>
            </div>
            <span class="ml-2 text-gray-600 dark:text-gray-400">Loading order details...</span>
        </div>
    `;
    
    document.getElementById('modalFooter').classList.add('hidden');
    
    fetch(`/admin/orders/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentOrderStatus = data.order.status;
                renderOrderDetails(data.order);
                
                // ONLY show footer if order is NOT cancelled
                if (currentOrderStatus !== 'cancelled') {
                    document.getElementById('modalFooter').classList.remove('hidden');
                }
            } else {
                modalContent.innerHTML = `
                    <div class="text-center py-8 text-red-600 dark:text-red-400">
                        <i class="bi bi-exclamation-triangle text-4xl"></i>
                        <p class="mt-2">Failed to load order details</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="text-center py-8 text-red-600 dark:text-red-400">
                    <i class="bi bi-exclamation-triangle text-4xl"></i>
                    <p class="mt-2">Error loading order details</p>
                </div>
            `;
        });
}

function renderOrderDetails(order) {
    const modalContent = document.getElementById('modalContent');
    const isCancelled = order.status === 'cancelled';
    
    modalContent.innerHTML = `
        <div class="space-y-6">
            <!-- Order Header -->
            <div class="flex justify-between items-start border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Order #${order.id}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Placed on ${new Date(order.created_at).toLocaleString()}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${getStatusColor(order.status)}">
                        ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                    </span>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h5 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="bi bi-person"></i> Customer Information
                </h5>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Name</p>
                        <p class="text-gray-900 dark:text-white font-medium">${order.customer_name || order.user?.name || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Email</p>
                        <p class="text-gray-900 dark:text-white">${order.customer_email || order.user?.email || 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Order Type</p>
                        <p class="text-gray-900 dark:text-white">${order.order_type === 'user' ? 'Registered User' : 'Guest'}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Payment Method</p>
                        <p class="text-gray-900 dark:text-white">${order.payment_method || 'N/A'}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div>
                <h5 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="bi bi-cart"></i> Order Items
                </h5>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-center">Quantity</th>
                                <th class="px-4 py-2 text-right">Price</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            ${order.items.map(item => `
                                <tr>
                                    <td class="px-4 py-2 text-gray-900 dark:text-white">${item.product?.name || 'Product not found'}</td>
                                    <td class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">${item.quantity}</td>
                                    <td class="px-4 py-2 text-right text-gray-700 dark:text-gray-300">₱${parseFloat(item.price).toFixed(2)}</td>
                                    <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">₱${(item.quantity * item.price).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right font-bold">Total:</td>
                                <td class="px-4 py-2 text-right font-bold text-gray-900 dark:text-white">₱${parseFloat(order.total_amount || order.total).toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            ${isCancelled ? `
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex items-center gap-2 text-red-800 dark:text-red-300">
                    <i class="bi bi-info-circle"></i>
                    <span class="text-sm">⚠️ This order has been cancelled by the customer and cannot be modified.</span>
                </div>
            </div>
            ` : ''}
        </div>
    `;
}

function updateOrderStatusFromModal(newStatus) {
    // PREVENT status change for cancelled orders
    if (currentOrderStatus === 'cancelled') {
        alert('Cannot modify cancelled orders');
        closeOrderModal();
        return;
    }
    
    if (!confirm(`Change order status to ${newStatus.toUpperCase()}?`)) return;
    
    fetch(`/admin/orders/${currentOrderId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentOrderStatus = newStatus;
            alert(`Order status updated to ${newStatus}`);
            
            // Update table row
            const row = document.querySelector(`.order-row[data-order-id="${currentOrderId}"]`);
            if (row) {
                row.setAttribute('data-status', newStatus);
                const statusBadge = row.querySelector('.status-badge');
                if (statusBadge) {
                    const statusColors = {
                        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                        processing: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                        completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                    };
                    statusBadge.className = `px-2 py-0.5 rounded-full text-xs font-semibold status-badge ${statusColors[newStatus]}`;
                    statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }
            }
            
            // If cancelled, hide footer and reload modal content
            if (newStatus === 'cancelled') {
                document.getElementById('modalFooter').classList.add('hidden');
                // Refresh the order details to show cancelled message
                fetch(`/admin/orders/${currentOrderId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            renderOrderDetails(data.order);
                        }
                    });
            }
            
            if (window.filterOrders) window.filterOrders();
        } else {
            alert('Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update status');
    });
}

function cancelOrderFromModal() {
    // PREVENT cancelling already cancelled orders
    if (currentOrderStatus === 'cancelled') {
        alert('Order is already cancelled');
        return;
    }
    
    if (confirm('Are you sure you want to CANCEL this order?\n\nThis action cannot be undone.')) {
        updateOrderStatusFromModal('cancelled');
    }
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    currentOrderId = null;
    currentOrderStatus = null;
}

function getStatusColor(status) {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        processing: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    };
    return colors[status] || colors.pending;
}
</script>