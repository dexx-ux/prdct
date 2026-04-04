<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function browse()
    {
        $products = Product::with('category')->get();
        $categories = Category::has('products')->get();

        return view('user.products.browse', compact('products', 'categories'));
    }
}
