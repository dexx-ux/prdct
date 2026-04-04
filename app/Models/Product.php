<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'discount_value',
        'category_id',
        'image',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getFinalPriceAttribute()
{
    if (!$this->discount_value) {
        return $this->price;
    }

    // determine if the value indicates a percentage (ends with '%')
    $raw = trim($this->discount_value);
    $isPercent = str_ends_with($raw, '%');

    if ($isPercent) {
        $num = floatval(rtrim($raw, '%'));
        return $this->price - ($this->price * ($num / 100));
    }

    // otherwise treat as fixed amount
    $num = floatval($raw);
    return max(0, $this->price - $num);
}

/**
 * Query scope for items considered "low stock".
 *
 * @param \Illuminate\Database\Eloquent\Builder $query
 * @param int $threshold
 * @return \Illuminate\Database\Eloquent\Builder
 */
public function scopeLowStock($query, $threshold = 5)
{
    return $query->where('quantity', '<=', $threshold)->where('quantity', '>', 0);
}
}

