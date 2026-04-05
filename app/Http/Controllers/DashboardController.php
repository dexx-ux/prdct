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

        // User dashboard
        $products = Product::with('category')->get();
        $categories = \App\Models\Category::all();

        $recentOrders = UserOrder::with('items.product')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $totalOrders = UserOrder::where('user_id', $user->id)->count();
        $totalSpent = UserOrder::where('user_id', $user->id)->sum('total_amount');
        $averageOrder = UserOrder::where('user_id', $user->id)->avg('total_amount') ?? 0;
        $pendingOrders = UserOrder::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return view('user.dashboard', [
            'user' => $user,
            'products' => $products,
            'categories' => $categories,
            'recentOrders' => $recentOrders,
            'totalOrders' => $totalOrders,
            'totalSpent' => $totalSpent,
            'averageOrder' => $averageOrder,
            'pendingOrders' => $pendingOrders,
        ]);
    }

    private function adminDashboard(Request $request)
    {
        $city = $request->get('city', 'Manila');
        
        $viewData = [
            'city' => trim($city),
            'weatherData' => $this->getWeatherData($city),
            'totalProducts' => $this->getTotalProducts(),
            'totalOrders' => $this->getTotalOrders(),
            'totalUsers' => $this->getTotalUsers(),
            'totalRevenue' => $this->getTotalRevenue(),
            'this_month_revenue' => $this->getMonthlyRevenue(),
            'this_week_revenue' => $this->getWeeklyRevenue(),
            'recentOrders' => $this->getRecentOrders(),
            'lowStockProducts' => $this->getLowStockProducts(),
            'categories' => $this->getCategoryBreakdown(),
            'topProducts' => $this->getTopSellingProducts(),
        ];

        return view('admin.dashboard', $viewData);
    }

    /**
     * Get weather data safely
     */
    private function getWeatherData(string $city): ?array
    {
        if (empty($city)) {
            $city = 'Manila';
        }

        try {
            $response = $this->weatherService->getWeather($city);
            
            // Validate response structure
            if (!$this->isValidWeatherResponse($response)) {
                return is_array($response) ? $response : null;
            }
            
            return $this->formatWeatherData($response);
            
        } catch (\Exception $e) {
            \Log::error('Weather error: ' . $e->getMessage());
            return ['cod' => 500, 'message' => 'Weather service error'];
        }
    }

    /**
     * Check if weather response is valid
     */
    private function isValidWeatherResponse($response): bool
    {
        return $response && 
               is_array($response) && 
               isset($response['cod']) && 
               $response['cod'] == 200 &&
               isset($response['main']) &&
               isset($response['weather'][0]);
    }

    /**
     * Format weather data for view
     */
    private function formatWeatherData(array $response): array
    {
        $timezoneOffset = $response['timezone'] ?? 0;
        
        $weatherData = $response;
        
        // Format sunrise/sunset
        if (isset($response['sys']['sunrise'])) {
            $weatherData['sys']['sunrise_formatted'] = Carbon::createFromTimestamp(
                $response['sys']['sunrise'] + $timezoneOffset
            )->setTimezone('UTC')->format('h:i A');
        }
        
        if (isset($response['sys']['sunset'])) {
            $weatherData['sys']['sunset_formatted'] = Carbon::createFromTimestamp(
                $response['sys']['sunset'] + $timezoneOffset
            )->setTimezone('UTC')->format('h:i A');
        }
        
        // Format temperatures
        $weatherData['main']['temp_celsius'] = round($response['main']['temp'] ?? 0);
        $weatherData['main']['feels_like_celsius'] = round($response['main']['feels_like'] ?? 0);
        
        // Format wind speed
        $weatherData['wind']['speed_kmh'] = round(($response['wind']['speed'] ?? 0) * 3.6, 1);
        
        // Set weather icon
        $weatherData['weather_icon'] = $this->getWeatherIcon(
            $response['weather'][0]['main'] ?? 'Clouds'
        );
        
        return $weatherData;
    }

    /**
     * Get total products count
     */
    private function getTotalProducts(): int
    {
        return Product::count();
    }

    /**
     * Get total orders count (user + guest)
     */
    private function getTotalOrders(): int
    {
        return UserOrder::count() + GuestOrder::count();
    }

    /**
     * Get total users count (excluding admins)
     */
    private function getTotalUsers(): int
    {
        return User::where('role', 'user')->count();
    }

    /**
     * Get total revenue (user + guest)
     */
    private function getTotalRevenue(): float
    {
        return UserOrder::sum('total_amount') + GuestOrder::sum('total_amount');
    }

    /**
     * Get current month revenue
     */
    private function getMonthlyRevenue(): float
    {
        $startOfMonth = now()->startOfMonth();
        
        return UserOrder::where('created_at', '>=', $startOfMonth)->sum('total_amount') +
               GuestOrder::where('created_at', '>=', $startOfMonth)->sum('total_amount');
    }

    /**
     * Get current week revenue
     */
    private function getWeeklyRevenue(): float
    {
        $startOfWeek = now()->startOfWeek();
        
        return UserOrder::where('created_at', '>=', $startOfWeek)->sum('total_amount') +
               GuestOrder::where('created_at', '>=', $startOfWeek)->sum('total_amount');
    }

    /**
     * Get recent orders (user + guest)
     */
    private function getRecentOrders(): \Illuminate\Support\Collection
    {
        $userOrders = UserOrder::with('items.product')->latest()->limit(5)->get();
        $guestOrders = GuestOrder::with('items.product')->latest()->limit(5)->get();
        
        return $userOrders->merge($guestOrders)
                         ->sortByDesc('created_at')
                         ->take(5);
    }

    /**
     * Get products with low stock
     */
    private function getLowStockProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::where('quantity', '<=', 10)
                     ->orderBy('quantity', 'asc')
                     ->limit(5)
                     ->get();
    }

    /**
     * Get category breakdown with product counts
     */
    private function getCategoryBreakdown(): \Illuminate\Support\Collection
    {
        return DB::table('categories')
            ->select('categories.name', DB::raw('COUNT(products.id) as product_count'))
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->groupBy('categories.id', 'categories.name')
            ->limit(5)
            ->get();
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts(): \Illuminate\Support\Collection
    {
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

        $combined = DB::query()
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
            ->get();

        return $combined->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->name,
                'order_count' => $item->total_orders,
                'revenue' => $item->total_revenue
            ];
        });
    }

    /**
     * Get weather icon based on condition
     */
    private function getWeatherIcon(string $condition): string
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