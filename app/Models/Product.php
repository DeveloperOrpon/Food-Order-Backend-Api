<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'slug',
        'short_description',
        'details',
        'category_ids',
        'brand_id',
        'added_by',
        'units',
        'min_qty',
        'refundable',
        'images',
        'thumbnail',
        'featured',
        'flash_deal',
        'video_provider',
        'video_url',
        'variant_product',
        'attributes',
        'choice_options',
        'variation',
        'published',
        'unit_price',
        'purchase_price',
        'regular_price',
        'offer_price',
        'tax',
        'tax_type',
        'discount',
        'discount_type',
        'current_stock',
        'free_shipping',
        'attachment',
        'featured_status',
        'on_sale',
        'purchasable',
        'date_on_sale_from',
        'date_on_sale_to',
        'updated_at',
        'created_at',
    ];
    protected $casts = [
        'brand_id' => 'integer',
        'min_qty' => 'integer',
        'published' => 'integer',
        'tax' => 'float',
        'unit_price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'current_stock' => 'integer',
        'free_shipping' => 'integer',
        'featured_status' => 'integer',
        'refundable' => 'integer',
        'featured' => 'integer',
        'flash_deal' => 'integer',
        'seller_id' => 'integer',
        'purchase_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['rating','reviewCount'];

    /**
     * Accessors
     */
    public function getRatingAttribute()
    {
        return round($this->reviews()->average('rating'), 2) ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Query scops
     */
    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeStatus($query)
    {
        return $query->where('featured_status', 1);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    //
    public function reviews()
    {
        return $this->hasMany(Review::class);
    } public function categories()
    {
        return $this->hasMany(Category::class,'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }  public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    } public function unit()
    {
        return $this->belongsTo(Unit::class,'units');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'user_id');
    }

    public function rating()
    {
        return $this->hasMany(Review::class)
            ->select(DB::raw('avg(rating) average, product_id'))
            ->groupBy('product_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function wish_list()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

}
