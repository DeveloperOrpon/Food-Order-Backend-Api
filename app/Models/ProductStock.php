<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;
    /**
     *
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * */
    protected $fillable = [
        'product_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
