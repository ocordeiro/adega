<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    protected $appends = ['flag_emoji'];

    public function getFlagEmojiAttribute(): string
    {
        if (! $this->code || strlen($this->code) !== 2) {
            return '';
        }

        $code = strtoupper($this->code);

        return mb_chr(0x1F1E6 + ord($code[0]) - ord('A'))
             . mb_chr(0x1F1E6 + ord($code[1]) - ord('A'));
    }

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
