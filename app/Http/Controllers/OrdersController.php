<?php

namespace App\Http\Controllers;

use App\Models\UserOrder;
use App\Models\UserOrderItem;
use App\Models\GuestOrder;
use App\Models\GuestOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    /**
     * Store multiple items order (for cart checkout)
     */
   public function storeMultiple(Request $request)
{
    // Log the incoming request
    \Log::info('storeMultiple called', ['request_data' => $request->all()]);
    
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'customer_name' => 'required|string|max:255',
        'customer_email' => 'required|email|max:255',
        'customer_phone' => 'required|string|max:20',
        'shipping_address' => 'required|string',
        'payment_method' => 'required|string',
        'notes' => 'nullable|string',
        'total_price' => 'required|numeric|min:0',
        'is_guest' => 'boolean'
    ]);

    try {
        DB::beginTransaction();

        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(uniqid());

        $isGuest = $validated['is_guest'] || !Auth::check();
        
        \Log::info('Creating order', ['is_guest' => $isGuest, 'order_number' => $orderNumber]);

        if ($isGuest) {
            // Create Guest Order
            $order = GuestOrder::create([
                'order_number' => $orderNumber,
                'session_id' => Session::getId(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['shipping_address'],
                'total_amount' => $validated['total_price'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
            ]);
            
            \Log::info('Guest order created', ['order_id' => $order->id]);

            // Create Guest Order Items
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                GuestOrderItem::create([
                    'guest_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $product->decrement('quantity', $item['quantity']);
            }
        } else {
            // Create User Order
            $order = UserOrder::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'total_amount' => $validated['total_price'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'shipping_address' => $validated['shipping_address'],
            ]);
            
            \Log::info('User order created', ['order_id' => $order->id]);

            // Create User Order Items
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                UserOrderItem::create([
                    'user_order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);

                // Update product stock
                $product->decrement('quantity', $item['quantity']);
            }
        }

        DB::commit();
        
        \Log::info('Order committed successfully', ['order_id' => $order->id]);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order' => $order,
            'redirect' => !$isGuest ? '/user/orders' : '/order/confirmation/' . $order->id
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        \Log::error('Order creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to place order: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * List orders for current user (or all for admin)
     */
    public function index()
    {
        $query = UserOrder::with('items.product');

        if (!Auth::user() || Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        $orders = $query->latest()->paginate(15);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show single order details
     */
    public function show($id)
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            $order = UserOrder::with('items.product')->find($id);
            if (!$order) {
                $order = GuestOrder::with('items.product')->findOrFail($id);
            }
        } else {
            $order = UserOrder::with('items.product')
                ->where('user_id', Auth::id())
                ->find($id);

            if (!$order) {
                $order = GuestOrder::with('items.product')
                    ->where('customer_email', Auth::user()->email)
                    ->findOrFail($id);
            }
        }

        return view('user.orders.show', compact('order'));
    }

    /**
     * Store single item order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Generate unique order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            $isGuest = !Auth::check();

            if ($isGuest) {
                // Create Guest Order
                $order = GuestOrder::create([
                    'order_number' => $orderNumber,
                    'session_id' => Session::getId(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_address' => $validated['shipping_address'],
                    'total_amount' => $validated['total_price'],
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                ]);

                // Create Guest Order Item
                $product = Product::find($validated['product_id']);
                
                GuestOrderItem::create([
                    'guest_order_id' => $order->id,
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $validated['price'],
                    'subtotal' => $validated['total_price']
                ]);

                // Update product stock
                $product->decrement('quantity', $validated['quantity']);
            } else {
                // Create User Order
                $order = UserOrder::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'total_amount' => $validated['total_price'],
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'shipping_address' => $validated['shipping_address'],
                ]);

                // Create User Order Item
                $product = Product::find($validated['product_id']);
                
                UserOrderItem::create([
                    'user_order_id' => $order->id,
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $validated['price'],
                    'subtotal' => $validated['total_price']
                ]);

                // Update product stock
                $product->decrement('quantity', $validated['quantity']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order' => $order,
                'redirect' => !$isGuest ? '/user/orders' : '/order/confirmation/' . $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track order
     */
    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string',
            'email' => 'required|email'
        ]);

        // Try to find in user orders
        $order = UserOrder::where('order_number', $validated['order_number'])
                          ->whereHas('user', function($query) use ($validated) {
                              $query->where('email', $validated['email']);
                          })
                          ->with('items.product')
                          ->first();

        // If not found, try guest orders
        if (!$order) {
            $order = GuestOrder::where('order_number', $validated['order_number'])
                              ->where('customer_email', $validated['email'])
                              ->with('items.product')
                              ->first();
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    /**
     * Order confirmation
     */
    public function confirmation($id)
    {
        // Try to find in user orders first
        $order = UserOrder::with(['items', 'items.product'])->find($id);
        
        // If not found, try guest orders
        if (!$order) {
            $order = GuestOrder::with(['items', 'items.product'])->find($id);
        }
        
        // If still not found, show 404
        if (!$order) {
            abort(404, 'Order not found. The order ID ' . $id . ' does not exist in our records.');
        }
        
        return view('orders.confirmation', compact('order'));
    }

}