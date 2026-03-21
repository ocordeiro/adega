<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ad extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'media_type',
        'display_duration',
        'is_active',
        'sort_order',
    ];

    protected $attributes = [
        'is_active'        => true,
        'sort_order'       => 0,
        'media_type'       => 'video',
        'display_duration' => null,
    ];

    protected $casts = [
        'is_active'        => 'boolean',
        'sort_order'       => 'integer',
        'display_duration' => 'integer',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('video')
            ->singleFile()
            ->useDisk('s3');

        $this->addMediaCollection('image')
            ->singleFile()
            ->useDisk('s3');
    }
}
