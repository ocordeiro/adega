<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GrapeVariety extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function wines(): BelongsToMany
    {
        return $this->belongsToMany(Wine::class, 'wine_grape_variety')
            ->withPivot('percentage');
    }
}
