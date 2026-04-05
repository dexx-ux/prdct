<!-- Filters Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-6">
    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-3">
        <i class="bi bi-funnel mr-2"></i>Filters & Search
    </h3>
    
    <form id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-3">
        <!-- Order Type Filter - Takes 2 columns -->
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Order Type</label>
            <select id="orderTypeFilter" 
                    name="order_type" 
                    class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-transparent">
                <option value="all">All Orders</option>
                <option value="user">User Orders</option>
                <option value="guest">Guest Orders</option>
            </select>
        </div>

        <!-- Status Filter - Takes 2 columns -->
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select id="statusFilter" 
                    name="status" 
                    class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-transparent">
                <option value="">All Orders</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <!-- Search - Takes 6 columns (adjusted) -->
        <div class="md:col-span-6">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" 
                       id="orderSearch" 
                       name="search"
                       placeholder="Search by Order ID, Customer Name, Email, or Product..." 
                       class="w-full pl-8 pr-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#1a2c3e] focus:border-transparent">
            </div>
        </div>

        <!-- Reset Button - Takes 2 columns -->
        <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">&nbsp;</label>
            <button type="button" 
                    id="resetFiltersBtn"
                    class="w-full px-3 py-1.5 bg-[#1a2c3e] hover:bg-[#0f1e2c] text-white rounded-lg transition font-medium text-sm">
                <i class="bi bi-arrow-counterclockwise mr-1"></i>Reset
            </button>
        </div>
    </form>
</div>

