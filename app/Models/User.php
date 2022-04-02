<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'mobile_token',
        'last_name',
        'mobile_number',
        'mobile_verified_at',
        'activation_token',
        'active',
        'email',
        'password',
        'register_ip',
    ];

    protected $guard_name = 'api';


    protected static $logAttributes = [
        'first_name',
        'last_name',
        'mobile_number',
        'activation_token',
        'active',
        'email',
        'password',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
        'mobile_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Check user phone verification
    public function userMobileVerified()
    {
        return !is_null($this->mobile_verified_at);
    }


    // Get user personal information
    public function personalInfos()
    {
        return $this->hasOne(PersonalUserInfo::class);
    }
}
