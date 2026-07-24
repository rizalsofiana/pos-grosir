<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['setting_key', 'setting_value'])]
#[Table('settings')]
class Setting extends Model
{
    public static function getValue(string $key, ?string $default = null): ?string
    {
        return static::where('setting_key', $key)->value('setting_value') ?? $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
    }
}

