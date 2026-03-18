<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id', 'website'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function wines(): HasMany
    {
        return $this->hasMany(Wine::class);
    }
}
