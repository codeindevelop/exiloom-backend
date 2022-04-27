<?php

namespace App\Models\Bank;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname_on_card',
        'bank_name',
        'expire_date',
        'card_number',
        'sheba_no',
        'images',
        'approved',
    ];

     
    public function user()
    {
        return $this->hasOne(User::class);
    }

}
