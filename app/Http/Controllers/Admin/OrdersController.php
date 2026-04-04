<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserOrder;
use App\Models\GuestOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index(Request $request)
{
    // Get filter parameters
    $status = $request->get('status');
    $search = $request->get('search');
    $orderType = $request->get('order_type'); // 'all', 'user', 'guest'

    // Get user orders
    $userOrders = UserOrder::with(['items.product', 'user'])
        ->when($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled']), function($q) use ($status) {
            return $q->where('status', $status);
        })
        ->when($search, function($q) use ($search) {
            return $q->where(function($query) use ($search) {
                $query->where('user_orders.id', 'like', "%{$search}%")
                      ->orWhere('user_orders.order_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      })
                      ->orWhereHas('items.product', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
            });
        })
        ->when($orderType === 'guest', function($q) {
            return $q->whereRaw('1 = 0'); // No user orders when filtering for guests only
        })
        ->get()
        ->map(function($order) {
            $order->order_type = 'user';
            return $order;
        });

    // Get guest orders
    $guestOrders = GuestOrder::with('items.product')
        ->when($status && in_array($status, ['pending', 'processing', 'completed', 'cancelled']), function($q) use ($status) {
            return $q->where('status', $status);
        })
        ->when($search, function($q) use ($search) {
            return $q->where(function($query) use ($search) {
                $query->where('guest_orders.id', 'like', "%{$search}%")
                      ->orWhere('guest_orders.customer_name', 'like', "%{$search}%")
                      ->orWhere('guest_orders.customer_email', 'like', "%{$search}%")
                      ->orWhereHas('items.product', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
            });
        })
        ->when($orderType === 'user', function($q) {
            return $q->whereRaw('1 = 0'); // No guest orders when filtering for users only
        })
        ->get()
        ->map(function($order) {
            $order->order_type = 'guest';
            return $order;
        });

    // Combine and sort orders
    $allOrders = $userOrders->concat($guestOrders)->sortByDesc('created_at');

    // Manual pagination
    $perPage = 15;
    $currentPage = $request->get('page', 1);
    $total = $allOrders->count();
    $orders = $allOrders->forPage($currentPage, $perPage);

    // Create pagination object
    $paginatedOrders = new \Illuminate\Pagination\LengthAwarePaginator(
        $orders,
        $total,
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'pageName' => 'page']
    );

    // Calculate statistics
    $totalOrders = UserOrder::count() + GuestOrder::count();
    $totalRevenue = UserOrder::sum('total_amount') + GuestOrder::sum('total_amount');
    $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

    // Most ordered products (combine user and guest order items)
    $mostOrderedProducts = DB::table('user_order_items')
        ->join('products', 'user_order_items.product_id', '=', 'products.id')
        ->select('products.id', 'products.name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(user_order_items.subtotal) as total_revenue'))
        ->groupBy('products.id', 'products.name')
        ->union(
            DB::table('guest_order_items')
                ->join('products', 'guest_order_items.product_id', '=', 'products.id')
                ->select('products.id', 'products.name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(guest_order_items.subtotal) as total_revenue'))
                ->groupBy('products.id', 'products.name')
        )
        ->get()
        ->groupBy('id')
        ->map(function($group) {
            return (object) [
                'id' => $group->first()->id,
                'name' => $group->first()->name,
                'order_count' => $group->sum('order_count'),
                'total_revenue' => $group->sum('total_revenue')
            ];
        })
        ->sortByDesc('order_count')
        ->take(5);

    // Revenue by product (combine user and guest order items)
    $revenueByProduct = DB::table('user_order_items')
        ->join('products', 'user_order_items.product_id', '=', 'products.id')
        ->select('products.id', 'products.name', DB::raw('SUM(user_order_items.subtotal) as revenue'))
        ->groupBy('products.id', 'products.name')
        ->union(
            DB::table('guest_order_items')
                ->join('products', 'guest_order_items.product_id', '=', 'products.id')
                ->select('products.id', 'products.name', DB::raw('SUM(guest_order_items.subtotal) as revenue'))
                ->groupBy('products.id', 'products.name')
        )
        ->get()
        ->groupBy('id')
        ->map(function($group) {
            return (object) [
                'id' => $group->first()->id,
                'name' => $group->first()->name,
                'revenue' => $group->sum('revenue')
            ];
        })
        ->sortByDesc('revenue')
        ->take(5);

    // Recent orders (last 7 days)
    $ordersThisWeek = UserOrder::where('created_at', '>=', now()->subDays(7))->count() + GuestOrder::where('created_at', '>=', now()->subDays(7))->count();
    $revenueThisWeek = UserOrder::where('created_at', '>=', now()->subDays(7))->sum('total_amount') + GuestOrder::where('created_at', '>=', now()->subDays(7))->sum('total_amount');

    // Recent orders (last 30 days)
    $ordersThisMonth = UserOrder::where('created_at', '>=', now()->subDays(30))->count() + GuestOrder::where('created_at', '>=', now()->subDays(30))->count();
    $revenueThisMonth = UserOrder::where('created_at', '>=', now()->subDays(30))->sum('total_amount') + GuestOrder::where('created_at', '>=', now()->subDays(30))->sum('total_amount');

    // FIXED: Top customers - NOW INCLUDES BOTH REGISTERED AND GUEST CUSTOMERS
    // Get registered user customers
    $registeredCustomers = DB::table('user_orders')
        ->join('users', 'user_orders.user_id', '=', 'users.id')
        ->select(
            'users.email as customer_email', 
            'users.name as customer_name', 
            DB::raw('COUNT(user_orders.id) as order_count'), 
            DB::raw('SUM(user_orders.total_amount) as total_spent'),
            DB::raw("'registered' as customer_type")
        )
        ->groupBy('users.email', 'users.name');

    // Get guest customers
    $guestCustomers = DB::table('guest_orders')
        ->select(
            'guest_orders.customer_email',
            'guest_orders.customer_name',
            DB::raw('COUNT(guest_orders.id) as order_count'),
            DB::raw('SUM(guest_orders.total_amount) as total_spent'),
            DB::raw("'guest' as customer_type")
        )
        ->groupBy('guest_orders.customer_email', 'guest_orders.customer_name');

    // Combine both and get top 10 by total spent
    $topCustomers = $registeredCustomers->union($guestCustomers)
        ->orderBy('total_spent', 'desc')
        ->limit(10)
        ->get();

    return view('admin.orders.index', compact(
        'paginatedOrders',
        'totalOrders',
        'totalRevenue',
        'averageOrderValue',
        'mostOrderedProducts',
        'revenueByProduct',
        'ordersThisWeek',
        'revenueThisWeek',
        'ordersThisMonth',
        'revenueThisMonth',
        'topCustomers'
    ));
}

    public function show($id)
    {
        // Try to find in user orders first
        $order = UserOrder::with('items.product')->find($id);

        if (!$order) {
            // If not found in user orders, try guest orders
            $order = GuestOrder::with('items.product')->find($id);
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

    public function updateStatus($id, Request $request)
    {
        $status = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ])['status'];

        // Try to find in user orders first
        $order = UserOrder::find($id);

        if (!$order) {
            // If not found in user orders, try guest orders
            $order = GuestOrder::find($id);
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
            'order' => $order
        ]);
    }

    public function cancel($id)
    {
        $order = UserOrder::find($id);

        if (!$order) {
            $order = GuestOrder::find($id);
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully!'
        ]);
    }

    public function destroy($id)
    {
        // Try to find in user orders first
        $order = UserOrder::find($id);

        if (!$order) {
            // If not found in user orders, try guest orders
            $order = GuestOrder::find($id);
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer'
        ])['ids'];

        $deletedCount = 0;

        foreach ($ids as $id) {
            // Try to find and delete from user orders first
            $userOrder = UserOrder::find($id);
            if ($userOrder) {
                $userOrder->delete();
                $deletedCount++;
                continue;
            }

            // If not found in user orders, try guest orders
            $guestOrder = GuestOrder::find($id);
            if ($guestOrder) {
                $guestOrder->delete();
                $deletedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} order(s) deleted successfully!"
        ]);
    }
}
