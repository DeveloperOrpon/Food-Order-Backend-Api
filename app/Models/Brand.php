<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

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
        'brand_logo',
        'description',
        'featured',
        'slug',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function products(){
        // return $this->belongsToMany(Product::class, 'product_brand', 'id', 'id');
    }
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_brand', 'id', 'id');
    }

    public  function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id', 'id');

    }

    public function admin(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Admin::class,'id',"updated_by");
    }

    protected static function boot(): void
    {
        parent::boot();
        static::updated(static function($user){
            $user->updated_at = now();
            $user->updated_by = Filament::auth()->user()->id;
        });
    }

}
