<!-- Order Details Modal -->
<div id="orderModal" class="hidden fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-70 z-50 items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl max-w-4xl w-full">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-900 dark:to-blue-800 px-6 py-4 flex justify-between items-center border-b border-blue-700 dark:border-blue-900">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="bi bi-box-seam"></i>Order Details
            </h3>
            <button onclick="closeOrderModal()" class="text-white hover:bg-blue-700 dark:hover:bg-blue-900 p-1 rounded transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div id="modalContent" class="p-6">
            <!-- Loading State -->
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin">
                    <i class="bi bi-hourglass text-blue-600 dark:text-blue-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Modal Footer with Actions -->
        <div id="modalFooter" class="hidden px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <button onclick="updateOrderStatusFromModal('pending')"
                            class="px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:hover:bg-yellow-800 dark:text-yellow-200 rounded text-sm font-medium transition">
                        <i class="bi bi-clock mr-1"></i>Pending
                    </button>
                    <button onclick="updateOrderStatusFromModal('processing')"
                            class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 dark:bg-blue-900 dark:hover:bg-blue-800 dark:text-blue-200 rounded text-sm font-medium transition">
                        <i class="bi bi-gear mr-1"></i>Processing
                    </button>
                    <button onclick="updateOrderStatusFromModal('completed')"
                            class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 dark:bg-green-900 dark:hover:bg-green-800 dark:text-green-200 rounded text-sm font-medium transition">
                        <i class="bi bi-check-circle mr-1"></i>Completed
                    </button>
                    <button onclick="cancelOrderFromModal()"
                            class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-200 rounded text-sm font-medium transition">
                        <i class="bi bi-x-circle mr-1"></i>Cancel
                    </button>
                </div>
                <button onclick="closeOrderModal()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition font-medium text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
