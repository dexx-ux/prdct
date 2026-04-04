<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Legacy compatibility alias for user_orders
    protected $table = 'user_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_address',
        'billing_address'
    ];

    protected $appends = [
        'product',
        'quantity',
        'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(UserOrderItem::class, 'user_order_id');
    }

    public function getProductAttribute()
    {
        return $this->items->first()->product ?? null;
    }

    public function getQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalPriceAttribute()
    {
        return $this->total_amount;
    }
}
