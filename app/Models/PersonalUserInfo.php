<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalUserInfo extends Model
{
    use HasFactory;
    protected $guarded = ["id", "user_id", "created_at", "updated_at"];


    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected $fillable = [
        'user_id',
        'father_name',
        'gender',
        'date_of_birth',
        'shenasname_no',
        'mellicode',
        'home_address',
        'phone_number',
        'image_mellicard',
        'image_selfi',
        'verified',
        'status',
        'user_verified_at',
    ];
}
