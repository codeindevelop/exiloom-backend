<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Models\Transaction\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitTransaction extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'invoice_id',
        'investment_detail',
        'amount',
        'note',
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }



}
