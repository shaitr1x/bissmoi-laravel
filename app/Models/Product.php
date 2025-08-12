<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'stock_quantity',
        'manage_stock',
        'status',
        'images',
        'weight',
        'sku',
        'featured',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'manage_stock' => 'boolean',
        'featured' => 'boolean',
        'images' => 'array',
    ];

    // Auto-generate slug from name
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    // Accessors
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('approved', true)->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('approved', true)->count();
    }


    // Obtenir l'image principale du produit
    public function getImageAttribute()
    {
        $images = $this->images;
        // S'assurer que c'est un tableau
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        // Cas champ images (array)
        if (is_array($images) && count($images) > 0) {
            return $images[0];
        }
        return null;
    }

    // Obtenir l'URL complète de l'image principale
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Si c'est déjà un chemin complet depuis images/
            if (str_starts_with($this->image, 'images/')) {
                $publicPath = public_path($this->image);
                if (file_exists($publicPath)) {
                    return asset($this->image);
                }
            } else {
                // Sinon, c'est juste un nom de fichier, on cherche dans images/products/
                $filename = basename($this->image);
                $publicPath = public_path('images/products/' . $filename);
                if (file_exists($publicPath)) {
                    return asset('images/products/' . $filename);
                }
            }
        }
        return asset('images/default-product.svg'); // Image par défaut
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}
