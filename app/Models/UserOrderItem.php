<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrderItem extends Model
{
    use HasFactory;

    protected $table = 'user_order_items';
    
    protected $fillable = [
        'user_order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(UserOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}