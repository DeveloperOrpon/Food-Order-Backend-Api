<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Jenssegers\Agent\Agent;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'company_id',
        'avatar',
        'phone_number',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'role',
        'phone',
        'zip',
        'last_login_ip',
        'last_login_at',
        'last_login_from',
        'subscription_package_id',
        'fcm_token',
        'notification_preference',
        'is_active',
        'is_active',
        'updated_by',
        'updated_at',
        'gender',
        'date_of_birth',
        'cart_number',
        'is_cart_verified',
        'is_email_verified',
        'is_phone_verified',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    // public function wishList(){
    //     return $this->hasMany(WishList::class, 'user_id', 'id');
    // }

    protected static function booted(): void
    {
        static::creating(function($user){
            $user->role = 'user';
            $user->last_login_ip = request()->ip();
            $user->last_login_at = now();
            $agent = new Agent();
            $device_name = '';
            if ($agent->isDesktop()) {
                $device_name = 'desktop';
            } elseif ($agent->isTablet()) {
                $device_name = 'tablet';
            } elseif ($agent->isMobile()) {
                $device_name = 'mobile';
            }
            $user->last_login_from = $device_name;
        });
        static::updated(function($user){
            $user->last_login_ip = request()->ip();
            $user->last_login_at = now();
            $agent = new Agent();
            $device_name = '';
            if ($agent->isDesktop()) {
                $device_name = 'desktop';
            } elseif ($agent->isTablet()) {
                $device_name = 'tablet';
            } elseif ($agent->isMobile()) {
                $device_name = 'mobile';
            }
            $user->last_login_from = $device_name;
            $user->updated_at = now();
        });
    }

}
