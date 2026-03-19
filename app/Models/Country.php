<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }

    public function producers(): HasMany
    {
        return $this->hasMany(Producer::class);
    }

    public function wines(): HasMany
    {
        return $this->hasMany(Wine::class);
    }

    public function spirits(): HasMany
    {
        return $this->hasMany(Spirit::class);
    }
}
