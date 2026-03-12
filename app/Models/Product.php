<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;


use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable;

class Product extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia, Searchable;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(400)
             ->height(400)
             ->sharpen(10);
             
        $this->addMediaConversion('preview')
             ->width(150)
             ->height(150);
    }

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'stock_quantity',
        'low_stock_threshold',
        'is_out_of_stock',
        'gender',
        'is_featured',
        'is_bestseller',
        'status'
    ];

    public $translatable = ['name', 'description', 'short_description'];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'status' => 'boolean',
        'is_out_of_stock' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }


    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function fragranceNotes()
    {
        return $this->belongsToMany(FragranceNote::class, 'product_fragrance_note')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    public function topNotes()
    {
        return $this->fragranceNotes()->wherePivot('type', 'top');
    }

    public function heartNotes()
    {
        return $this->fragranceNotes()->wherePivot('type', 'middle');
    }

    public function baseNotes()
    {
        return $this->fragranceNotes()->wherePivot('type', 'base');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->low_stock_threshold && !$this->is_out_of_stock;
    }

    public function updateStockStatus()
    {
        $this->is_out_of_stock = $this->stock_quantity <= 0;
        $this->save();
    }

    /**
     * Get the best applicable coupon for this product.
     */
    public function getApplicableCoupon()
    {
        // 1. Check for specific coupons for this product
        $specificCoupon = Coupon::where('is_active', true)
            ->where('is_global', false)
            ->whereHas('products', function($query) {
                $query->where('products.id', $this->id);
            })
            ->get()
            ->filter(function($coupon) {
                return $coupon->isValid();
            })
            ->sortByDesc(function($coupon) {
                return $this->calculateDiscountValue($coupon);
            })
            ->first();

        // 2. Check for global coupons
        $globalCoupon = Coupon::where('is_active', true)
            ->where('is_global', true)
            ->get()
            ->filter(function($coupon) {
                return $coupon->isValid();
            })
            ->sortByDesc(function($coupon) {
                return $this->calculateDiscountValue($coupon);
            })
            ->first();

        // Return the one that gives the better discount
        if (!$specificCoupon) return $globalCoupon;
        if (!$globalCoupon) return $specificCoupon;

        return $this->calculateDiscountValue($specificCoupon) >= $this->calculateDiscountValue($globalCoupon) 
            ? $specificCoupon 
            : $globalCoupon;
    }

    /**
     * Calculate discount value for a given coupon and this product.
     */
    protected function calculateDiscountValue($coupon)
    {
        $basePrice = $this->sale_price ?: $this->price;
        if ($coupon->type === 'percent') {
            return $basePrice * ($coupon->value / 100);
        }
        return min($coupon->value, $basePrice);
    }

    /**
     * Get the final price after applying the best coupon.
     */
    public function getDiscountedPrice()
    {
        $basePrice = $this->sale_price ?: $this->price;
        $coupon = $this->getApplicableCoupon();
        
        if (!$coupon) return $basePrice;

        return max(0, $basePrice - $this->calculateDiscountValue($coupon));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        // Load relationships if not loaded
        $this->loadMissing(['category', 'brand', 'fragranceNotes']);

        return [
            'id' => (int) $this->id,
            'name' => $this->getTranslations('name'), // Index all translations
            'brand' => $this->brand ? $this->brand->name : null,
            'category' => $this->category ? $this->category->getTranslations('name') : null,
            'description' => $this->getTranslations('description'),
            'short_description' => $this->getTranslations('short_description'),
            'sku' => $this->sku,
            'fragrance_notes' => $this->fragranceNotes->map(function ($note) {
                return $note->getTranslations('name');
            })->flatten()->toArray(),
            'price' => (float) ($this->sale_price ?: $this->price),
            'status' => (bool) $this->status,
            'is_out_of_stock' => (bool) $this->is_out_of_stock,
            'gender' => $this->gender,
        ];
    }
}

