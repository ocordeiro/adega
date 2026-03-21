<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeverageReport extends Model
{
    protected $fillable = [
        'barcode',
        'type',
        'is_resolved',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];
}
