<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const DEFAULTS = [
        'element_scale' => 1.0,
    ];

    protected $fillable = [
        'element_scale',
    ];

    protected $casts = [
        'element_scale' => 'float',
    ];

    public static function instance(): static
    {
        return static::firstOrCreate(['id' => 1]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile()
            ->useDisk('s3');
    }
}
