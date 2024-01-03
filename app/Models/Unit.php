<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'slug',
        'description',
        'status',
    ];

    public final function uniteValue() :HasOne
    {
        return $this->hasOne(UnitValue::class,'id','name');
    }
}
