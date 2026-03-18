<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WineType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (WineType $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function (WineType $model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function wines(): HasMany
    {
        return $this->hasMany(Wine::class);
    }
}
