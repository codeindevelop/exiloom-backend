<?php

namespace App\Mail\Logout;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserLogoutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nowTime;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user =  $user;

        $this->nowTime = Verta(Carbon::now())->format('H:i - j-m-Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.auth.logout.LogoutNotification')->subject('خروج از سیستم')->with([
            'first_name' => $this->user->first_name,
            'nowTime' => $this->nowTime,

        ]);
    }
}
