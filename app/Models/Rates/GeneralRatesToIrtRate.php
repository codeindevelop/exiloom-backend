<?php

namespace App\Models\Rates;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralRatesToIrtRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'baseCurrencyName',
        'priceChange',
        'priceChangePercent',
        'lastPrice',
        'highPrice',
        'lowPrice',
        'buyRate',
        'sellRate',
        'targetCurrencyId',
        'targetCurrencyLocalizedName',
        'targetCurrencyName',
        'active',

    ];
}
