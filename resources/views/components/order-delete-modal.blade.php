@props(['order' => null])

<!-- DELETE ORDER MODAL -->
<div id="deleteOrderModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">

    <!-- Clean backdrop -->
    <div class="fixed inset-0 bg-black/40 dark:bg-black/60 backdrop-blur-sm
                transition-opacity duration-300"
         onclick="closeDeleteOrderModal()"
         aria-hidden="true"></div>

    <!-- Minimal modal panel -->
    <div class="relative transform overflow-hidden rounded-2xl
                bg-white dark:bg-gray-900
                shadow-xl
                w-full max-w-md
                transition-all duration-300
                scale-95 opacity-0 translate-y-4
                data-[state=open]:scale-100 data-[state=open]:opacity-100 data-[state=open]:translate-y-0">

        <!-- Simple header -->
        <div class="px-6 pt-6 pb-2">
            <h3 id="delete-modal-title" 
                class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Delete Order
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Are you sure you want to delete this order?
            </p>
        </div>

        <!-- Order info - clean and simple -->
        <div id="orderInfoContainer" class="px-6 py-3">
            <!-- Dynamic order info will be inserted here -->
        </div>

        <!-- Simple warning -->
        <div class="px-6 py-2">
            <p class="text-xs text-red-600 dark:text-red-400">
                This action cannot be undone.
            </p>
        </div>

        <!-- Clean action buttons -->
        <div class="px-6 py-4 flex justify-end gap-2 bg-gray-50/50 dark:bg-gray-800/50">
            <button type="button"
                    onclick="closeDeleteOrderModal()"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                           text-gray-700 dark:text-gray-300
                           hover:bg-gray-100 dark:hover:bg-gray-800
                           transition-colors">
                Cancel
            </button>

            <button type="button"
                    id="confirmDeleteBtn"
                    onclick="confirmDeleteOrder()"
                    class="px-4 py-2 text-sm font-medium rounded-lg
                           bg-red-600 hover:bg-red-700
                           text-white
                           transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
let currentDeleteOrderId = null;

function openDeleteOrderModal(orderId, orderName, orderTotal) {
    currentDeleteOrderId = orderId;
    
    const modal = document.getElementById('deleteOrderModal');
    const modalPanel = modal.querySelector('.transform');
    const orderInfoContainer = document.getElementById('orderInfoContainer');
    
    // Insert order info
    orderInfoContainer.innerHTML = `
        <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
            <div class="font-medium text-gray-900 dark:text-gray-100">
                Order #${orderId}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Customer: ${escapeHtml(orderName)}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Total: ₱${parseFloat(orderTotal).toFixed(2)}
            </div>
        </div>
    `;
    
    // Show modal with animation
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        modalPanel.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
        modalPanel.classList.add('scale-100', 'opacity-100', 'translate-y-0');
    }, 10);
    
    document.body.style.overflow = 'hidden';
    document.addEventListener('keydown', handleDeleteEscapeKey);
}

function closeDeleteOrderModal() {
    const modal = document.getElementById('deleteOrderModal');
    const modalPanel = modal.querySelector('.transform');
    
    modalPanel.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
    modalPanel.classList.add('scale-95', 'opacity-0', 'translate-y-4');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        currentDeleteOrderId = null;
    }, 200);
    
    document.body.style.overflow = '';
    document.removeEventListener('keydown', handleDeleteEscapeKey);
}

function confirmDeleteOrder() {
    if (currentDeleteOrderId) {
        fetch(`/orders/${currentDeleteOrderId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDeleteOrderModal();
                // Show success message
                showToast('Order deleted successfully!', 'success');
                // Reload page after short delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Failed to delete order.', 'error');
                closeDeleteOrderModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error deleting order.', 'error');
            closeDeleteOrderModal();
        });
    }
}

function handleDeleteEscapeKey(e) {
    if (e.key === 'Escape') {
        closeDeleteOrderModal();
    }
}

function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-600' : 'bg-red-600'
    }`;
    toast.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
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

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>