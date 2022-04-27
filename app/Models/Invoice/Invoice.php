<?php

namespace App\Models\Invoice;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'title',
        'description',
        'amount',
        'total_discounts',
        'total_paid',
        'tax_rate',
        'du_time',
        'pay_time',
        'invoice_creator',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }



}
