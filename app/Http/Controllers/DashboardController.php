<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UserOrder;
use App\Models\GuestOrder;
use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->role === 'admin') {
            return $this->adminDashboard($request);
        }
        
        // Get products and categories for user dashboard
        $products = Product::with('category')->get();
        $categories = \App\Models\Category::all();
        
        return view('user.dashboard', [
            'user' => $user,
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
private function adminDashboard(Request $request)
{
    $city = $request->get('city', 'Manila');
    $city = trim($city);
    
    if (empty($city)) {
        $city = 'Manila';
    }
    
    $weatherData = $this->weatherService->getWeather($city);
    
    if (isset($weatherData) && !isset($weatherData['cod']) || (isset($weatherData['cod']) && $weatherData['cod'] == 200)) {
        $timezoneOffset = $weatherData['timezone'] ?? 0;
        
        if (isset($weatherData['sys']['sunrise'])) {
            $weatherData['sys']['sunrise_formatted'] = Carbon::createFromTimestamp(
                $weatherData['sys']['sunrise'] + $timezoneOffset
            )->setTimezone('UTC')->format('h:i A');
        }
        
        if (isset($weatherData['sys']['sunset'])) {
            $weatherData['sys']['sunset_formatted'] = Carbon::createFromTimestamp(
                $weatherData['sys']['sunset'] + $timezoneOffset
            )->setTimezone('UTC')->format('h:i A');
        }
        
        $weatherData['main']['temp_celsius'] = round($weatherData['main']['temp']);
        $weatherData['main']['feels_like_celsius'] = round($weatherData['main']['feels_like']);
        $weatherData['wind']['speed_kmh'] = round($weatherData['wind']['speed'] * 3.6, 1);
        $weatherData['weather_icon'] = $this->getWeatherIcon($weatherData['weather'][0]['main']);
    }
    
    // Get counts for the 3 cards
    $totalProducts = Product::count();
    $totalOrders = UserOrder::count() + GuestOrder::count();
    $totalUsers = User::where('role', 'user')->count();
    
    // Get revenue information
    $totalRevenue = UserOrder::sum('total_amount') + GuestOrder::sum('total_amount');
    $this_month_revenue = UserOrder::where('created_at', '>=', now()->startOfMonth())->sum('total_amount')
        + GuestOrder::where('created_at', '>=', now()->startOfMonth())->sum('total_amount');
    $this_week_revenue = UserOrder::where('created_at', '>=', now()->startOfWeek())->sum('total_amount')
        + GuestOrder::where('created_at', '>=', now()->startOfWeek())->sum('total_amount');
    
    // Get recent orders
    $recentOrders = UserOrder::with('items.product')
        ->latest()
        ->limit(5)
        ->get();
    
    $guestRecentOrders = GuestOrder::with('items.product')
        ->latest()
        ->limit(5)
        ->get();
    
    $recentOrders = $recentOrders->merge($guestRecentOrders)->sortByDesc('created_at')->take(5);
    
    // Get low stock products
    $lowStockProducts = Product::where('quantity', '<=', 10)
        ->orderBy('quantity', 'asc')
        ->limit(5)
        ->get();
    
    // Get category breakdown
    $categories = DB::table('categories')
        ->select('categories.name', DB::raw('COUNT(products.id) as product_count'))
        ->leftJoin('products', 'categories.id', '=', 'products.category_id')
        ->groupBy('categories.id', 'categories.name')
        ->limit(5)
        ->get();
    
    // FIXED: Get top selling products from BOTH user orders AND guest orders
    $userTopProducts = DB::table('user_order_items')
        ->join('products', 'user_order_items.product_id', '=', 'products.id')
        ->select(
            'products.id', 
            'products.name', 
            DB::raw('COUNT(*) as order_count'), 
            DB::raw('SUM(user_order_items.subtotal) as revenue')
        )
        ->groupBy('products.id', 'products.name');
    
    $guestTopProducts = DB::table('guest_order_items')
        ->join('products', 'guest_order_items.product_id', '=', 'products.id')
        ->select(
            'products.id', 
            'products.name', 
            DB::raw('COUNT(*) as order_count'), 
            DB::raw('SUM(guest_order_items.subtotal) as revenue')
        )
        ->groupBy('products.id', 'products.name');
    
    // Combine both queries using union and then sum them
    $topProducts = DB::query()
        ->fromSub($userTopProducts->unionAll($guestTopProducts), 'combined')
        ->select(
            'id',
            'name',
            DB::raw('SUM(order_count) as total_orders'),
            DB::raw('SUM(revenue) as total_revenue')
        )
        ->groupBy('id', 'name')
        ->orderBy('total_orders', 'desc')
        ->limit(5)
        ->get()
        ->map(function($item) {
            return (object) [
                'id' => $item->id,
                'name' => $item->name,
                'order_count' => $item->total_orders,
                'revenue' => $item->total_revenue
            ];
        });
    
    return view('admin.dashboard', compact(
        'weatherData', 
        'city', 
        'totalProducts', 
        'totalOrders', 
        'totalUsers',
        'totalRevenue',
        'this_month_revenue',
        'this_week_revenue',
        'recentOrders',
        'lowStockProducts',
        'categories',
        'topProducts'
    ));
}
    
    private function getWeatherIcon($condition)
    {
        $icons = [
            'Clear' => 'bi bi-brightness-high',
            'Clouds' => 'bi bi-cloud',
            'Rain' => 'bi bi-cloud-rain',
            'Drizzle' => 'bi bi-cloud-drizzle',
            'Thunderstorm' => 'bi bi-cloud-lightning',
            'Snow' => 'bi bi-snow',
            'Mist' => 'bi bi-cloud-haze',
            'Fog' => 'bi bi-cloud-haze',
            'Haze' => 'bi bi-cloud-haze',
        ];
        
        return $icons[$condition] ?? 'bi bi-cloud';
    }
}