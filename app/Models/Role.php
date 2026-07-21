<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
#[Table('roles')]
class Role extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
