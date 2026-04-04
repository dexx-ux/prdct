<!-- Create Category Modal -->
<div id="createCategoryModal" class="modal-overlay" style="display: none;">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h2 class="modal-title">
                <i class="fas fa-plus-circle"></i>
                Create New Category
            </h2>
            <button class="modal-close" onclick="closeCreateCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form id="createCategoryForm" action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="create_name">
                        <i class="fas fa-tag"></i> Category Name *
                    </label>
                    <input type="text" id="create_name" name="name" required 
                           placeholder="Enter category name">
                    <div id="create_name_error" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="create_description">
                        <i class="fas fa-align-left"></i> Description
                    </label>
                    <textarea id="create_description" name="description" rows="4" 
                              placeholder="Enter category description (optional)"></textarea>
                </div>
                
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <small>Categories help organize your products and make them easier to find.</small>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeCreateCategoryModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
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
        border-radius: 10px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .modal-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border-radius: 10px 10px 0 0;
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
        color: #1a2c3e;
    }
    
    .modal-title i {
        color: #1a2c3e;
    }
    
    .modal-close {
        background: transparent;
        border: none;
        color: #6b7280;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        background: #f3f4f6;
        color: #1a2c3e;
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-group label i {
        color: #1a2c3e;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.625rem 0.75rem;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #1a2c3e;
        box-shadow: 0 0 0 3px rgba(26, 44, 62, 0.1);
    }
    
    .error-message {
        color: #dc2626;
        font-size: 0.7rem;
        margin-top: 0.25rem;
    }
    
    .info-box {
        background: #fef3c7;
        border-radius: 8px;
        padding: 0.75rem;
        margin: 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #92400e;
    }
    
    .info-box i {
        color: #f59e0b;
    }
    
    .modal-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .btn-cancel,
    .btn-submit {
        flex: 1;
        padding: 0.625rem;
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }
    
    .btn-cancel:hover {
        background: #e5e7eb;
    }
    
    .btn-submit {
        background: #1a2c3e;
        color: white;
    }
    
    .btn-submit:hover {
        background: #0f1e2c;
        transform: translateY(-1px);
    }
    
    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: white;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid #10b981;
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .toast-notification.success { border-left-color: #10b981; }
    .toast-notification.error { border-left-color: #ef4444; }
    
    .dark .modal-container { background: #1f2937; }
    .dark .modal-header { background: #1f2937; border-bottom-color: #374151; }
    .dark .modal-title { color: #f3f4f6; }
    .dark .form-group label { color: #e5e7eb; }
    .dark .form-group input,
    .dark .form-group textarea { background: #374151; border-color: #4b5563; color: #f3f4f6; }
    .dark .btn-cancel { background: #374151; color: #e5e7eb; border-color: #4b5563; }
    .dark .btn-cancel:hover { background: #4b5563; }
    
    @media (max-width: 640px) {
        .modal-buttons { flex-direction: column; }
    }
</style>

<script>
function openCreateCategoryModal() {
    const modal = document.getElementById('createCategoryModal');
    const form = document.getElementById('createCategoryForm');
    if (!modal || !form) return;
    
    form.reset();
    clearCreateCategoryErrors();
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeCreateCategoryModal() {
    const modal = document.getElementById('createCategoryModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        const submitBtn = document.querySelector('#createCategoryModal .btn-submit');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Create Category';
            submitBtn.disabled = false;
        }
    }
}

function clearCreateCategoryErrors() {
    document.querySelectorAll('#createCategoryForm .error-message').forEach(el => el.textContent = '');
    document.querySelectorAll('#createCategoryForm input, #createCategoryForm textarea').forEach(el => el.style.borderColor = '');
}

function showCreateCategoryErrors(errors) {
    clearCreateCategoryErrors();
    for (let field in errors) {
        const input = document.getElementById(`create_${field}`);
        if (input) {
            input.style.borderColor = '#dc2626';
            const errorDiv = document.getElementById(`create_${field}_error`);
            if (errorDiv) errorDiv.textContent = errors[field][0];
        }
    }
}

function showNotification(message, type = 'success') {
    const existing = document.querySelector('.toast-notification');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i><span>${message}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createCategoryForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = this.action;
            const submitBtn = document.querySelector('#createCategoryModal .btn-submit');
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
                    closeCreateCategoryModal();
                    showNotification(data.message || 'Category created successfully!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    if (data.errors) showCreateCategoryErrors(data.errors);
                    else showNotification(data.message || 'An error occurred', 'error');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while creating the category', 'error');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

document.addEventListener('click', function(event) {
    const modal = document.getElementById('createCategoryModal');
    if (modal && modal.style.display === 'flex' && event.target === modal) closeCreateCategoryModal();
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('createCategoryModal');
        if (modal && modal.style.display === 'flex') closeCreateCategoryModal();
    }
});
</script>