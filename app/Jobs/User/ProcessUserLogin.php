<?php

namespace App\Jobs\User;

use App\Mail\Login\LoginNotification;
use App\Models\User;
use Carbon\Carbon;

use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessUserLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    public $userIp;
    private $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $userIp)
    {
        //
        $this->user = $user;
        $this->ip = $userIp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $ip = $this->ip;

        // check user Notification setting to send email
        if (DB::table('user_notification_settings')->where('user_id', $user->id)->value('login_email') == true) {

            Mail::to($user->email)->send(new LoginNotification($user, $ip));
        }

        // check user Notification setting to send email
        if (DB::table('user_notification_settings')->where('user_id', $user->id)->value('login_sms') == true) {


            $nowTime = Verta(Carbon::now())->format('H:i - j-m-Y');
        }
    }
}
