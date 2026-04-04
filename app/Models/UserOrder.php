<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrder extends Model
{
    use HasFactory;

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
        'total_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address'
    ];

    public function getProductAttribute()
    {
        return $this->items->first()->product ?? null;
    }

    public function getCustomerNameAttribute()
    {
        return $this->attributes['customer_name'] ?? $this->user?->name ?? null;
    }

    public function getCustomerEmailAttribute()
    {
        return $this->attributes['customer_email'] ?? $this->user?->email ?? null;
    }

    public function getCustomerPhoneAttribute()
    {
        return $this->attributes['customer_phone'] ?? $this->user?->phone ?? null;
    }

    public function getCustomerAddressAttribute()
    {
        return $this->attributes['customer_address'] ?? $this->shipping_address ?? null;
    }

    public function getQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    public function getTotalPriceAttribute()
    {
        return $this->total_amount;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(UserOrderItem::class);
    }
}