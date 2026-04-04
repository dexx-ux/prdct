<script>
    function loadDashboardData() {
        fetch('{{ route("user.orders.statistics") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('totalOrders').textContent = data.total_orders;
                document.getElementById('totalSpent').textContent = '₱' + parseFloat(data.total_spent).toFixed(2);
                document.getElementById('avgOrder').textContent = '₱' + parseFloat(data.average_order_value).toFixed(2);
                document.getElementById('pendingOrders').textContent = data.pending_orders;
            });

        fetch('{{ route("user.orders.index") }}')
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const recentOrders = doc.querySelector('table tbody');
                
                if (recentOrders) {
                    const rows = recentOrders.querySelectorAll('tr');
                    let html = '';
                    
                    for (let i = 0; i < Math.min(5, rows.length); i++) {
                        const cells = rows[i].querySelectorAll('td');
                        if (cells.length > 0) {
                            html += `
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">${cells[0].textContent.trim()}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">${cells[1].textContent.trim()}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-white">${cells[3].textContent.trim()}</p>
                                        <p class="text-sm ${cells[4].innerHTML.includes('green') ? 'text-green-600' : 'text-orange-600'}">${cells[4].textContent.trim()}</p>
                                    </div>
                                </div>
                            `;
                        }
                    }
                    
                    if (html) {
                        document.getElementById('recentOrdersContainer').innerHTML = html + `
                            <div class="pt-4">
                                <a href="{{ route('user.orders.index') }}" class="text-[#991b1b] hover:underline font-medium text-sm">View all orders →</a>
                            </div>
                        `;
                    } else {
                        document.getElementById('recentOrdersContainer').innerHTML = `
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No orders yet. <a href="{{ route('user.products.browse') }}" class="text-[#991b1b] hover:underline">Start shopping</a></p>
                        `;
                    }
                }
            });
    }

    loadDashboardData();
</script>
