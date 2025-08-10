<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'merchant_id',
        'status',
        'total_amount',
        'tax_amount',
        'shipping_amount',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status',
        'notes',
        'delivery_address',
        'phone',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
    ];

    // Auto-generate order number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            $order->order_number = 'BSM-' . strtoupper(uniqid());
        });
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->total_amount - $this->tax_amount - $this->shipping_amount;
    }
}
