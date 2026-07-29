<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['invoice_number', 'customer_id', 'debtor_name', 'user_id', 'sale_date', 'sub_total', 'discount', 'grand_amount', 'paid_amount', 'change_amount', 'payment_method', 'payment_status', 'due_date', 'midtrans_order_id', 'snap_token'])]
#[Table('sales')]
class Sale extends Model
{
    protected $casts = [
        'sale_date' => 'datetime',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function getPaidTotalAttribute(): float
    {
        $initial = $this->payment_status === 'unpaid' ? 0 : (float) $this->paid_amount;

        return $initial + (float) $this->payments()->sum('amount');
    }

    public function getOutstandingAttribute(): float
    {
        return max(0, (float) $this->grand_amount - $this->paid_total);
    }
}
