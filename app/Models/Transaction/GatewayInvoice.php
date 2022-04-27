<?php

namespace App\Models\Transaction;

use App\Models\User;
use App\Models\Transaction\Invoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'authority_number',
        'amount',
        'gateway_name',
        'status',
    ];

    // Get Gatway invoice , system invoice detail
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
