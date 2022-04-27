<?php

namespace App\Models\Rates;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable=[
        'title',
        'code',
        'live_price',
        'min_price',
        'max_price',
        'status',
    ];

}
