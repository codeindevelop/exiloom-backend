<?php

namespace App\Models\Rates;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CryptoRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'priceChange',
        'priceChangePercent',
        'lastPrice',
        'highPrice',
        'lowPrice',
        'active',

    ];
}
