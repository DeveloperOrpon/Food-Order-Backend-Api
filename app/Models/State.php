<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

//    public function cities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
//    {
////         return $this->belongsToMany(Product::class, 'product_brand', 'id', 'id');
//    }
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(City::class);
    }
}
