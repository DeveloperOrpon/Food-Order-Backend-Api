<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *
     * */
    protected $fillable = [
        'name',
        'slug',
        'description',
        "parent_id",
        'brand_id',
        'logo',
        'banner',
        'status',
        'created_by',
        'updated_by'
    ];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category', 'attribute_id', 'id');
    }
    public function brands(){
        return $this->belongsToMany(Brand::class, 'brand_category', 'brand_id', 'id');
    }
    public function products(){
        return $this->belongsToMany(Product::class, 'product_category', 'id', 'id');
    }
    public function categories(){
        return $this->belongsToMany(Category::class, 'category_category', 'id', 'id');
    }

    public  function brand()
    {
        return $this->hasOne(Brand::class,'id','brand_id');
    }

    public function updateAdmin()
    {
        return $this->hasOne(Admin::class,'id','updated_by');
    }
    public final function parentCategory()
    {
        return $this->hasOne(Category::class,'id','parent_id');
    }


    protected static function booted()
    {
        static::deleted(function ($category) {
            $affectedProducts = Product::whereRaw("FIND_IN_SET(?, category_ids)", [$category->id])->get();
            foreach ($affectedProducts as $product) {
                $product->delete();
            }
        });
    }
}
