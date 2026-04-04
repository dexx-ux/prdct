<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Confirmation - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .confirmation-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .confirmation-header {
            background: linear-gradient(135deg, #1a2c3e 0%, #2c4a6e 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 0.5s ease-out;
        }
        
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        
        .order-details {
            padding: 2rem;
        }
        
        .order-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }
        
        .order-info-item {
            padding: 0.5rem;
        }
        
        .order-info-item label {
            font-weight: bold;
            color: #1a2c3e;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .order-info-item p {
            color: #555;
        }
        
        .order-items {
            margin-bottom: 1.5rem;
        }
        
        .order-items h3 {
            margin-bottom: 1rem;
            color: #1a2c3e;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-item:hover {
            background: #f8f9fa;
        }
        
        .order-item-details {
            flex: 1;
        }
        
        .order-item-name {
            font-weight: bold;
            color: #333;
        }
        
        .order-item-meta {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .order-item-price {
            text-align: right;
            font-weight: bold;
            color: #1a2c3e;
        }
        
        .order-total {
            background: linear-gradient(135deg, #1a2c3e 0%, #2c4a6e 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .order-total-label {
            font-size: 1.2rem;
        }
        
        .order-total-amount {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        
        .status-processing {
            background: #17a2b8;
            color: white;
        }
        
        .status-completed {
            background: #28a745;
            color: white;
        }
        
        .status-cancelled {
            background: #dc3545;
            color: white;
        }
        
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: bold;
        }
        
        .btn-primary {
            background: #1a2c3e;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2c4a6e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }
            
            .order-item {
                flex-direction: column;
                text-align: center;
            }
            
            .order-item-price {
                text-align: center;
                margin-top: 0.5rem;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-header">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase</p>
        </div>
        
        <div class="order-details">
            <div class="order-info">
                <div class="order-info-grid">
                    <div class="order-info-item">
                        <label>Order Number</label>
                        <p><strong>{{ $order->order_number ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="order-info-item">
                        <label>Order Date</label>
                        <p>{{ isset($order->created_at) ? $order->created_at->format('F j, Y g:i A') : 'N/A' }}</p>
                    </div>
                    <div class="order-info-item">
                        <label>Payment Method</label>
                        <p>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</p>
                    </div>
                    <div class="order-info-item">
                        <label>Payment Status</label>
                        <p>
                            <span class="status-badge status-{{ $order->payment_status ?? 'pending' }}">
                                {{ ucfirst($order->payment_status ?? 'Pending') }}
                            </span>
                        </p>
                    </div>
                    <div class="order-info-item">
                        <label>Order Status</label>
                        <p>
                            <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                                {{ ucfirst($order->status ?? 'Pending') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="order-items">
                <h3><i class="bi bi-bag-check"></i> Order Items</h3>
                @if(isset($order->items) && $order->items->count() > 0)
                    @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="order-item-details">
                                <div class="order-item-name">
                                    {{ $item->product->name ?? 'Product not found' }}
                                </div>
                                <div class="order-item-meta">
                                    Quantity: {{ $item->quantity ?? 0 }} × ₱{{ number_format($item->price ?? 0, 2) }}
                                </div>
                            </div>
                            <div class="order-item-price">
                                ₱{{ number_format($item->subtotal ?? 0, 2) }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; padding: 2rem; color: #666;">
                        <i class="bi bi-inbox"></i> No items found in this order.
                    </p>
                @endif
            </div>
            
            <div class="order-total">
                <span class="order-total-label">Total Amount:</span>
                <span class="order-total-amount">
                    ₱{{ number_format($order->total_amount ?? $order->total_price ?? 0, 2) }}
                </span>
            </div>
            
            @if(isset($order->customer_address) || isset($order->shipping_address))
            <div class="order-info" style="margin-top: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem;"><i class="bi bi-geo-alt"></i> Shipping Address</h4>
                <p>{{ $order->shipping_address ?? $order->customer_address ?? 'N/A' }}</p>
            </div>
            @endif
            
            <div class="button-group">
                <a href="{{ route('welcome') }}" class="btn btn-primary">
                    <i class="bi bi-house"></i> Continue Shopping
                </a>
                @if(!Auth::check())
                <a href="{{ route('register') }}" class="btn btn-success">
                    <i class="bi bi-person-plus"></i> Create Account
                </a>
                @endif
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="bi bi-shop"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>