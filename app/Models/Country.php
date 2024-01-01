<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jenssegers\Agent\Agent;

class Country extends Model
{
    use HasFactory;

    protected $fillable =[
        'flag',
        "phonecode",
        "code",
        "name"
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class,'country_id','id');
    }
    public function country(): HasMany
    {
        return $this->hasMany(self::class,);
    }

    public function cities  (): HasMany
    {
        return $this->hasMany(State::class,'country_id','id');
    }

//    protected static function booted(): void
//    {
//        static::creating(static function($country){
//            $country->update_at = now();
//            $country->create_at = now();
//        });
//        static::updated(static function($country){
//            $country->update_at = now();
//        });
//    }
}
