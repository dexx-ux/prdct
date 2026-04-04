<!-- ================= DELETE CATEGORY MODAL ================= -->

<!-- MODAL -->
<div id="deleteCategoryModal" class="delete-modal-overlay" style="display: none;">
    <div class="delete-modal-container">

        <!-- CLOSE BUTTON -->
        <button class="delete-modal-close" onclick="closeDeleteCategoryModal()">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="delete-modal-content">

            <div class="delete-icon-wrapper">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <h3 class="delete-modal-title">Delete Category</h3>

            <p class="delete-modal-message">
                Are you sure you want to delete this category? This action cannot be undone.
            </p>

            <div id="deleteCategoryInfo" class="delete-product-info">
                <i class="bi bi-folder"></i>
                <span id="deleteCategoryName">Loading category...</span>
            </div>

            <div class="delete-warning-note">
                <i class="bi bi-info-circle-fill"></i>
                <small>Deleting this category will affect all products that belong to it.</small>
            </div>

            <!-- FORM -->
            <form id="deleteCategoryForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="delete-modal-buttons">
                    <button type="button" class="delete-btn-cancel" onclick="closeDeleteCategoryModal()">
                        Cancel
                    </button>

                    <button type="submit" class="delete-btn-confirm">
                        <i class="bi bi-trash3"></i> Delete Category
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ================= STYLES ================= -->
<style>
.delete-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
    z-index: 10002;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-modal-container {
    position: relative;
    background: white;
    border-radius: 1.5rem;
    width: 90%;
    max-width: 450px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    animation: modalIn 0.25s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(.95); }
    to { opacity: 1; transform: scale(1); }
}

.delete-modal-close {
    position: absolute;
    top: 12px;
    right: 12px;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #9ca3af;
    transition: all 0.2s;
}

.delete-modal-close:hover {
    color: #1a2c3e;
    transform: rotate(90deg);
}

.delete-modal-content {
    padding: 2rem;
    text-align: center;
}

.delete-icon-wrapper {
    width: 64px;
    height: 64px;
    background: #fee2e2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
}

.delete-icon-wrapper i {
    color: #ef4444;
    font-size: 2rem;
}

.delete-modal-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin-top: 10px;
    color: #111827;
}

.delete-modal-message {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 15px;
}

.delete-product-info {
    background: #f9fafb;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 10px;
    display: flex;
    gap: 8px;
    align-items: center;
    border-left: 4px solid #ef4444;
}

.delete-product-info i {
    color: #ef4444;
    font-size: 1.1rem;
}

.delete-product-info span {
    font-weight: 500;
    color: #374151;
}

.delete-warning-note {
    background: #fff3cd;
    padding: 10px;
    border-radius: 10px;
    font-size: 12px;
    margin-bottom: 15px;
    display: flex;
    gap: 8px;
    align-items: center;
    color: #92400e;
}

.delete-warning-note i {
    color: #f59e0b;
}

.delete-modal-buttons {
    display: flex;
    gap: 10px;
}

.delete-btn-cancel {
    flex: 1;
    background: #f3f4f6;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.delete-btn-cancel:hover {
    background: #e5e7eb;
    transform: translateY(-1px);
}

.delete-btn-confirm {
    flex: 1;
    background: #ef4444;
    color: white;
    padding: 10px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.delete-btn-confirm:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.delete-btn-confirm:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Dark Mode */
.dark .delete-modal-container {
    background: #1f2937;
}

.dark .delete-modal-title {
    color: #f3f4f6;
}

.dark .delete-modal-message {
    color: #9ca3af;
}

.dark .delete-product-info {
    background: #374151;
}

.dark .delete-product-info span {
    color: #e5e7eb;
}

.dark .delete-btn-cancel {
    background: #374151;
    color: #e5e7eb;
    border-color: #4b5563;
}

.dark .delete-btn-cancel:hover {
    background: #4b5563;
}

/* Responsive */
@media (max-width: 640px) {
    .delete-modal-buttons {
        flex-direction: column;
    }
}
</style>

<!-- ================= SCRIPT ================= -->
<script>
window.currentDeleteCategoryId = null;

// OPEN MODAL
function openDeleteCategoryModal(categoryId, categoryName = null) {
    const modal = document.getElementById('deleteCategoryModal');
    const form = document.getElementById('deleteCategoryForm');
    const nameSpan = document.getElementById('deleteCategoryName');

    if (!modal || !form) return;

    window.currentDeleteCategoryId = categoryId;

    // Set form action
    form.action = '/admin/categories/' + categoryId;

    // Set category name
    if (categoryName) {
        nameSpan.textContent = categoryName;
    } else {
        nameSpan.innerHTML = '<i class="bi bi-arrow-repeat"></i> Loading...';
        // Optional: fetch category name if not provided
        fetch('/admin/categories/' + categoryId + '/edit', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nameSpan.textContent = data.category.name;
            } else {
                nameSpan.textContent = 'this category';
            }
        })
        .catch(() => {
            nameSpan.textContent = 'this category';
        });
    }

    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// CLOSE MODAL
function closeDeleteCategoryModal() {
    const modal = document.getElementById('deleteCategoryModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        window.currentDeleteCategoryId = null;

        const btn = document.querySelector('#deleteCategoryModal .delete-btn-confirm');
        if (btn) {
            btn.innerHTML = '<i class="bi bi-trash3"></i> Delete Category';
            btn.disabled = false;
        }
    }
}

// SHOW NOTIFICATION
function showNotification(message, type = 'success') {
    const existing = document.querySelector('.delete-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'delete-toast';
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: ${type === 'success' ? '#10b981' : '#ef4444'};
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
        font-size: 0.875rem;
        font-weight: 500;
    `;
    toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'}"></i><span>${message}</span>`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add animation style if not exists
if (!document.querySelector('#delete-modal-animation')) {
    const style = document.createElement('style');
    style.id = 'delete-modal-animation';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
}

// FORM SUBMIT
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('deleteCategoryForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!window.currentDeleteCategoryId) {
                showNotification('No category selected', 'error');
                return;
            }

            const btn = form.querySelector('.delete-btn-confirm');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeDeleteCategoryModal();
                    showNotification(data.message || 'Category deleted successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error deleting category', 'error');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            })
            .catch(() => {
                showNotification('Error deleting category', 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});

// CLOSE WHEN CLICK OUTSIDE
document.addEventListener('click', function (e) {
    const modal = document.getElementById('deleteCategoryModal');
    if (modal && e.target === modal) {
        closeDeleteCategoryModal();
    }
});

// CLOSE WITH ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('deleteCategoryModal');
        if (modal && modal.style.display === 'flex') {
            closeDeleteCategoryModal();
        }
    }
});
</script>

<!-- ================= HOW TO USE ================= -->
<!-- 
    Example button to trigger the modal:
    
    <button onclick="openDeleteCategoryModal(1, 'Electronics')">
        Delete Category
    </button>
    
    Or without name (will fetch automatically):
    
    <button onclick="openDeleteCategoryModal(1)">
        Delete Category
    </button>
-->