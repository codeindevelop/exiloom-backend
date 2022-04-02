<?php

namespace App\Mail\Login;

use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected  $user;
    protected  $ip;
    /**
     * @var string
     */
    protected $date;
    /**
     * @var string
     */
    protected $time;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip)
    {
        $this->user =  $user;

        $this->date = Verta(Carbon::now())->format('Y-n-j');
        $this->time = Verta(Carbon::now())->format('H:i');
        $this->ip = $ip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): LoginNotification
    {
        return $this->view('email.auth.login.loginNotification')->subject('اعلان ورود به حساب')->with([
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'ip' => $this->ip,
            'date' => $this->date,
            'time' => $this->time,

        ]);
    }
}
