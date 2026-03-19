<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Occasion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'description', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function wines(): BelongsToMany
    {
        return $this->belongsToMany(Wine::class, 'wine_occasion');
    }
}
