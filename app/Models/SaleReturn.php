<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['sale_id', 'customer_id', 'user_id', 'return_date', 'reason', 'notes', 'total_amount'])]
#[Table('sale_returns')]
class SaleReturn extends Model
{
    protected $casts = [
        'return_date' => 'datetime',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }
}
