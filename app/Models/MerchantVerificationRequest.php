<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantVerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'message',
        'business_phone',
        'has_physical_office',
        'office_address',
        'website_or_social',
        'business_description',
        'business_experience',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
