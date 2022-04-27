<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Models\Transaction\Invoice;
use App\Models\Transaction\GatewayInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineDepositTransaction extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'invoice_id',
        'gateway_id',
        'transaction_type',
        'amount',
        'pay_time',
        'tax',
        'total_discounts',
        'total_paid',
        'status',
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

    // Get Payment Gateway Detaile
    public function pay_gateway()
    {
        return $this->hasOne(GatewayInvoice::class);
    }

}
