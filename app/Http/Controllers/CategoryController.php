<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10);
        
        // Get statistics
        $totalCategories = Category::count();
        $totalProductsInCategories = DB::table('products')->count();
        $averageProductsPerCategory = $totalCategories > 0 ? round($totalProductsInCategories / $totalCategories, 1) : 0;
        $maxProducts = Category::withCount('products')->orderBy('products_count', 'desc')->first()->products_count ?? 0;
        
        return view('admin.categories.index', compact('categories', 'totalCategories', 'totalProductsInCategories', 'averageProductsPerCategory', 'maxProducts'));
    }

    public function list()
    {
        $categories = Category::all()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category,
        ]);
    }

    public function edit(Category $category)
    {
        return response()->json([
            'success' => true,
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $category->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category,
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products!',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }
}
