// public/js/welcome.js - Complete File

// Cart System
let cart = [];

// Load cart from localStorage
function loadCart() {
    const savedCart = localStorage.getItem('guestCart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('guestCart', JSON.stringify(cart));
}

// Add to cart
function addToCart(productId, productName, price, quantity, imageUrl) {
    const existingItem = cart.find(item => item.product_id == productId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            product_id: productId,
            name: productName,
            price: parseFloat(price),
            quantity: quantity,
            image: imageUrl
        });
    }
    
    saveCart();
    showNotification(`${productName} added to cart!`, 'success');
    updateCartCount();
}

// Update cart count display
function updateCartCount() {
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(el => {
        if (count > 0) {
            el.textContent = count;
            el.style.display = 'inline-block';
        } else {
            el.style.display = 'none';
        }
    });
}

// Show cart modal with quantity controls
function showCartModal() {
    if (cart.length === 0) {
        showNotification('Your cart is empty', 'error');
        return;
    }
    
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    const modalHTML = `
        <div id="cartModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Your Cart (${totalItems} items)</h3>
                    <button class="modal-close" onclick="closeCartModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="cart-items">
                        ${cart.map((item, index) => `
                            <div class="cart-item" data-cart-index="${index}">
                                <div class="cart-item-image">
                                    <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
                                </div>
                                <div class="cart-item-details">
                                    <div class="cart-item-name">${escapeHtml(item.name)}</div>
                                    <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
                                    <div class="cart-item-quantity-controls">
                                        <button class="cart-qty-btn" onclick="updateCartQuantity(${index}, ${item.quantity - 1})">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="cart-qty" id="qty-${index}">${item.quantity}</span>
                                        <button class="cart-qty-btn" onclick="updateCartQuantity(${index}, ${item.quantity + 1})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="cart-item-subtotal">
                                        Subtotal: <strong>₱${(item.price * item.quantity).toFixed(2)}</strong>
                                    </div>
                                </div>
                                <button class="cart-item-remove" onclick="removeFromCart(${index})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                    <div class="cart-total">
                        <strong>Total: ₱${totalPrice.toFixed(2)}</strong>
                    </div>
                    <button class="submit-order-btn" onclick="checkoutCart()">
                        <i class="bi bi-cart-check"></i> Checkout (${totalItems} items)
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeCartModal() {
    const modal = document.getElementById('cartModal');
    if (modal) modal.remove();
}

// Update cart quantity
function updateCartQuantity(index, newQuantity) {
    if (newQuantity < 1) {
        // If quantity becomes 0, remove the item
        removeFromCart(index);
        return;
    }
    
    // Update the quantity in cart array
    cart[index].quantity = newQuantity;
    saveCart();
    updateCartCount();
    
    // Update the display
    const qtySpan = document.getElementById(`qty-${index}`);
    if (qtySpan) {
        qtySpan.textContent = newQuantity;
    }
    
    // Update subtotal for this item
    const cartItem = document.querySelector(`.cart-item[data-cart-index="${index}"]`);
    if (cartItem) {
        const subtotalDiv = cartItem.querySelector('.cart-item-subtotal');
        if (subtotalDiv) {
            const newSubtotal = cart[index].price * newQuantity;
            subtotalDiv.innerHTML = `Subtotal: <strong>₱${newSubtotal.toFixed(2)}</strong>`;
        }
    }
    
    // Update total
    const totalSpan = document.querySelector('.cart-total');
    if (totalSpan) {
        const newTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        totalSpan.innerHTML = `<strong>Total: ₱${newTotal.toFixed(2)}</strong>`;
    }
    
    // Update checkout button text
    const checkoutBtn = document.querySelector('.submit-order-btn');
    if (checkoutBtn) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        checkoutBtn.innerHTML = `<i class="bi bi-cart-check"></i> Checkout (${totalItems} items)`;
    }
    
    // Update header cart count
    updateCartCount();
}

// Remove from cart
function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
    updateCartCount();
    closeCartModal();
    
    // If cart still has items, reopen it
    if (cart.length > 0) {
        showCartModal();
    } else {
        showNotification('Your cart is empty', 'error');
    }
}

function checkoutCart() {
    closeCartModal();
    
    if (cart.length === 0) {
        showNotification('Your cart is empty', 'error');
        return;
    }
    
    if (window.isLoggedIn) {
        showMultiItemOrderForm(cart);
    } else {
        showAuthOptionsForCart(cart);
    }
}

