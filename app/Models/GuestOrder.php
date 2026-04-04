<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrder extends Model
{
    use HasFactory;

    protected $table = 'guest_orders';  // Make sure this is NOT 'orders'
    
    protected $fillable = [
        'order_number',
        'session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
    ];

    protected $appends = [
        'product',
        'quantity',
        'total_price'
    ];

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

    public function items()
    {
        return $this->hasMany(GuestOrderItem::class, 'guest_order_id');
    }
}