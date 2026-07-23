<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'product_id', 'type', 'quantity', 'stock_before', 'stock_after',
    'reference_type', 'reference_id', 'reason', 'note', 'user_id',
])]
#[Table('stock_movements')]
class StockMovement extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
