<?php

namespace App\Models\Wallet;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'profit_balance',
        'investment_balance',
        'irt_balance',
        'avalible_irt_balance',
        'usd_balance',
        'cad_balance',
        'pound_balance',
        'btc_balance',
        'usdt_balance',
        'eth_balance',
        'bch_balance',
        'dash_balance',
        'ltc_balance',
        'imc_balance',
        'usdterc20_balance',
    ];

    
    
    public function user()
    {
        return $this->hasOne(User::class)->withTimestamps();
    }
}
