<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['stock_opname_id', 'product_id', 'system_stock', 'physical_stock', 'difference', 'note'])]
#[Table('stock_opname_details')]
class StockOpnameDetail extends Model
{
    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
