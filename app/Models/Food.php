<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Food extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'foods';

    protected $fillable = ['food_category_id', 'name', 'description'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
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
    }

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function wines(): BelongsToMany
    {
        return $this->belongsToMany(Wine::class, 'wine_food')
            ->withPivot('notes');
    }
}
