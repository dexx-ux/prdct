@props(['product'])
@php
if(!isset($product)) {
    dd('Product is not set in row.blade.php');
}
@endphp


<tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 product-row" 
    data-name="{{ strtolower($product->name) }}" 
    data-sku="{{ strtolower($product->sku ?? '') }}"
    data-description="{{ strtolower($product->description ?? '') }}">

    <!-- Checkbox -->
  <td class="px-6 py-4 whitespace-nowrap">
    <input type="checkbox" class="product-checkbox border-gray-300 text-blue-600 focus:ring-blue-600" 
           data-product-id="{{ $product->id }}">
</td>
    

    <!-- ID -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
        #{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
    </td>

    <!-- Product -->
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900 dark:text-white">
            {{ $product->name }}
        </div>
        @if($product->sku)
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                SKU: {{ $product->sku }}
            </div>
        @endif
    </td>

    <!-- Description -->
    <td class="px-6 py-4">
        <div class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
            {{ $product->description ?: '—' }}
        </div>
    </td>

    <!-- Stock -->
    @php
        $quantity = $product->quantity ?? 0;
        $badgeBg = match (true) {
            $quantity > 10 => 'bg-green-100 dark:bg-green-900/40',
            $quantity > 0  => 'bg-amber-100 dark:bg-amber-900/40',
            default        => 'bg-red-100 dark:bg-red-900/40',
        };
    @endphp

    <td class="px-6 py-4 whitespace-nowrap">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                     {{ $badgeBg }}">
            {{ number_format($quantity) }}
        </span>
    </td>

    <!-- Price -->
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
            ₱{{ number_format($product->price, 2) }}
        </div>

        @if($product->compare_price && $product->compare_price > $product->price)
            <div class="text-xs text-gray-500 dark:text-gray-400 line-through mt-0.5">
                ₱{{ number_format($product->compare_price, 2) }}
            </div>
        @endif
    </td>

    <!-- Discount -->
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
        @if($product->discount_value)
            {{ $product->discount_value }}
            <div class="text-xs font-semibold text-green-700 dark:text-green-300 mt-0.5">
                ₱{{ number_format($product->final_price, 2) }}
            </div>
        @else
            —
        @endif
    </td>

    <!-- Actions -->
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        <div class="flex items-center gap-2">
            <button onclick="openEditModal({{ $product->id }})" 
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition duration-200">
                <i class="bi bi-pencil mr-1"></i> Edit
            </button>
            <button onclick="singleDeleteProduct({{ $product->id }})" 
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition duration-200">
                <i class="bi bi-trash mr-1"></i> Delete
            </button>
        </div>
    </td>
</tr>