<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'approved',
        'helpful_votes',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'rating' => 'integer',
        'helpful_votes' => 'integer',
        'approved_at' => 'datetime',
    ];

    // Relations
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopePopular($query)
    {
        return $query->orderByDesc('helpful_votes');
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }
}
