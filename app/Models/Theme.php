<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name',
        'color_primary',
        'color_secondary',
        'color_background',
        'color_text',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function active(): static
    {
        return static::where('is_active', true)->firstOrFail();
    }

    public function activate(): void
    {
        static::query()->update(['is_active' => false]);
        $this->update(['is_active' => true]);
    }

    public function restoreDefaults(): void
    {
        $this->update([
            'color_primary'    => $this->default_color_primary,
            'color_secondary'  => $this->default_color_secondary,
            'color_background' => $this->default_color_background,
            'color_text'       => $this->default_color_text,
        ]);
    }

    public function isAtDefaults(): bool
    {
        return $this->color_primary    === $this->default_color_primary
            && $this->color_secondary  === $this->default_color_secondary
            && $this->color_background === $this->default_color_background
            && $this->color_text       === $this->default_color_text;
    }
}
