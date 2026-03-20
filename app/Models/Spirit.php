<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Spirit extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'spirit_type_id',
        'producer_id',
        'country_id',
        'description',
        'alcohol_content',
        'barcode',
        'is_active',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'alcohol_content' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Spirit $model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });

        static::updating(function (Spirit $model) {
            if ($model->isDirty('name')) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });
    }

    protected static function generateUniqueSlug(Spirit $model): string
    {
        $base = Str::slug($model->name);
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
            ->useDisk('s3');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 200, 400)
            ->sharpen(10)
            ->keepOriginalImageFormat()
            ->nonQueued();

        $this->addMediaConversion('card')
            ->fit(Fit::Contain, 512, 1024)
            ->sharpen(10)
            ->keepOriginalImageFormat()
            ->nonQueued();
    }

    public function spiritType(): BelongsTo
    {
        return $this->belongsTo(SpiritType::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

}
