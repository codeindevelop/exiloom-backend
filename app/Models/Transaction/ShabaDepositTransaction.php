<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Models\Transaction\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShabaDepositTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'deposit_bank_name',
        'paygiri_number',
        'pay_time',
        'tax',
        'total_discounts',
        'total_paid',
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
