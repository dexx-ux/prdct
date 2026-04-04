<!-- Create Product Modal -->
<div id="createProductModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-plus-circle"></i>
                Add New Product
            </h2>
            <button class="modal-close" onclick="closeCreateModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="createProductForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Product Name -->
                <div class="form-group">
                    <label for="create_name">
                        <i class="fas fa-tag"></i> Product Name *
                    </label>
                    <input type="text" id="create_name" name="name" required 
                           placeholder="Enter product name">
                    <div id="create_name_error" class="error-message"></div>
                </div>
                
                <!-- Image Upload with Preview Beside -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-camera"></i> Product Image
                    </label>
                    <div class="image-upload-wrapper">
                        <!-- Left Side: File Input -->
                        <div class="image-upload-input">
                            <input type="file" id="create_image" name="image" accept="image/*" 
                                   class="file-input" onchange="previewImage(this)">
                            <small class="form-text">Optional: JPEG, PNG, JPG, GIF (max 2MB)</small>
                        </div>
                        
                        <!-- Right Side: Image Preview Box -->
                        <div id="imagePreview" class="image-preview-box">
                            <div class="preview-placeholder">
                                <i class="fas fa-image"></i>
                                <p>No image</p>
                            </div>
                            <img id="previewImg" src="" alt="Preview" style="display: none;">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="create_description">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea id="create_description" name="description" rows="3" 
                              placeholder="Enter product description"></textarea>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="create_category_id">
                        <i class="fas fa-tag"></i> Category
                    </label>
                    <select id="create_category_id" name="category_id">
                        <option value="">-- Select Category --</option>
                    </select>
                </div>
                
                <!-- Stock & Price Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="create_quantity">
                            <i class="fas fa-boxes"></i> Stock Quantity *
                        </label>
                        <input type="number" id="create_quantity" name="quantity" required 
                               min="0" value="0" placeholder="Enter quantity">
                    </div>
                    
                    <div class="form-group">
                        <label for="create_price">
                            <i class="fas fa-money-bill"></i> Price (₱) *
                        </label>
                        <input type="number" id="create_price" name="price" required 
                               step="0.01" min="0" placeholder="Enter price">
                        <div id="create_price_error" class="error-message"></div>
                    </div>
                </div>
                
                <!-- Discount -->
                <div class="form-group">
                    <label for="create_discount_value">
                        <i class="fas fa-percent"></i> Discount
                    </label>
                    <input type="text" id="create_discount_value" name="discount_value" 
                           placeholder="e.g. 10 or 10%">
                    <small class="form-text">Optional: Enter amount (e.g., 50) or percentage (e.g., 10%)</small>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <small>Products will be available for guest orders immediately after creation.</small>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeCreateModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Product
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
        max-width: 700px;
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
        background: linear-gradient(135deg, #1a2c3e 0%, #0f1e2c 100%);
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
        color: #1a2c3e;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #1a2c3e;
        box-shadow: 0 0 0 3px rgba(26, 44, 62, 0.1);
    }
    
    /* Image Upload Wrapper - Flex layout for side by side */
    .image-upload-wrapper {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .image-upload-input {
        flex: 1;
    }
    
    .image-preview-box {
        width: 120px;
        height: 120px;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        transition: all 0.2s;
        overflow: hidden;
    }
    
    .preview-placeholder {
        text-align: center;
        color: #9ca3af;
    }
    
    .preview-placeholder i {
        font-size: 2rem;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .preview-placeholder p {
        font-size: 0.7rem;
        margin: 0;
    }
    
    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.7rem;
        color: #6c757d;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #1a2c3e;
        padding: 0.75rem;
        border-radius: 8px;
        margin: 1rem 0;
        font-size: 0.75rem;
        color: #004085;
    }
    
    .info-box i {
        margin-right: 0.5rem;
        color: #1a2c3e;
    }
    
    .modal-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .btn-cancel,
    .btn-submit {
        flex: 1;
        padding: 0.75rem;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-cancel {
        background: #e9ecef;
        color: #495057;
    }
    
    .btn-cancel:hover {
        background: #dee2e6;
    }
    
    .btn-submit {
        background: #1a2c3e;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-submit:hover {
        background: #0f1e2c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 44, 62, 0.3);
    }
    
    .file-input {
        display: block;
        width: 100%;
        padding: 0.5rem;
        border: 2px dashed #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.8rem;
    }
    
    .file-input:hover {
        border-color: #1a2c3e;
        background: rgba(26, 44, 62, 0.05);
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
    
    /* Dark mode */
    .dark .modal-container {
        background: #1f2937;
    }
    
    .dark .form-group label {
        color: #e5e7eb;
    }
    
    .dark .form-group input,
    .dark .form-group textarea,
    .dark .form-group select {
        background: #374151;
        border-color: #4b5563;
        color: #f3f4f6;
    }
    
    .dark .form-group input:focus,
    .dark .form-group textarea:focus,
    .dark .form-group select:focus {
        border-color: #1a2c3e;
    }
    
    .dark .btn-cancel {
        background: #374151;
        color: #e5e7eb;
    }
    
    .dark .btn-cancel:hover {
        background: #4b5563;
    }
    
    .dark .info-box {
        background: #1e293b;
        color: #94a3b8;
    }
    
    .dark .image-preview-box {
        background: #374151;
        border-color: #4b5563;
    }
    
    .dark .preview-placeholder {
        color: #6b7280;
    }
    
    .dark .file-input:hover {
        border-color: #1a2c3e;
        background: rgba(26, 44, 62, 0.1);
    }
    
    @media (max-width: 640px) {
        .image-upload-wrapper {
            flex-direction: column;
        }
        
        .image-preview-box {
            width: 100%;
            height: 150px;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .modal-buttons {
            flex-direction: column;
        }
    }
</style>

<script>
function openCreateModal() {
    const modal = document.getElementById('createProductModal');
    const form = document.getElementById('createProductForm');
    
    // Reset form
    form.reset();
    clearCreateErrors();
    
    // Reset image preview
    const previewBox = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const placeholder = document.querySelector('.preview-placeholder');
    
    if (placeholder) placeholder.style.display = 'block';
    if (previewImg) {
        previewImg.style.display = 'none';
        previewImg.src = '';
    }
    
    // Load categories
    loadCategories('create_category_id');
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function loadCategories(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    fetch('/admin/categories/list', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(categories => {
        while (select.options.length > 1) {
            select.remove(1);
        }
        
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error loading categories:', error);
    });
}

function closeCreateModal() {
    const modal = document.getElementById('createProductModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

function clearCreateErrors() {
    document.querySelectorAll('#createProductForm .error-message').forEach(el => {
        el.textContent = '';
    });
    document.querySelectorAll('#createProductForm input, #createProductForm textarea, #createProductForm select').forEach(el => {
        el.style.borderColor = '#e9ecef';
    });
}

function showCreateErrors(errors) {
    clearCreateErrors();
    
    for (let field in errors) {
        const input = document.getElementById(`create_${field}`);
        if (input) {
            input.style.borderColor = '#dc3545';
            const errorDiv = document.getElementById(`create_${field}_error`);
            if (errorDiv) {
                errorDiv.textContent = errors[field][0];
            }
        }
    }
}

function showNotification(message, type = 'success') {
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

// Image preview function
function previewImage(input) {
    const previewBox = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const placeholder = document.querySelector('.preview-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            if (placeholder) placeholder.style.display = 'none';
            previewImg.style.display = 'block';
            previewImg.src = e.target.result;
            previewBox.style.borderColor = '#10b981';
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if (placeholder) placeholder.style.display = 'block';
        previewImg.style.display = 'none';
        previewImg.src = '';
        previewBox.style.borderColor = '#e5e7eb';
    }
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createProductForm');
    
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            
            const submitBtn = document.querySelector('#createProductModal .btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
            submitBtn.disabled = true;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeCreateModal();
                    showNotification(data.message || 'Product created successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    if (data.errors) {
                        showCreateErrors(data.errors);
                    } else {
                        showNotification(data.message || 'An error occurred', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while creating the product', 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('createProductModal');
    if (modal && event.target === modal) {
        closeCreateModal();
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const createModal = document.getElementById('createProductModal');
        if (createModal && createModal.style.display !== 'none') {
            closeCreateModal();
        }
    }
});
</script>