<!-- Orders Table Section - Normal/Compact Size -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg mb-8 w-full">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center flex-wrap gap-3">
        <h3 class="text-base font-bold text-gray-800 dark:text-white">
            <i class="bi bi-table mr-2"></i>All Orders
            <span id="orderCount" class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-2"></span>
        </h3>
        <div class="flex gap-3">
            <button onclick="deleteSelected()" 
                    id="bulkDeleteBtn"
                    class="hidden px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium text-sm">
                <i class="bi bi-trash mr-1"></i><span id="deleteCount">0</span> Delete
            </button>
        </div>
    </div>

    <!-- Scrollable Table Container -->
    <div class="w-full" style="max-height: 480px; overflow-y: auto; overflow-x: auto;">
        <table class="min-w-[800px] md:min-w-full w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-2 text-left w-8">
                        <input type="checkbox" id="selectAll" class="w-3.5 h-3.5 cursor-pointer" onchange="toggleSelectAll(this)">
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-12">Qty</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="ordersTableBody">
                @forelse($paginatedOrders as $order)
                @php
                    $isUserOrder = $order->order_type === 'user';
                    $customerName = $isUserOrder ? ($order->user->name ?? 'N/A') : ($order->customer_name ?? 'N/A');
                    $customerEmail = $isUserOrder ? ($order->user->email ?? 'N/A') : ($order->customer_email ?? 'N/A');
                    $product = $order->items->first()->product ?? null;
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors order-row"
                    data-order-id="{{ $order->id }}"
                    data-order-number="{{ (string)$order->id }}"
                    data-customer-name="{{ strtolower($customerName) }}"
                    data-customer-email="{{ strtolower($customerEmail) }}"
                    data-product-name="{{ strtolower($product->name ?? '') }}"
                    data-status="{{ $order->status ?? 'pending' }}"
                    data-order-type="{{ $order->order_type }}">
                    <td class="px-4 py-2 text-left">
                        <input type="checkbox" class="order-checkbox w-3.5 h-3.5 cursor-pointer" value="{{ $order->id }}" onchange="updateBulkDeleteBtn()">
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs font-medium text-gray-900 dark:text-white">
                        #{{ $order->id }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $order->order_type === 'user' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                            <i class="bi {{ $order->order_type === 'user' ? 'bi-person-fill' : 'bi-person' }} text-xs mr-0.5"></i>
                            {{ ucfirst($order->order_type) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                        <div>
                            <p class="text-gray-900 dark:text-white font-medium text-xs">{{ $customerName }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customerEmail }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            {{ Str::limit($product->name ?? 'N/A', 20) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-center text-xs text-gray-900 dark:text-white">
                        {{ $order->items->sum('quantity') }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-semibold text-gray-900 dark:text-white">
                        ₱{{ number_format($order->total_amount ?? $order->total, 2) }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs">
                        @php
                            $status = $order->status ?? 'pending';
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold status-badge {{ $statusColors[$status] ?? $statusColors['pending'] }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-center text-xs">
                        <div class="flex gap-1.5 justify-center">
                            <button onclick="openOrderModal({{ $order->id }})" 
                                    title="View details"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition p-1">
                                <i class="bi bi-eye text-sm"></i>
                            </button>
                            <!-- Status button - triggers global dropdown -->
                            <button onclick="showGlobalDropdown(event, {{ $order->id }})" 
                                    title="Update status"
                                    class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 transition p-1">
                                <i class="bi bi-arrow-repeat text-sm"></i>
                            </button>
                            <button onclick="deleteOrder({{ $order->id }})" 
                                    title="Delete order"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition p-1">
                                <i class="bi bi-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-cart-x text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No orders found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginatedOrders->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $paginatedOrders->links() }}
        </div>
    @endif
</div>

<!-- Global Dropdown Container - Positioned outside scrollable area -->
<div id="globalDropdownContainer" style="position: fixed; top: 0; left: 0; z-index: 9999; pointer-events: none;"></div>

<script>

    // Toast notification function
function showToast(message, type = 'error') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) existingToast.remove();
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `custom-toast ${type}`;
    toast.innerHTML = `
        <div class="flex items-center gap-2">
            <i class="bi ${type === 'error' ? 'bi-x-circle-fill' : 'bi-check-circle-fill'} text-lg"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'error' ? '#ef4444' : '#22c55e'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        z-index: 99999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease;
        cursor: pointer;
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
    
    // Click to dismiss
    toast.onclick = () => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    };
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .custom-toast {
        transition: all 0.3s ease;
    }
`;
document.head.appendChild(style);


    // Global dropdown management - renders dropdown outside scroll container
    let currentDropdownId = null;
    
    function showGlobalDropdown(event, orderId) {
        event.stopPropagation();
        
        // Check if order is cancelled before showing dropdown
        const row = document.querySelector(`.order-row[data-order-id="${orderId}"]`);
        if (row && row.getAttribute('data-status') === 'cancelled') {
showToast('Cannot modify cancelled orders', 'error');
            return;
        }
        
        // Close any existing dropdown
        closeGlobalDropdown();
        
        // Get position of the clicked button
        const button = event.currentTarget;
        const rect = button.getBoundingClientRect();
        
        const container = document.getElementById('globalDropdownContainer');
        if (!container) return;
        
        const dropdownId = `global-dropdown-${orderId}`;
        
        const dropdownHTML = `
            <div id="${dropdownId}" class="absolute bg-white dark:bg-gray-800 rounded-lg shadow-xl py-1 z-[10000] min-w-[120px] border border-gray-200 dark:border-gray-600"
                 style="top: ${rect.bottom + window.scrollY + 2}px; left: ${rect.left + window.scrollX - 80}px; pointer-events: auto;">
                <button onclick="updateStatusAndClose(${orderId}, 'pending')" 
                        class="w-full text-left px-3 py-1.5 text-xs text-yellow-700 dark:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 whitespace-nowrap">
                    Pending
                </button>
                <button onclick="updateStatusAndClose(${orderId}, 'processing')" 
                        class="w-full text-left px-3 py-1.5 text-xs text-blue-700 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 whitespace-nowrap">
                    Processing
                </button>
                <button onclick="updateStatusAndClose(${orderId}, 'completed')" 
                        class="w-full text-left px-3 py-1.5 text-xs text-green-700 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 whitespace-nowrap">
                    Completed
                </button>
                <hr class="my-1 border-gray-200 dark:border-gray-600">
                <button onclick="cancelOrderAndClose(${orderId})" 
                        class="w-full text-left px-3 py-1.5 text-xs text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 whitespace-nowrap">
                    Cancel Order
                </button>
            </div>
        `;
        
        container.innerHTML = dropdownHTML;
        currentDropdownId = dropdownId;
        
        // Close dropdown when clicking outside
        setTimeout(() => {
            const closeHandler = function(e) {
                const dropdown = document.getElementById(dropdownId);
                if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
                    closeGlobalDropdown();
                    document.removeEventListener('click', closeHandler);
                }
            };
            document.addEventListener('click', closeHandler);
        }, 0);
    }
    
    function closeGlobalDropdown() {
        const container = document.getElementById('globalDropdownContainer');
        if (container) {
            container.innerHTML = '';
        }
        currentDropdownId = null;
    }
    
    function updateStatusAndClose(orderId, newStatus) {
        updateStatus(orderId, newStatus);
        closeGlobalDropdown();
    }
    
    function cancelOrderAndClose(orderId) {
        if (confirm('Are you sure you want to cancel this order?')) {
            updateStatus(orderId, 'cancelled');
        }
        closeGlobalDropdown();
    }
    
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const orderTypeFilter = document.getElementById('orderTypeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const searchInput = document.getElementById('orderSearch');
        const resetBtn = document.getElementById('resetFiltersBtn');
        const ordersTableBody = document.getElementById('ordersTableBody');
        
        function filterOrders() {
            if (!ordersTableBody) return;
            
            const selectedOrderType = orderTypeFilter.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            const rows = ordersTableBody.querySelectorAll('.order-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const orderType = row.getAttribute('data-order-type') || '';
                const orderStatus = row.getAttribute('data-status') || '';
                const customerName = row.getAttribute('data-customer-name') || '';
                const customerEmail = row.getAttribute('data-customer-email') || '';
                const orderNumber = row.getAttribute('data-order-number') || '';
                const productName = row.getAttribute('data-product-name') || '';
                
                let orderTypeMatch = true;
                if (selectedOrderType && selectedOrderType !== 'all') {
                    orderTypeMatch = orderType === selectedOrderType;
                }
                
                let statusMatch = true;
                if (selectedStatus && selectedStatus !== '') {
                    statusMatch = orderStatus === selectedStatus;
                }
                
                let searchMatch = true;
                if (searchTerm) {
                    searchMatch = orderNumber.includes(searchTerm) ||
                                 customerName.includes(searchTerm) ||
                                 customerEmail.includes(searchTerm) ||
                                 productName.includes(searchTerm);
                }
                
                if (orderTypeMatch && statusMatch && searchMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            updateOrderCount(visibleCount, rows.length);
            
            const emptyMessageRow = ordersTableBody.querySelector('.empty-message-row');
            if (visibleCount === 0 && !emptyMessageRow) {
                const tr = document.createElement('tr');
                tr.className = 'empty-message-row';
                tr.innerHTML = `
                    <td colspan="10" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-funnel text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No orders match your filters</p>
                            <button onclick="resetFilters()" class="mt-2 text-xs text-red-600 hover:underline">Clear all filters</button>
                        </div>
                    </td>
                `;
                ordersTableBody.appendChild(tr);
            } else if (visibleCount > 0 && emptyMessageRow) {
                emptyMessageRow.remove();
            }
        }
        
        function updateOrderCount(visible, total) {
            const orderCountSpan = document.getElementById('orderCount');
            if (orderCountSpan) {
                if (visible !== total) {
                    orderCountSpan.textContent = `(${visible} of ${total})`;
                } else {
                    orderCountSpan.textContent = `(${total})`;
                }
            }
        }
        
        function resetFilters() {
            if (orderTypeFilter) orderTypeFilter.value = 'all';
            if (statusFilter) statusFilter.value = '';
            if (searchInput) searchInput.value = '';
            filterOrders();
            const emptyMessageRow = ordersTableBody?.querySelector('.empty-message-row');
            if (emptyMessageRow) emptyMessageRow.remove();
        }
        
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                filterOrders();
            });
        }
        
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => filterOrders(), 300);
            });
        }
        
        if (orderTypeFilter) orderTypeFilter.addEventListener('change', () => filterOrders());
        if (statusFilter) statusFilter.addEventListener('change', () => filterOrders());
        if (resetBtn) resetBtn.addEventListener('click', () => resetFilters());
        
        const totalRows = ordersTableBody?.querySelectorAll('.order-row').length || 0;
        updateOrderCount(totalRows, totalRows);
        
        window.filterOrders = filterOrders;
        window.resetFilters = resetFilters;
    });
    
    function updateStatus(orderId, newStatus) {
        // Check if order is cancelled - prevent any status change
        const row = document.querySelector(`.order-row[data-order-id="${orderId}"]`);
        if (row && row.getAttribute('data-status') === 'cancelled') {
            alert('Cannot modify cancelled orders');
            return;
        }
        
        console.log(`Updating order ${orderId} to ${newStatus}`);
        
        fetch(`/admin/orders/${orderId}/status`, {
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
                const row = document.querySelector(`.order-row[data-order-id="${orderId}"]`);
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
                alert('Status updated successfully');
                if (window.filterOrders) window.filterOrders();
            } else {
                alert('Failed to update status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update status');
        });
    }
    
    function deleteOrder(orderId) {
        if (confirm('Delete this order?')) {
            fetch(`/admin/orders/${orderId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`.order-row[data-order-id="${orderId}"]`);
                    if (row) row.remove();
                    alert('Order deleted');
                    if (window.filterOrders) window.filterOrders();
                }
            })
            .catch(error => alert('Delete failed'));
        }
    }
    
    function deleteSelected() {
        const selected = document.querySelectorAll('.order-checkbox:checked');
        if (selected.length === 0) return;
        if (confirm(`Delete ${selected.length} order(s)?`)) {
            const ids = Array.from(selected).map(cb => cb.value);
            fetch('/admin/orders/bulk-delete', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    ids.forEach(id => {
                        const row = document.querySelector(`.order-row[data-order-id="${id}"]`);
                        if (row) row.remove();
                    });
                    alert(`${ids.length} order(s) deleted`);
                    updateBulkDeleteBtn();
                    if (window.filterOrders) window.filterOrders();
                }
            });
        }
    }
    
    function toggleSelectAll(checkbox) {
        document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = checkbox.checked);
        updateBulkDeleteBtn();
    }
    
    function updateBulkDeleteBtn() {
        const selected = document.querySelectorAll('.order-checkbox:checked');
        const btn = document.getElementById('bulkDeleteBtn');
        const countSpan = document.getElementById('deleteCount');
        if (selected.length > 0 && btn) {
            btn.classList.remove('hidden');
            if (countSpan) countSpan.textContent = selected.length;
        } else if (btn) {
            btn.classList.add('hidden');
        }
    }
    
    function openOrderModal(orderId) {
        alert(`View order #${orderId}`);
    }
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">