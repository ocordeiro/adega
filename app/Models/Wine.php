<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Wine extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'producer_id',
        'country_id',
        'region_id',
        'wine_type_id',
        'vintage',
        'description',
        'alcohol_content',
        'serving_temp_min',
        'serving_temp_max',
        'rating',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'stock_unit',
        'barcode',
        'is_active',
    ];

    protected $attributes = [
        'is_active'      => true,
        'stock_quantity' => 0,
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'cost_price'      => 'decimal:2',
        'sale_price'      => 'decimal:2',
        'alcohol_content' => 'decimal:2',
        'vintage'         => 'integer',
        'stock_quantity'  => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Wine $model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });

        static::updating(function (Wine $model) {
            if ($model->isDirty('name') || $model->isDirty('vintage')) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });
    }

    protected static function generateUniqueSlug(Wine $model): string
    {
        $base = Str::slug($model->name . ($model->vintage ? ' ' . $model->vintage : ''));
        $slug = $base;
        $count = 1;

        while (
            static::withTrashed()
                ->where('slug', $slug)
                ->where('id', '!=', $model->id ?? 0)
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photos')
            ->useDisk('s3')
            ->withResponsiveImages();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);

        $this->addMediaConversion('card')
            ->width(600)
            ->height(400)
            ->sharpen(10);
    }

    public function wineType(): BelongsTo
    {
        return $this->belongsTo(WineType::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function grapeVarieties(): BelongsToMany
    {
        return $this->belongsToMany(GrapeVariety::class, 'wine_grape_variety')
            ->withPivot('percentage');
    }

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'wine_food')
            ->withPivot('notes');
    }

    public function getStockValueAttribute(): float
    {
        return ($this->stock_quantity ?? 0) * ($this->sale_price ?? 0);
    }
}
