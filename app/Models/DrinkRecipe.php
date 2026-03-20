<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DrinkRecipe extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'instructions',
        'prep_time',
        'difficulty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'prep_time' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (DrinkRecipe $model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });

        static::updating(function (DrinkRecipe $model) {
            if ($model->isDirty('name')) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });
    }

    protected static function generateUniqueSlug(DrinkRecipe $model): string
    {
        $base = Str::slug($model->name);
        $slug = $base;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->where('id', '!=', $model->id ?? 0)
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->useDisk('s3')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('card')
            ->width(800)
            ->height(500)
            ->sharpen(10)
            ->nonQueued();
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(DrinkRecipeIngredient::class)->orderBy('sort_order');
    }

    public function occasions(): BelongsToMany
    {
        return $this->belongsToMany(Occasion::class, 'drink_recipe_occasion');
    }
}
