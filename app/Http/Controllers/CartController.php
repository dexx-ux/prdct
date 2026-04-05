<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;

        $product = Product::find($productId);
        if ($product->quantity < $quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough stock']);
        }

        if (Auth::check()) {
            // Authenticated user - use database
            $userId = Auth::id();
            $cartItem = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        } else {
            // Guest user - use session
            $cart = Session::get('cart', []);
            $cartKey = (string) $productId;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ];
            }

            Session::put('cart', $cart);
        }

        return response()->json(['success' => true, 'message' => 'Added to cart']);
    }

    public function index()
    {
        if (Auth::check()) {
            // Authenticated user - get from database
            $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
            $cartData = $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'product' => $item->product
                ];
            });
        } else {
            // Guest user - get from session
            $cart = Session::get('cart', []);
            $cartData = collect($cart)->map(function ($item, $key) {
                $product = Product::find($item['product_id']);
                return $product ? [
                    'id' => $key,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'product' => $product
                ] : null;
            })->filter();
        }

        return view('user.cart.index', compact('cartData'));
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->quantity;

        if (Auth::check()) {
            // Authenticated user - update database
            $cartItem = CartItem::find($cartItemId);

            if (!$cartItem || $cartItem->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized']);
            }

            $product = $cartItem->product;
            if ($product->quantity < $quantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock']);
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();
        } else {
            // Guest user - update session
            $cart = Session::get('cart', []);

            if (!isset($cart[$cartItemId])) {
                return response()->json(['success' => false, 'message' => 'Item not found']);
            }

            $product = Product::find($cart[$cartItemId]['product_id']);
            if (!$product || $product->quantity < $quantity) {
                return response()->json(['success' => false, 'message' => 'Not enough stock']);
            }

            $cart[$cartItemId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($cartItemId)
    {
        if (Auth::check()) {
            // Authenticated user - delete from database
            $cartItem = CartItem::find($cartItemId);

            if (!$cartItem || $cartItem->user_id !== Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized']);
            }

            $cartItem->delete();
        } else {
            // Guest user - remove from session
            $cart = Session::get('cart', []);

            if (isset($cart[$cartItemId])) {
                unset($cart[$cartItemId]);
                Session::put('cart', $cart);
            }
        }

        return response()->json(['success' => true]);
    }

    // Method to merge guest cart to user cart when logging in
    public function mergeGuestCart()
    {
        if (!Auth::check()) {
            return;
        }

        $guestCart = Session::get('cart', []);
        $userId = Auth::id();

        foreach ($guestCart as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];

            $existingItem = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                $existingItem->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }
        }

        // Clear guest cart
        Session::forget('cart');
    }
}
