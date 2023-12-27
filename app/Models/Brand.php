<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * */
    protected $fillable = [
        'brand_name',
        'brand_email',
        'company_id',
        'slug',
    ];

    public function products(){
        // return $this->belongsToMany(Product::class, 'product_brand', 'id', 'id');
    }
    public function categories(){
        return $this->belongsToMany(Category::class, 'category_brand', 'id', 'id');
    }


}