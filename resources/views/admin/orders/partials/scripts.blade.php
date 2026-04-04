<script>
    // ============================================
    // SIMPLE & CLEAN ORDER MANAGEMENT
    // MODAL PERFECTLY CENTERED ON SCREEN
    // ============================================

    (function() {
        'use strict';

        // ---------- Helper: Escape HTML ----------
        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        // ---------- Clean Notification ----------
        function showNotification(message, type = 'info') {
            const existing = document.querySelector('.clean-notification');
            if (existing) existing.remove();

            const notif = document.createElement('div');
            notif.className = 'clean-notification fixed top-5 right-5 z-[10000] px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium flex items-center gap-2 transition-all duration-300';
            
            const colors = {
                success: 'bg-emerald-500',
                error: 'bg-rose-500',
                warning: 'bg-amber-500',
                info: 'bg-sky-500'
            };
            
            const icons = {
                success: 'bi-check-circle-fill',
                error: 'bi-exclamation-triangle-fill',
                warning: 'bi-exclamation-circle-fill',
                info: 'bi-info-circle-fill'
            };
            
            notif.style.backgroundColor = colors[type] || colors.info;
            notif.innerHTML = `<i class="bi ${icons[type]}"></i><span>${message}</span>`;
            document.body.appendChild(notif);
            
            setTimeout(() => {
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        }

        // ---------- Clean Confirm Dialog ----------
        function showConfirmDialog(message, onConfirm) {
            const existing = document.querySelector('.clean-confirm');
            if (existing) existing.remove();
            
            const overlay = document.createElement('div');
            overlay.className = 'clean-confirm fixed inset-0 bg-black/40 z-[10001] flex items-center justify-center';
            
            const box = document.createElement('div');
            box.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full mx-5 p-6 transform transition-all';
            box.innerHTML = `
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-rose-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Action</h3>
                </div>
                <p class="text-gray-600 mb-6">${message}</p>
                <div class="flex gap-3 justify-end">
                    <button class="cancel-btn px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition font-medium">Cancel</button>
                    <button class="confirm-btn px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition font-medium">Confirm</button>
                </div>
            `;
            
            overlay.appendChild(box);
            document.body.appendChild(overlay);
            
            const close = () => overlay.remove();
            box.querySelector('.cancel-btn').addEventListener('click', close);
            box.querySelector('.confirm-btn').addEventListener('click', () => {
                close();
                if (onConfirm) onConfirm();
            });
            overlay.addEventListener('click', (e) => { if (e.target === overlay) close(); });
        }

        // ---------- Dropdown Functions ----------
        function closeAllStatusDropdowns() {
            document.querySelectorAll('.status-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }

        function toggleStatusDropdown(event, orderId) {
            event.stopPropagation();
            closeAllStatusDropdowns();
            const dropdown = document.querySelector(`.dropdown-${orderId}`);
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.status-dropdown') && !event.target.closest('button[title="Update status"]')) {
                closeAllStatusDropdowns();
            }
        });

        // ---------- Bulk Delete ----------
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateBulkDeleteBtn();
        }

        function updateBulkDeleteBtn() {
            const checked = document.querySelectorAll('.order-checkbox:checked').length;
            const btn = document.getElementById('bulkDeleteBtn');
            const count = document.getElementById('deleteCount');
            
            if (checked > 0) {
                btn.classList.remove('hidden');
                count.textContent = checked;
            } else {
                btn.classList.add('hidden');
                const selectAll = document.getElementById('selectAll');
                if (selectAll) selectAll.checked = false;
            }
        }

        function deleteSelected() {
            const checkedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);
            if (checkedIds.length === 0) {
                showNotification('Please select orders to delete', 'warning');
                return;
            }
            showConfirmDialog(`Delete ${checkedIds.length} order(s)? This action cannot be undone.`, () => {
                fetch('/admin/orders/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ids: checkedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(`${checkedIds.length} order(s) deleted successfully`, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('Error deleting orders', 'error');
                    }
                })
                .catch(() => showNotification('Error deleting orders', 'error'));
            });
        }

        // ============================================
        // MODAL PERFECTLY CENTERED ON SCREEN
        // Clean | Simple | Centered
        // ============================================
        
        // Create modal HTML structure (injected once)
        function createModalIfNotExists() {
            if (document.getElementById('orderModal')) return;
            
            const modalHTML = `
                <div id="orderModal" class="order-modal-overlay" style="display: none;">
                    <div class="order-modal-container">
                        <button class="order-modal-close" onclick="closeOrderModal()">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <div id="modalContent" class="order-modal-content">
                            <div class="flex items-center justify-center py-12">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Add styles for centering
            const style = document.createElement('style');
            style.textContent = `
                .order-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(4px);
                    z-index: 10002;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .order-modal-container {
                    position: relative;
                    background: white;
                    border-radius: 1.5rem;
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                    width: 90%;
                    max-width: 900px;
                    max-height: 85vh;
                    overflow-y: auto;
                    margin: 20px;
                }
                .order-modal-close {
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    background: transparent;
                    border: none;
                    font-size: 1.25rem;
                    cursor: pointer;
                    color: #9ca3af;
                    transition: color 0.2s;
                    z-index: 10;
                }
                .order-modal-close:hover {
                    color: #4b5563;
                }
                .order-modal-content {
                    padding: 1.5rem;
                }
                /* Custom scrollbar for modal */
                .order-modal-container::-webkit-scrollbar {
                    width: 6px;
                }
                .order-modal-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }
                .order-modal-container::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 10px;
                }
                .order-modal-container::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                .animate-slide-in {
                    animation: slideIn 0.3s ease-out;
                }
            `;
            document.head.appendChild(style);
            
            // Close on outside click
            const overlay = document.getElementById('orderModal');
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) closeOrderModal();
            });
        }
        
        function openOrderModal(orderId) {
            createModalIfNotExists();
            
            const modal = document.getElementById('orderModal');
            const modalContent = document.getElementById('modalContent');
            
            if (!modal || !modalContent) return;
            
            // Show modal with flex centering
            modal.style.display = 'flex';
            
            // Show loading state
            modalContent.innerHTML = '<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div></div>';
            
            // Fetch order details
            fetch(`/admin/orders/${orderId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    const product = order.product || {};
                    const createdDate = new Date(order.created_at);
                    const currentStatus = order.status || 'pending';
                    
                    const statusColors = {
                        pending: 'bg-amber-100 text-amber-800 border-amber-300',
                        processing: 'bg-blue-100 text-blue-800 border-blue-300',
                        completed: 'bg-emerald-100 text-emerald-800 border-emerald-300',
                        cancelled: 'bg-rose-100 text-rose-800 border-rose-300'
                    };
                    
                    const statusIcons = {
                        pending: 'bi-clock-history',
                        processing: 'bi-arrow-repeat',
                        completed: 'bi-check-circle',
                        cancelled: 'bi-x-circle'
                    };
                    
                    modalContent.innerHTML = `
                        <div class="mb-6 pb-3 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                <i class="bi bi-receipt mr-2 text-blue-600"></i>Order Details
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColors[currentStatus]} border">
                                <i class="bi ${statusIcons[currentStatus]} mr-1"></i>
                                ${currentStatus.toUpperCase()}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Order ID</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1">#${order.id}</p>
                                <p class="text-xs text-gray-400 mt-2"><i class="bi bi-calendar3 mr-1"></i>${createdDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2 mb-3"><i class="bi bi-person-circle text-blue-600"></i>Customer</h4>
                                <p class="text-sm font-medium text-gray-800">${escapeHtml(order.customer_name)}</p>
                                <p class="text-xs text-gray-500 break-all">${escapeHtml(order.customer_email)}</p>
                                <p class="text-xs text-gray-500 mt-1">${escapeHtml(order.customer_phone || 'N/A')}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-700 text-sm flex items-center gap-2 mb-3"><i class="bi bi-box-seam text-emerald-600"></i>Product</h4>
                                <p class="text-sm font-medium text-gray-800">${escapeHtml(product.name || 'N/A')}</p>
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-gray-500">Qty: <strong class="text-gray-800">${order.quantity}</strong></span>
                                    <span class="text-xs text-gray-500">₱${parseFloat(product.price || 0).toFixed(2)} ea</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1"><i class="bi bi-geo-alt mr-1"></i>Delivery Address</p>
                            <p class="text-sm text-gray-700">${escapeHtml(order.customer_address || 'N/A')}</p>
                        </div>
                        
                        <div class="mt-4 bg-emerald-50 rounded-xl p-4 border-l-4 border-emerald-500 flex justify-between items-center">
                            <p class="text-sm font-semibold text-gray-700">TOTAL AMOUNT</p>
                            <p class="text-2xl font-bold text-emerald-700">₱${parseFloat(order.total_price).toFixed(2)}</p>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-600 mb-3">Update Status</p>
                            <div class="flex flex-wrap gap-2">
                                <button id="btn-pending" class="px-4 py-2 rounded-lg text-sm font-medium transition-all bg-amber-500 hover:bg-amber-600 text-white shadow-sm flex items-center gap-2"><i class="bi bi-clock-history"></i> Pending</button>
                                <button id="btn-processing" class="px-4 py-2 rounded-lg text-sm font-medium transition-all bg-blue-500 hover:bg-blue-600 text-white shadow-sm flex items-center gap-2"><i class="bi bi-arrow-repeat"></i> Processing</button>
                                <button id="btn-completed" class="px-4 py-2 rounded-lg text-sm font-medium transition-all bg-emerald-500 hover:bg-emerald-600 text-white shadow-sm flex items-center gap-2"><i class="bi bi-check-circle"></i> Completed</button>
                                <button id="btn-cancel" class="px-4 py-2 rounded-lg text-sm font-medium transition-all bg-rose-500 hover:bg-rose-600 text-white shadow-sm flex items-center gap-2"><i class="bi bi-x-circle"></i> Cancel</button>
                            </div>
                        </div>
                    `;
                    
                    initializeModalActions(orderId, currentStatus);
                } else {
                    modalContent.innerHTML = '<div class="text-center py-12"><i class="bi bi-exclamation-circle text-rose-500 text-5xl mb-3 block"></i><p class="text-gray-600">Error loading order</p><button onclick="closeOrderModal()" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded-lg">Close</button></div>';
                }
            })
            .catch(() => {
                modalContent.innerHTML = '<div class="text-center py-12"><i class="bi bi-wifi-off text-rose-500 text-5xl mb-3 block"></i><p class="text-gray-600">Network error. Please try again.</p><button onclick="closeOrderModal()" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded-lg">Close</button></div>';
            });
        }

        function closeOrderModal() {
            const modal = document.getElementById('orderModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOrderModal();
            }
        });

        // ---------- Order CRUD ----------
        function deleteOrder(orderId) {
            showConfirmDialog('Delete this order? This cannot be undone.', () => {
                fetch(`/admin/orders/${orderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Order deleted', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification('Error deleting order', 'error');
                    }
                })
                .catch(() => showNotification('Error deleting order', 'error'));
            });
        }

        function updateStatus(orderId, status) {
            showNotification(`Updating to ${status}...`, 'info');
            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAllStatusDropdowns();
                    closeOrderModal();
                    showNotification(`Order #${orderId} updated to ${status}!`, 'success');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showNotification('Error updating status', 'error');
                }
            })
            .catch(() => showNotification('Error updating status', 'error'));
        }

        function cancelOrderAdmin(orderId) {
            showConfirmDialog('Cancel this order?', () => {
                fetch(`/admin/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAllStatusDropdowns();
                        closeOrderModal();
                        showNotification('Order cancelled', 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showNotification('Error cancelling order', 'error');
                    }
                })
                .catch(() => showNotification('Error cancelling order', 'error'));
            });
        }

        function handleModalStatusChange(orderId, status) {
            updateStatus(orderId, status);
        }

        function handleModalCancel(orderId) {
            cancelOrderAdmin(orderId);
        }

        function initializeModalActions(orderId, currentStatus) {
            const pendingBtn = document.getElementById('btn-pending');
            const processingBtn = document.getElementById('btn-processing');
            const completedBtn = document.getElementById('btn-completed');
            const cancelBtn = document.getElementById('btn-cancel');
            
            if (pendingBtn) {
                pendingBtn.addEventListener('click', () => handleModalStatusChange(orderId, 'pending'));
                if (currentStatus === 'pending') pendingBtn.style.opacity = '0.6';
            }
            if (processingBtn) {
                processingBtn.addEventListener('click', () => handleModalStatusChange(orderId, 'processing'));
                if (currentStatus === 'processing') processingBtn.style.opacity = '0.6';
            }
            if (completedBtn) {
                completedBtn.addEventListener('click', () => handleModalStatusChange(orderId, 'completed'));
                if (currentStatus === 'completed') completedBtn.style.opacity = '0.6';
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => handleModalCancel(orderId));
                if (currentStatus === 'cancelled') cancelBtn.style.opacity = '0.6';
            }
        }

        // Expose global functions for HTML buttons
        window.closeAllStatusDropdowns = closeAllStatusDropdowns;
        window.toggleStatusDropdown = toggleStatusDropdown;
        window.toggleSelectAll = toggleSelectAll;
        window.updateBulkDeleteBtn = updateBulkDeleteBtn;
        window.deleteSelected = deleteSelected;
        window.openOrderModal = openOrderModal;
        window.closeOrderModal = closeOrderModal;
        window.deleteOrder = deleteOrder;
        window.updateStatus = updateStatus;
        window.cancelOrderAdmin = cancelOrderAdmin;
    })();
</script>