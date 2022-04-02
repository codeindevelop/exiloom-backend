<?php

namespace App\Mail\Signup;

use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UserSignup extends Mailable
{
    use Queueable, SerializesModels;

    protected  $user;



    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)

    {

        $this->user =  $user;
        $this->ip =  $user->register_ip;
        $this->activation_token =  $user->activation_token;
        $this->date = Verta(Carbon::now())->format('H:i | Y-n-j');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.auth.signup.usersignup')->subject('ثبت نام در صرافی اِکسیلوم')->with([
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,


        ]);
    }
}
