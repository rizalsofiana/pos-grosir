<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'scope', 'product_id', 'category_id', 'min_qty', 'discount_type', 'discount_value', 'is_active'])]
#[Table('discount_rules')]
class DiscountRule extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Hitung nominal diskon untuk sebuah baris item (product_id, category_id, qty, price).
     */
    public function calculateDiscount(int $quantity, float $price): float
    {
        if ($quantity < $this->min_qty) {
            return 0;
        }

        $lineTotal = $quantity * $price;

        if ($this->discount_type === 'percentage') {
            return round($lineTotal * ($this->discount_value / 100), 2);
        }

        // nominal: dianggap diskon per unit dikali quantity
        return min($this->discount_value * $quantity, $lineTotal);
    }
}
