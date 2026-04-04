<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestOrderItem extends Model
{
    use HasFactory;

    protected $table = 'guest_order_items';
    
    protected $fillable = [
        'guest_order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    public function order()
    {
        return $this->belongsTo(GuestOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}