<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'merchant_approved',
        'is_verified_merchant',
        'merchant_description',
        'merchant_phone',
        'merchant_address',
        'is_active',
        'shop_name',
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
        'merchant_approved' => 'boolean',
        'is_verified_merchant' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // Role methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMerchant()
    {
        return $this->role === 'merchant';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isApprovedMerchant()
    {
        return $this->role === 'merchant' && $this->merchant_approved;
    }

    /**
     * Vérifie si l'utilisateur est un marchand vérifié (badge)
     */
    public function getIsVerifiedMerchantAttribute()
    {
        return $this->role === 'merchant' && $this->attributes['is_verified_merchant'];
    }

    /**
     * Scope pour ne récupérer que les marchands vérifiés
     */
    public function scopeVerifiedMerchants($query)
    {
        return $query->where('role', 'merchant')->where('merchant_approved', true);
    }
}
