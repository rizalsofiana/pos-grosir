<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'user_id', 'opname_date', 'notes'])]
#[Table('stock_opnames')]
class StockOpname extends Model
{
    protected $casts = [
        'opname_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stockOpnameDetails()
    {
        return $this->hasMany(StockOpnameDetail::class);
    }
}
