{{-- resources/views/admin/products/partials/products-table.blade.php --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="w-full overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                <tr>
                    <th class="px-3 py-2 text-left w-10">
                        <input type="checkbox" id="selectAll" 
                               class="border-gray-300 text-[#1a2c3e] focus:ring-[#1a2c3e]">
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        ID
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Image
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Product
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Category
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Stock
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Price
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Discount
                    </th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="productsTableBody">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 product-row" 
                    data-name="{{ strtolower($product->name) }}" 
                    data-description="{{ strtolower($product->description ?? '') }}"
                    data-id="{{ $product->id }}"
                    data-stock="{{ $product->quantity }}"
                    data-category-id="{{ $product->category_id ?? '' }}">
                    <td class="px-3 py-3 whitespace-nowrap">
                        <input type="checkbox" class="product-checkbox border-gray-300 text-[#1a2c3e] focus:ring-[#1a2c3e]"
                               data-product-id="{{ $product->id }}">
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-900 dark:text-gray-300">
                        #{{ $product->id }}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                <i class="bi bi-image text-gray-400 text-lg"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="text-xs font-medium text-gray-900 dark:text-white">
                            {{ $product->name }}
                        </div>
                    </td>
                    <td class="px-3 py-3">
                        <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 max-w-xs">
                            {{ $product->description ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                        @if($product->category)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                        @if($product->quantity > 10)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                {{ $product->quantity }}
                            </span>
                        @elseif($product->quantity > 0)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                {{ $product->quantity }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                Out
                            </span>
                        @endif
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-900 dark:text-white font-semibold">
                        ₱{{ number_format($product->price, 2) }}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-900 dark:text-white">
                        @if($product->discount_value)
                            <span class="text-green-600 dark:text-green-400">{{ $product->discount_value }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-sm">
                        <div class="flex gap-2 justify-center">
                            <button onclick="openEditModal({{ $product->id }})" 
                                    title="Edit product"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition duration-200">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button onclick="openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')" 
                                    title="Delete product"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-200">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="bi bi-box text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No products found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>