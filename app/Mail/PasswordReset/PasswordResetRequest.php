<?php

namespace App\Mail\PasswordReset;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PasswordResetRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $date;
    /**
     * @var string
     */
    private $time;
    private $ip;
    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip, $token)
    {
        $this->user =  $user;

        $this->date = Verta(Carbon::now())->format('j-n-Y');
        $this->time = Verta(Carbon::now())->format('H:i');
        $this->ip = $ip;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): PasswordResetRequest
    {
        return $this->view('email.auth.forgotPassword.passwordRequest')->subject('بازیابی رمز عبور')->with([
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'ip' => $this->ip,
            'date' => $this->date,
            'time' => $this->time,
            'token' => $this->token,

        ]);
    }
}
