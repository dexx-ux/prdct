<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserOrder;
use App\Models\UserOrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display user's orders list
     */
    public function index()
    {
        $orders = UserOrder::with('items.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show($id)
    {
        $order = UserOrder::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        
        return view('user.orders.show', compact('order'));
    }

    /**
     * Store a new order (from dashboard or product page)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'total_price' => 'required|numeric|min:0'
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please log in to place an order.'
            ], 401);
        }

        $product = Product::findOrFail($validated['product_id']);
        
        if ($product->quantity < $validated['quantity']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available'
            ], 422);
        }

        try {
            $order = UserOrder::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $validated['total_price'],
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash',
                'shipping_address' => '',
            ]);

            UserOrderItem::create([
                'user_order_id' => $order->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $validated['total_price'] / $validated['quantity'],
                'subtotal' => $validated['total_price'],
            ]);

            $product->decrement('quantity', $validated['quantity']);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $order = UserOrder::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cannot cancel this order. It has already been processed.'
        ], 422);
    }

    /**
     * Get order statistics for user
     */
    public function statistics()
    {
        $userId = Auth::id();
        
        $stats = [
            'total_orders' => UserOrder::where('user_id', $userId)->count(),
            'total_spent' => UserOrder::where('user_id', $userId)->sum('total_amount'),
            'average_order_value' => UserOrder::where('user_id', $userId)->avg('total_amount') ?? 0,
            'pending_orders' => UserOrder::where('user_id', $userId)
                ->where('status', 'pending')
                ->count()
        ];

        return response()->json($stats);
    }

    /**
     * Get user's order history with filters
     */
    public function history(Request $request)
    {
        $query = UserOrder::with('items.product')
            ->where('user_id', Auth::id());

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->has('search')) {
            $query->whereHas('items.product', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }

        $orders = $query->latest()->paginate(15);

        return view('user.orders.history', compact('orders'));
    }
}
