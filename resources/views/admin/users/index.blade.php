<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Users Management
                </h2>
            </div>
        </div>
    </x-slot>

    {{-- ✅ FULL WIDTH FIX --}}
    <div class="w-full px-6 py-8">

        {{-- Stats Cards --}}
        @include('admin.users.partials.stats-cards', ['users' => $users])

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                <i class="bi bi-people mr-2 text-[#a30000]"></i>
                Users List
            </h1>
            
            <button id="deleteSelectedBtn" 
                    class="hidden bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                    onclick="showDeleteModal()">
                Delete Selected
            </button>
        </div>

        {{-- Search --}}
        @include('admin.users.partials.search-bar')

        {{-- ✅ TABLE WRAPPER FIX --}}
        <div class="overflow-x-auto">
            @include('admin.users.partials.users-table', ['users' => $users])
        </div>

        @if(isset($users) && method_exists($users, 'links'))
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 w-96 bg-white rounded-lg">
            <div class="text-center">
                <h3 class="text-lg font-medium">Confirm Delete</h3>
                <p class="mt-2">
                    Delete <span id="deleteCount" class="text-red-600 font-bold">0</span> users?
                </p>

                <div class="flex justify-center gap-3 mt-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>
                    <button onclick="confirmDeleteSelected()" class="px-4 py-2 bg-red-600 text-white rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ REAL FORM --}}
    <form id="deleteSelectedForm" method="POST" action="{{ route('admin.users.delete-selected') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="user_ids" id="user_ids">
    </form>

</x-app-layout>
<script>
let pendingSelectedUsers = [];

document.addEventListener('DOMContentLoaded', function () {
    // Verify form exists
    const form = document.getElementById('deleteSelectedForm');
    if (!form) {
        console.error('Error: Delete form not found in DOM');
    }

    // Verify hidden input exists
    const input = document.getElementById('user_ids');
    if (!input) {
        console.error('Error: Hidden user_ids input not found in DOM');
    }

    initializeSelectAll();
    initializeCheckboxes();
    initializeSearch();
    updateDeleteButton();

    console.log('Delete functionality initialized');
});

/* ===============================
   SELECT ALL FUNCTION
================================ */
function initializeSelectAll() {
    const selectAll = document.getElementById('selectAll');

    if (!selectAll) return;

    selectAll.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.user-checkbox');

        checkboxes.forEach(cb => {
            cb.checked = selectAll.checked;
        });

        updateDeleteButton();
    });
}

/* ===============================
   INDIVIDUAL CHECKBOX
================================ */
function initializeCheckboxes() {
    document.querySelectorAll('.user-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            updateSelectAllState();
            updateDeleteButton();
        });
    });
}

/* ===============================
   UPDATE SELECT ALL STATE
================================ */
function updateSelectAllState() {
    const all = document.querySelectorAll('.user-checkbox');
    const checked = document.querySelectorAll('.user-checkbox:checked');
    const selectAll = document.getElementById('selectAll');

    if (!selectAll) return;

    selectAll.checked = all.length > 0 && all.length === checked.length;
}

/* ===============================
   TOGGLE DELETE BUTTON
================================ */
function updateDeleteButton() {
    const btn = document.getElementById('deleteSelectedBtn');
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');
    const inactiveChecked = Array.from(checkboxes).filter(cb => 
        cb.dataset.userStatus === 'inactive'
    ).length;

    if (!btn) return;

    if (inactiveChecked > 0) {
        btn.classList.remove('hidden');
        btn.textContent = `Delete Selected (${inactiveChecked})`;
    } else {
        btn.classList.add('hidden');
        btn.textContent = 'Delete Selected';
    }
}

/* ===============================
   SHOW DELETE MODAL
================================ */
function showDeleteModal() {
    const selected = [];
    const activeUsers = [];
    const checkboxes = document.querySelectorAll('.user-checkbox:checked');

    checkboxes.forEach(cb => {
        const id = cb.dataset.userId;
        const status = cb.dataset.userStatus;

        if (status === 'inactive') {
            selected.push(id);
        } else {
            activeUsers.push(id);
        }
    });

    // Uncheck active users
    if (activeUsers.length > 0) {
        activeUsers.forEach(id => {
            const cb = document.querySelector(`[data-user-id="${id}"]`);
            if (cb) cb.checked = false;
        });
        alert(`Cannot delete user(s) ${activeUsers.join(', ')} - they are ACTIVE. Only INACTIVE users can be deleted.`);
    }

    updateSelectAllState();
    updateDeleteButton();

    if (selected.length === 0) {
        return;
    }

    pendingSelectedUsers = selected;

    document.getElementById('deleteCount').textContent = selected.length;
    document.getElementById('deleteModal').classList.remove('hidden');
}

/* ===============================
   CLOSE MODAL
================================ */
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    pendingSelectedUsers = [];
}

/* ===============================
   CONFIRM DELETE
================================ */
function confirmDeleteSelected() {
    if (pendingSelectedUsers.length === 0) {
        alert('No users selected for deletion.');
        return;
    }

    const input = document.getElementById('user_ids');
    const form = document.getElementById('deleteSelectedForm');

    if (!input) {
        alert('Error: Hidden input not found. Please try again.');
        return;
    }

    if (!form) {
        alert('Error: Form not found. Please try again.');
        return;
    }

    // Set the user IDs
    input.value = pendingSelectedUsers.join(',');

    console.log('Submitting delete form with user IDs:', input.value);
    
    // Submit the form
    form.submit();
}

/* ===============================
   SEARCH FILTER
================================ */
function initializeSearch() {
    const input = document.getElementById('searchInput');

    if (!input) return;

    input.addEventListener('keyup', function () {
        const term = this.value.toLowerCase();

        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';

            if (name.includes(term) || email.includes(term)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

/* ===============================
   DELETE SINGLE USER
================================ */
function deleteUser(userId) {
    const row = document.querySelector(`[data-id="${userId}"]`);
    const userRow = document.querySelector(`.user-row[data-id="${userId}"]`);
    const checkbox = document.querySelector(`[data-user-id="${userId}"]`);
    const userStatus = checkbox?.dataset.userStatus || 'active';

    if (userStatus !== 'inactive') {
        alert('Cannot delete this user - they are ACTIVE. Only INACTIVE users can be deleted.');
        return;
    }

    if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        return;
    }

    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.content || 
                  document.querySelector('input[name="_token"]')?.value;

    if (!token) {
        alert('Security token missing. Please refresh the page and try again.');
        return;
    }

    // Send DELETE request
    fetch(`/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        // Remove the row from the table
        if (userRow) {
            userRow.remove();
        }
        alert('User deleted successfully!');
        location.reload();
    })
    .catch(error => {
        console.error('Delete error:', error);
        alert('Failed to delete user: ' + error.message);
    });
}
</script>