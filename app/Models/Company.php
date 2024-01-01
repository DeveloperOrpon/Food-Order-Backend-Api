<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'description',
        'logo',
        'email',
        'email',
        'state',
    ];

    public function brands(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Brand::class,'company_id');
    }


}
