{{-- resources/views/components/order-modal.blade.php --}}
<div id="orderModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-shopping-cart"></i>
                Guest Order
            </h2>
            <button class="modal-close" onclick="closeOrderModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="product-summary" id="modalProductSummary">
                <!-- Product info will be inserted here -->
            </div>
            
            <form id="orderForm" onsubmit="submitGuestOrder(event)">
                @csrf
                <input type="hidden" name="product_id" id="product_id">
                <input type="hidden" name="product_price" id="product_price">
                
                <div class="form-group">
                    <label for="customer_name">
                        <i class="fas fa-user"></i> Full Name *
                    </label>
                    <input type="text" id="customer_name" name="customer_name" required 
                           placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="customer_email">
                        <i class="fas fa-envelope"></i> Email Address *
                    </label>
                    <input type="email" id="customer_email" name="customer_email" required 
                           placeholder="your@email.com">
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">
                        <i class="fas fa-phone"></i> Phone Number *
                    </label>
                    <input type="tel" id="customer_phone" name="customer_phone" required 
                           placeholder="+63 XXX XXX XXXX">
                </div>
                
                <div class="form-group">
                    <label for="customer_address">
                        <i class="fas fa-location-dot"></i> Delivery Address *
                    </label>
                    <textarea id="customer_address" name="customer_address" required 
                              rows="3" placeholder="Enter your complete address"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="quantity">
                            <i class="fas fa-boxes"></i> Quantity *
                        </label>
                        <input type="number" id="quantity" name="quantity" required 
                               min="1" value="1" onchange="updateTotalPrice()">
                    </div>
                    
                    <div class="form-group">
                        <label for="total_price">
                            <i class="fas fa-tag"></i> Total Amount
                        </label>
                        <div class="total-price-display" id="totalPriceDisplay">₱0.00</div>
                        <input type="hidden" id="total_price" name="total_price">
                    </div>
                </div>
                
                <div class="order-notes">
                    <i class="fas fa-info-circle"></i>
                    <small>Guest orders are processed within 24 hours. You will receive an email confirmation.</small>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeOrderModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .modal-container {
        background: white;
        border-radius: 20px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #991b1b 0%, #7f1a1a 100%);
        color: white;
        border-radius: 20px 20px 0 0;
    }
    
    .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }
    
    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 1.5rem;
        max-height: calc(90vh - 200px);
        overflow-y: auto;
    }
    
    .product-summary {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #991b1b;
    }
    
    .product-summary h3 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1a1a1a;
    }
    
    .product-summary p {
        font-size: 0.875rem;
        color: #6c757d;
        margin: 0.25rem 0;
    }
    
    .product-summary .price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #000000;;
        margin-top: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    
    .form-group label i {
        margin-right: 0.5rem;
        color: #991b1b;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #991b1b;
        box-shadow: 0 0 0 3px rgba(153, 27, 27, 0.1);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .total-price-display {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000000;
        padding: 0.75rem;
        background: #fef5f5;
        border-radius: 8px;
        text-align: center;
    }
    
    .order-notes {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 0.75rem;
        border-radius: 8px;
        margin: 1.5rem 0;
        font-size: 0.75rem;
        color: #856404;
    }
    
    .order-notes i {
        margin-right: 0.5rem;
    }
    
    .modal-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-cancel,
    .btn-submit {
        flex: 1;
        padding: 0.75rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-cancel {
        background: #e9ecef;
        color: #495057;
    }
    
    .btn-cancel:hover {
        background: #dee2e6;
        transform: translateY(-1px);
    }
    
    .btn-submit {
        background: #991b1b;
        color: white;
    }
    
    .btn-submit:hover {
        background: #7f1a1a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(153, 27, 27, 0.3);
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 1rem;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid #10b981;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .toast-notification.success {
        border-left-color: #10b981;
    }
    
    .toast-notification.error {
        border-left-color: #ef4444;
    }
    
    .toast-notification i {
        font-size: 1.5rem;
    }
    
    .toast-notification.success i {
        color: #10b981;
    }
    
    .toast-notification.error i {
        color: #ef4444;
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .toast-notification {
            bottom: 1rem;
            right: 1rem;
            left: 1rem;
        }
    }
</style>
<script>
    let currentProduct = null;
    let unitPrice = 0;
    
    function openOrderModal(productId, productName, price, quantity) {
        currentProduct = {
            id: productId,
            name: productName,
            price: price,
            maxQuantity: quantity
        };
        unitPrice = price;
        
        // Populate product summary
        document.getElementById('modalProductSummary').innerHTML = `
            <h3><i class="fas fa-box"></i> ${escapeHtml(productName)}</h3>
            <p>Unit Price: ₱${formatPrice(price)}</p>
            <p>Available Stock: ${quantity} pcs</p>
            <div class="price">Total: ₱${formatPrice(price)}</div>
        `;
        
        document.getElementById('product_id').value = productId;
        document.getElementById('product_price').value = price;
        document.getElementById('quantity').max = quantity;
        document.getElementById('quantity').value = 1;
        
        updateTotalPrice();
        
        // Show modal
        document.getElementById('orderModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeOrderModal() {
        document.getElementById('orderModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        document.getElementById('orderForm').reset();
    }
    
    function updateTotalPrice() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const total = unitPrice * quantity;
        document.getElementById('totalPriceDisplay').innerHTML = `₱${formatPrice(total)}`;
        document.getElementById('total_price').value = total;
    }
    
    function formatPrice(price) {
        return parseFloat(price).toFixed(2);
    }
    
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        toast.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
    
    async function submitGuestOrder(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const orderData = {
            customer_name: formData.get('customer_name'),
            customer_email: formData.get('customer_email'),
            customer_phone: formData.get('customer_phone'),
            customer_address: formData.get('customer_address'),
            product_id: formData.get('product_id'),
            quantity: parseInt(formData.get('quantity')),
            total_price: parseFloat(formData.get('total_price'))
        };
        
        // Validate
        if (!orderData.customer_name || !orderData.customer_email || 
            !orderData.customer_phone || !orderData.customer_address) {
            showToast('Please fill in all required fields', 'error');
            return;
        }
        
        if (orderData.quantity < 1 || orderData.quantity > currentProduct.maxQuantity) {
            showToast(`Invalid quantity. Maximum available: ${currentProduct.maxQuantity}`, 'error');
            return;
        }
        
        // Show loading state
        const submitBtn = document.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;
        
        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Send order to backend
            const response = await fetch('/guest/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(orderData)
            });
            
            // Check if response is ok
            if (!response.ok) {
                // Try to get error message from response
                let errorMessage = 'Failed to place order';
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    // If response is not JSON, get text
                    errorMessage = await response.text();
                }
                throw new Error(errorMessage);
            }
            
            // Parse JSON response
            const result = await response.json();
            
            if (result.success) {
                showToast('Order placed successfully! Check your email for confirmation.');
                closeOrderModal();
                // Optional: Reload page to update stock display
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                throw new Error(result.message || 'Failed to place order');
            }
        } catch (error) {
            console.error('Order error:', error);
            showToast(error.message || 'Failed to place order. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('orderModal');
        if (event.target === modal) {
            closeOrderModal();
        }
    });
    
    // Handle escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeOrderModal();
        }
    });
</script>