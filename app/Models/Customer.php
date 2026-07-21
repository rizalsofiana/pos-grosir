<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'phone', 'address'])]
#[Table('customers')]
class Customer extends Model
{
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
