<!-- ================= DELETE PRODUCT MODAL FULL CODE ================= -->

<!-- MODAL -->
<div id="deleteProductModal" class="delete-modal-overlay" style="display: none;">
    <div class="delete-modal-container">

        <!-- CLOSE BUTTON -->
        <button class="delete-modal-close" onclick="closeDeleteModal()">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="delete-modal-content">

            <div class="delete-icon-wrapper">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <h3 class="delete-modal-title">Delete Product</h3>

            <p class="delete-modal-message">
                Are you sure you want to delete this product? This action cannot be undone.
            </p>

            <div id="deleteProductInfo" class="delete-product-info">
                <i class="bi bi-box"></i>
                <span id="deleteProductName">Loading product...</span>
            </div>

            <div class="delete-warning-note">
                <i class="bi bi-info-circle-fill"></i>
                <small>Deleting this product will affect all orders that reference it.</small>
            </div>

            <!-- FORM -->
            <form id="deleteProductForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="delete-modal-buttons">
                    <button type="button" class="delete-btn-cancel" onclick="closeDeleteModal()">
                        Cancel
                    </button>

                    <button type="submit" class="delete-btn-confirm">
                        <i class="bi bi-trash3"></i> Delete Product
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
}

.delete-modal-message {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 15px;
}

.delete-product-info {
    background: #f9fafb;
    padding: 10px;
    border-radius: 10px;
    margin-bottom: 10px;
    display: flex;
    gap: 8px;
    align-items: center;
}

.delete-warning-note {
    background: #fff3cd;
    padding: 10px;
    border-radius: 10px;
    font-size: 12px;
    margin-bottom: 15px;
}

.delete-modal-buttons {
    display: flex;
    gap: 10px;
}

.delete-btn-cancel {
    flex: 1;
    background: #e5e7eb;
    padding: 10px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.delete-btn-confirm {
    flex: 1;
    background: #ef4444;
    color: white;
    padding: 10px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
}

.delete-btn-confirm:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<!-- ================= SCRIPT ================= -->
<script>
window.currentDeleteProductId = null;

// OPEN MODAL
function openDeleteModal(productId, productName = null) {
    const modal = document.getElementById('deleteProductModal');
    const form = document.getElementById('deleteProductForm');
    const nameSpan = document.getElementById('deleteProductName');

    window.currentDeleteProductId = productId;

    form.action = '/admin/products/' + productId;

    nameSpan.textContent = productName || 'this product';

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// CLOSE MODAL
function closeDeleteModal() {
    const modal = document.getElementById('deleteProductModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    window.currentDeleteProductId = null;

    const btn = document.querySelector('.delete-btn-confirm');
    if (btn) {
        btn.innerHTML = '<i class="bi bi-trash3"></i> Delete Product';
        btn.disabled = false;
    }
}

// FORM SUBMIT
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('deleteProductForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const btn = form.querySelector('.delete-btn-confirm');
        btn.innerHTML = 'Deleting...';
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            closeDeleteModal();
            location.reload();
        })
        .catch(() => {
            alert('Error deleting product');
            btn.innerHTML = 'Delete Product';
            btn.disabled = false;
        });
    });

});

// CLOSE WHEN CLICK OUTSIDE
document.addEventListener('click', function (e) {
    const modal = document.getElementById('deleteProductModal');
    if (e.target === modal) {
        closeDeleteModal();
    }
});

// CLOSE WITH ESC
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>

