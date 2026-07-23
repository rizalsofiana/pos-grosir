<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'product_id', 'old_purchase_price', 'new_purchase_price',
    'old_selling_price', 'new_selling_price', 'user_id',
])]
#[Table('price_histories')]
class PriceHistory extends Model
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
