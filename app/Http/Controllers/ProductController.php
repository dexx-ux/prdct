<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Paginated products for the table
        $products = Product::with('category')->latest()->paginate(10);

        
        // Get all products for stats calculation
        $allProducts = Product::all();

        return view('admin.products.index', compact('products', 'allProducts'));
    }

    public function create()
    {
        // Return JSON response for modal since we use modals now
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        // build validator so we can add conditional checks
        $validator = \Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'quantity'       => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
            'discount_value' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validator->after(function ($validator) use ($request) {
            $price = $request->input('price');
            $value = trim($request->input('discount_value'));

            if ($value !== '') {
                // ensure the string is a number with optional decimal and optional % suffix
                if (!preg_match('/^\d+(\.\d+)?%?$/', $value)) {
                    $validator->errors()->add('discount_value', 'Discount must be a number or a percentage (e.g. 10%).');
                } else {
                    // determine type by presence of '%' suffix
                    $isPercent = str_ends_with($value, '%');
                    $num = floatval(rtrim($value, '%'));

                    if ($isPercent) {
                        if ($num > 100) {
                            $validator->errors()->add('discount_value', 'Percentage discount cannot exceed 100.');
                        }
                    } else {
                        // treat as fixed amount
                        if ($num > $price) {
                            // attach error to price so user knows price must be higher than discount
                            $validator->errors()->add('price', 'Price must be higher than the discount amount.');
                        }
                    }
                }
            } else {
                // blank value -> normalize to null so database stores null
                $request->merge(['discount_value' => null]);
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only('name', 'description', 'quantity', 'price', 'discount_value', 'category_id');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }
        
        $product = Product::create($data);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product
            ]);
        }
        
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = \Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'quantity'       => 'required|integer|min:0',
            'price'          => 'required|numeric|min:0',
            'discount_value' => 'nullable|string',
            'category_id'    => 'nullable|exists:categories,id',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validator->after(function ($validator) use ($request) {
            $price = $request->input('price');
            $value = trim($request->input('discount_value'));

            if ($value !== '') {
                if (!preg_match('/^\d+(\.\d+)?%?$/', $value)) {
                    $validator->errors()->add('discount_value', 'Discount must be a number or a percentage (e.g. 10%).');
                } else {
                    $isPercent = str_ends_with($value, '%');
                    $num = floatval(rtrim($value, '%'));

                    if ($isPercent) {
                        if ($num > 100) {
                            $validator->errors()->add('discount_value', 'Percentage discount cannot exceed 100.');
                        }
                    } else {
                        if ($num > $price) {
                            $validator->errors()->add('price', 'Price must be higher than the discount amount.');
                        }
                    }
                }
            } else {
                $request->merge(['discount_value' => null]);
            }
        });

        // Check if validation fails
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only('name', 'description', 'quantity', 'price', 'discount_value', 'category_id');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }
        
        $product->update($data);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => $product
            ]);
        }
        
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Request $request, Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully!'
            ]);
        }
        
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function destroySelected(Request $request)
    {
        // Get product_ids from request
        $productIdsString = $request->input('product_ids');
        
        // If it's a string (comma-separated), convert to array
        if (is_string($productIdsString)) {
            $productIds = explode(',', $productIdsString);
        } 
        // If it's already an array
        elseif (is_array($productIdsString)) {
            $productIds = $productIdsString;
        }
        // If it's JSON, decode it
        else {
            $productIds = json_decode($productIdsString, true);
        }
        
        // Remove any empty values and convert to integers
        $productIds = array_filter($productIds);
        $productIds = array_map('intval', $productIds);
        
        if (empty($productIds)) {
            return redirect()->route('admin.products.index')
                           ->with('error', 'No products selected.');
        }
        
        // Get products to delete their images
        $products = Product::whereIn('id', $productIds)->get();
        foreach ($products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
        }
        
        // Delete selected products
        $deletedCount = Product::whereIn('id', $productIds)->delete();
        
        if ($deletedCount > 0) {
            return redirect()->route('admin.products.index')
                           ->with('success', "$deletedCount product(s) deleted successfully.");
        }
        
        return redirect()->route('admin.products.index')
                       ->with('error', 'No products were deleted.');
    }

    /**
     * Get product data for editing via AJAX
     */
    public function editData($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }
}