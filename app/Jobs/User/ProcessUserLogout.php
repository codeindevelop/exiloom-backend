<?php

namespace App\Jobs\User;

use App\Mail\Logout\UserLogoutMail;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class ProcessUserLogout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    public $nowTime;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $nowTime)
    {
        //
        $this->user = $user;
        $this->nowTime = $nowTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $nowTime = $this->nowTime;


        // check user Notification setting to send email
        if (DB::table('user_notification_settings')->where('user_id', $user->id)->value('logout_email') == true) {

            Mail::to($user->email)->send(new UserLogoutMail($user, $nowTime));
        }

        // check user Notification setting to send email
        if (DB::table('user_notification_settings')->where('user_id', $user->id)->value('logout_sms') == true) {


            $nowTime = Verta(Carbon::now())->format('H:i - j-m-Y');
        }
    }
}
