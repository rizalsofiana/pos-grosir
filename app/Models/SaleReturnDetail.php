<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['sale_return_id', 'sale_detail_id', 'product_id', 'quantity', 'price', 'sub_total', 'condition'])]
#[Table('sale_return_details')]
class SaleReturnDetail extends Model
{
    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
