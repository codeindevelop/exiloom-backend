<?php

namespace App\Models\Contract;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'contract_text',
        'contract_month',
        'start_datetime',
        'end_datetime',
        'creator',
        'contract_amount',
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }
}