function showAuthOptionsForCart(cartItems) {
    const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    const modalHTML = `
        <div id="authOptionsModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Complete Your Order</h3>
                    <button class="modal-close" onclick="closeAuthOptionsModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="product-summary-compact">
                        <i class="bi bi-cart-fill" style="font-size: 2.5rem; color: var(--dark-blue);"></i>
                        <h4>${cartItems.length} item(s) in cart</h4>
                        <p class="price">Total: ₱${total.toFixed(2)}</p>
                    </div>
                    
                    <p class="auth-message">Choose how you'd like to proceed:</p>
                    
                    <div class="auth-buttons-container">
                        <a href="/login" class="auth-option-btn login-btn">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <div class="btn-content">
                                <span>Login to Account</span>
                                <small>Access your account and order history</small>
                            </div>
                        </a>
                        
                        <a href="/register" class="auth-option-btn register-btn">
                            <i class="bi bi-person-plus"></i>
                            <div class="btn-content">
                                <span>Create New Account</span>
                                <small>Get order tracking and faster checkout</small>
                            </div>
                        </a>
                        
                        <button onclick="continueAsGuestWithCart()" class="auth-option-btn guest-btn">
                            <i class="bi bi-person"></i>
                            <div class="btn-content">
                                <span>Continue as Guest</span>
                                <small>Checkout without creating an account</small>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function continueAsGuestWithCart() {
    closeAuthOptionsModal();
    showMultiItemOrderForm(cart, true);
}

function closeAuthOptionsModal() {
    const modal = document.getElementById('authOptionsModal');
    if (modal) modal.remove();
}

function showMultiItemOrderForm(cartItems, isGuest = false) {
    const existingModal = document.getElementById('orderModal');
    if (existingModal) existingModal.remove();
    
    const userName = window.user?.name || '';
    const userEmail = window.user?.email || '';
    const userPhone = window.user?.phone || '';
    const total = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    const guestNote = isGuest ? `
        <div class="guest-note">
            <i class="bi bi-info-circle-fill"></i>
            <span>You're ordering as a guest. <a href="/register">Create an account</a> for faster future orders.</span>
        </div>
    ` : '';
    
    const modalHTML = `
        <div id="orderModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Place Your Order</h3>
                    <button class="modal-close" onclick="closeOrderModal()">&times;</button>
                </div>
                <div class="modal-body">
                    ${guestNote}
                    
                    <div class="cart-summary">
                        ${cartItems.map(item => `
                            <div class="product-summary" style="margin-bottom: 0.5rem;">
                                <div class="product-image">
                                    <img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}">
                                </div>
                                <div class="product-details">
                                    <h4>${escapeHtml(item.name)}</h4>
                                    <p class="price">₱${item.price.toFixed(2)} x ${item.quantity}</p>
                                    <p>Subtotal: ₱${(item.price * item.quantity).toFixed(2)}</p>
                                </div>
                            </div>
                        `).join('')}
                        <div class="cart-total" style="margin-bottom: 1rem;">
                            Total Amount: ₱${total.toFixed(2)}
                        </div>
                    </div>
                    
                    <form id="orderForm" onsubmit="submitMultiItemOrder(event)">
                        <input type="hidden" name="cart_data" value='${JSON.stringify(cartItems)}'>
                        <input type="hidden" name="total_price" value="${total}">
                        ${isGuest ? '<input type="hidden" name="is_guest" value="1">' : ''}
                        
                        <div class="form-group">
                            <label for="customer_name">Full Name</label>
                            <input type="text" id="customer_name" name="customer_name" value="${escapeHtml(userName)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email Address</label>
                            <input type="email" id="customer_email" name="customer_email" value="${escapeHtml(userEmail)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Phone Number</label>
                            <input type="tel" id="customer_phone" name="customer_phone" value="${escapeHtml(userPhone)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" rows="2" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="cash_on_delivery">💵 Cash on Delivery</option>
                                <option value="gcash">📱 GCash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="2" placeholder="Special instructions"></textarea>
                        </div>
                        
                        <button type="submit" class="submit-order-btn">
                            <i class="bi bi-check-circle"></i> Place Order (₱${total.toFixed(2)})
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

async function submitMultiItemOrder(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const cartData = JSON.parse(formData.get('cart_data'));
    
    const data = {
        items: cartData.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity,
            price: item.price
        })),
        customer_name: formData.get('customer_name'),
        customer_email: formData.get('customer_email'),
        customer_phone: formData.get('customer_phone'),
        shipping_address: formData.get('shipping_address'),
        payment_method: formData.get('payment_method'),
        notes: formData.get('notes') || '',
        is_guest: formData.get('is_guest') === '1',
        total_price: parseFloat(formData.get('total_price'))
    };
    
    const submitBtn = form.querySelector('.submit-order-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    submitBtn.disabled = true;
    
    try {
            const response = await fetch('/order/store-multiple', {

            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });


        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Clear cart
            cart = [];
            saveCart();
            updateCartCount();
            
            showNotification(result.message, 'success');
            closeOrderModal();
            
            setTimeout(() => {
                if (result.redirect) {
                    window.location.href = result.redirect;
                }
            }, 1500);
        } else {
            showNotification(result.message || 'Failed to place order', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

function closeOrderModal() {
    const modal = document.getElementById('orderModal');
    if (modal) modal.remove();
}

// Track Order Functions
function showTrackOrderModal() {
    const modalHTML = `
        <div id="trackOrderModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Track Your Order</h3>
                    <button class="modal-close" onclick="closeTrackOrderModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <p style="margin-bottom: 1rem; color: var(--text-muted);">Enter your order number and email to track your order.</p>
                    <form id="trackOrderForm" onsubmit="trackOrder(event)">
                        <div class="form-group">
                            <label for="order_number">Order Number</label>
                            <input type="text" id="order_number" name="order_number" required placeholder="e.g., #123">
                        </div>
                        <div class="form-group">
                            <label for="track_email">Email Address</label>
                            <input type="email" id="track_email" name="email" required placeholder="Enter your email">
                        </div>
                        <button type="submit" class="submit-order-btn">
                            <i class="bi bi-search"></i> Track Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

async function trackOrder(event) {
    event.preventDefault();
    
    const form = event.target;
    const orderNumber = form.querySelector('#order_number').value;
    const email = form.querySelector('#track_email').value;
    
    const submitBtn = form.querySelector('.submit-order-btn');
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Searching...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(`/order/track?order_number=${orderNumber}&email=${email}`);
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeTrackOrderModal();
            showOrderStatusModal(result.order);
        } else {
            showNotification(result.message || 'Order not found', 'error');
            submitBtn.innerHTML = '<i class="bi bi-search"></i> Track Order';
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Error tracking order', 'error');
        submitBtn.innerHTML = '<i class="bi bi-search"></i> Track Order';
        submitBtn.disabled = false;
    }
}

function showOrderStatusModal(order) {
    const modalHTML = `
        <div id="orderStatusModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Order Status</h3>
                    <button class="modal-close" onclick="closeOrderStatusModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="product-summary-compact">
                        <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                        <h4>Order #${order.id}</h4>
                        <p class="price">Status: ${order.status.toUpperCase()}</p>
                        <p>Date: ${new Date(order.created_at).toLocaleDateString()}</p>
                        <p>Total: ₱${parseFloat(order.total_price).toFixed(2)}</p>
                    </div>
                    
                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 0.5rem;">Items:</h4>
                        ${order.items.map(item => `
                            <div style="display: flex; justify-content: space-between; padding: 0.5rem; border-bottom: 1px solid var(--border-light);">
                                <span>${escapeHtml(item.product_name)} x ${item.quantity}</span>
                                <span>₱${parseFloat(item.subtotal).toFixed(2)}</span>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div style="margin-top: 1rem; padding: 0.75rem; background: var(--bg-light); border-radius: 8px;">
                        <strong>Shipping Address:</strong><br>
                        ${escapeHtml(order.shipping_address)}
                    </div>
                    
                    <div style="margin-top: 1rem; padding: 0.75rem; background: var(--bg-light); border-radius: 8px;">
                        <strong>Payment Method:</strong><br>
                        ${escapeHtml(order.payment_method)}
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

function closeTrackOrderModal() {
    const modal = document.getElementById('trackOrderModal');
    if (modal) modal.remove();
}

function closeOrderStatusModal() {
    const modal = document.getElementById('orderStatusModal');
    if (modal) modal.remove();
}

// Product modal functions
function openOrderModal(productId, productName, price, quantity, productImage) {
    if (window.isLoggedIn) {
        showSingleItemOrderForm(productId, productName, parseFloat(price), quantity, productImage);
    } else {
        showAddToCartModal(productId, productName, parseFloat(price), quantity, productImage);
    }
}

function showAddToCartModal(productId, productName, price, quantity, productImage) {
    const modalHTML = `
        <div id="addToCartModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Add to Cart</h3>
                    <button class="modal-close" onclick="closeAddToCartModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="product-summary-compact">
                        <div class="product-image">
                            <img src="${escapeHtml(productImage)}" alt="${escapeHtml(productName)}">
                        </div>
                        <h4>${escapeHtml(productName)}</h4>
                        <p class="price">₱${price.toFixed(2)}</p>
                        <p class="stock-info">Available: ${quantity} units</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="cart_quantity">Quantity</label>
                        <input type="number" id="cart_quantity" value="1" min="1" max="${quantity}">
                    </div>
                    
                    <div class="auth-buttons-container" style="margin-top: 1rem;">
                        <button onclick="addToCartAndContinue(${productId}, '${escapeHtml(productName)}', ${price}, '${escapeHtml(productImage)}')" class="auth-option-btn login-btn">
                            <i class="bi bi-cart-plus"></i>
                            <div class="btn-content">
                                <span>Add to Cart</span>
                                <small>Continue shopping</small>
                            </div>
                        </button>
                        
                        <button onclick="addToCartAndCheckout(${productId}, '${escapeHtml(productName)}', ${price}, '${escapeHtml(productImage)}')" class="auth-option-btn register-btn">
                            <i class="bi bi-bag-check"></i>
                            <div class="btn-content">
                                <span>Buy Now</span>
                                <small>Proceed to checkout</small>
                            </div>
                        </button>
                        
                        <a href="/login" class="auth-option-btn guest-btn">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <div class="btn-content">
                                <span>Login to Account</span>
                                <small>For faster checkout</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const quantityInput = document.getElementById('cart_quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const qty = parseInt(this.value) || 1;
            if (qty > quantity) this.value = quantity;
            if (qty < 1) this.value = 1;
        });
    }
}

function addToCartAndContinue(productId, productName, price, productImage) {
    const quantity = parseInt(document.getElementById('cart_quantity').value);
    addToCart(productId, productName, price, quantity, productImage);
    closeAddToCartModal();
}

function addToCartAndCheckout(productId, productName, price, productImage) {
    const quantity = parseInt(document.getElementById('cart_quantity').value);
    addToCart(productId, productName, price, quantity, productImage);
    closeAddToCartModal();
    showCartModal();
}

function closeAddToCartModal() {
    const modal = document.getElementById('addToCartModal');
    if (modal) modal.remove();
}

function showSingleItemOrderForm(productId, productName, price, quantity, productImage) {
    const existingModal = document.getElementById('orderModal');
    if (existingModal) existingModal.remove();
    
    const userName = window.user?.name || '';
    const userEmail = window.user?.email || '';
    const userPhone = window.user?.phone || '';
    
    const modalHTML = `
        <div id="orderModal" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Place Your Order</h3>
                    <button class="modal-close" onclick="closeOrderModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="product-summary">
                        <div class="product-image">
                            <img src="${escapeHtml(productImage)}" alt="${escapeHtml(productName)}">
                        </div>
                        <div class="product-details">
                            <h4>${escapeHtml(productName)}</h4>
                            <p class="price">₱${price.toFixed(2)}</p>
                            <p class="stock-info">Available: ${quantity} units</p>
                        </div>
                    </div>
                    
                    <form id="orderForm" onsubmit="submitSingleOrder(event)">
                        <input type="hidden" name="product_id" value="${productId}">
                        <input type="hidden" name="price" value="${price}">
                        
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="${quantity}" required>
                            <span class="subtotal">Total: ₱${price.toFixed(2)}</span>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_name">Full Name</label>
                            <input type="text" id="customer_name" name="customer_name" value="${escapeHtml(userName)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email Address</label>
                            <input type="email" id="customer_email" name="customer_email" value="${escapeHtml(userEmail)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Phone Number</label>
                            <input type="tel" id="customer_phone" name="customer_phone" value="${escapeHtml(userPhone)}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" rows="2" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="cash_on_delivery">💵 Cash on Delivery</option>
                                <option value="gcash">📱 GCash</option>
                                <option value="bank_transfer">🏦 Bank Transfer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Order Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="2" placeholder="Special instructions"></textarea>
                        </div>
                        
                        <button type="submit" class="submit-order-btn">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const qty = parseInt(this.value) || 1;
            const total = (price * qty).toFixed(2);
            const subtotalSpan = this.closest('.form-group').querySelector('.subtotal');
            if (subtotalSpan) subtotalSpan.textContent = `Total: ₱${total}`;
        });
    }
}

async function submitSingleOrder(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    const data = {
        product_id: formData.get('product_id'),
        quantity: parseInt(formData.get('quantity')),
        customer_name: formData.get('customer_name'),
        customer_email: formData.get('customer_email'),
        customer_phone: formData.get('customer_phone'),
        shipping_address: formData.get('shipping_address'),
        payment_method: formData.get('payment_method'),
        notes: formData.get('notes') || '',
        price: parseFloat(formData.get('price')),
        total_price: parseFloat(formData.get('price')) * parseInt(formData.get('quantity'))
    };
    
    const submitBtn = form.querySelector('.submit-order-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('/order/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(result.message, 'success');
            closeOrderModal();
            setTimeout(() => {
                if (result.redirect) window.location.href = result.redirect;
            }, 1500);
        } else {
            showNotification(result.message || 'Failed to place order', 'error');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        showNotification('Network error. Please try again.', 'error');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Helper functions
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type) {
    const existingNotif = document.getElementById('notification');
    if (existingNotif) existingNotif.remove();
    
    const notification = document.createElement('div');
    notification.id = 'notification';
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i>
        <span>${escapeHtml(message)}</span>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    updateCartCount();
    
    // Add cart button to header
    const authButtons = document.querySelector('.auth-buttons');
    const trackButton = document.createElement('button');
    trackButton.className = 'btn-login';
    trackButton.innerHTML = '<i class="bi bi-truck"></i> Track Order';
    trackButton.onclick = () => showTrackOrderModal();
    
    const cartButton = document.createElement('button');
    cartButton.className = 'btn-login';
    cartButton.innerHTML = '<i class="bi bi-cart"></i> Cart <span class="cart-count" style="display: none; background: var(--dark-blue); color: white; border-radius: 50%; padding: 0 0.4rem; margin-left: 0.3rem; font-size: 0.7rem;">0</span>';
    cartButton.onclick = () => showCartModal();
    
    authButtons.insertBefore(trackButton, authButtons.firstChild);
    authButtons.insertBefore(cartButton, authButtons.firstChild);
    
    updateCartCount();
    
    // Filter and sort functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const products = document.querySelectorAll('.product-card');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortSelect = document.getElementById('sortSelect');
    const scrollTopBtn = document.getElementById('scrollTopBtn');
    
    function applyAllFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value.toLowerCase();
        const currentFilter = document.querySelector('.filter-btn.active').dataset.filter;
        
        let visibleCount = 0;
        
        products.forEach(product => {
            const name = product.dataset.name.toLowerCase();
            const category = product.dataset.category.toLowerCase();
            const hasDiscount = product.dataset.discount === '1';
            const quantity = parseInt(product.dataset.quantity);
            const isLowStock = quantity > 0 && quantity <= 5;
            const isInStock = quantity > 0;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;
            
            let matchesStatus = false;
            if (currentFilter === 'all') matchesStatus = true;
            else if (currentFilter === 'sale') matchesStatus = hasDiscount;
            else if (currentFilter === 'lowstock') matchesStatus = isLowStock;
            else if (currentFilter === 'instock') matchesStatus = isInStock;
            
            const isVisible = matchesSearch && matchesCategory && matchesStatus;
            product.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        const resultsCountSpan = document.getElementById('resultsCount');
        if (resultsCountSpan) {
            resultsCountSpan.textContent = `Showing ${visibleCount} product${visibleCount !== 1 ? 's' : ''}`;
        }
        applySort();
    }
    
    function applySort() {
        const sortType = sortSelect.value;
        const grid = document.getElementById('productsGrid');
        const productsArray = Array.from(products).filter(p => p.style.display !== 'none');
        
        productsArray.sort((a, b) => {
            switch(sortType) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'popular':
                    return parseInt(b.dataset.quantity) - parseInt(a.dataset.quantity);
                default:
                    return parseInt(b.dataset.productId) - parseInt(a.dataset.productId);
            }
        });
        
        productsArray.forEach(product => grid.appendChild(product));
    }
    
    if (filterBtns.length) {
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                applyAllFilters();
            });
        });
    }
    
    if (searchInput) searchInput.addEventListener('keyup', applyAllFilters);
    if (categoryFilter) categoryFilter.addEventListener('change', applyAllFilters);
    if (sortSelect) sortSelect.addEventListener('change', applyAllFilters);
    
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });
        
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
}
});