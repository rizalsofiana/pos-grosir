<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['purchase_return_id', 'product_id', 'sale_return_detail_id', 'quantity', 'price', 'sub_total'])]
#[Table('purchase_return_details')]
class PurchaseReturnDetail extends Model
{
    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function saleReturnDetail()
    {
        return $this->belongsTo(SaleReturnDetail::class);
    }
}
