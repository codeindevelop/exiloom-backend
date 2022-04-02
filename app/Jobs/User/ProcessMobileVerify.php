<?php

namespace App\Jobs\User;

use App\Mail\mobile\MobileVerification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ProcessMobileVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    public $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip)
    {
        //
        $this->user = $user;
        $this->ip = $ip;
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


        $userMobile = $user->mobile_number;
        $userId = $user->id;
        $nowTime = Verta(Carbon::now())->format('H:i - j-m-Y');


        // // Send SMS verification code to user
        // Http::post(env('SMS_URL'), [
        //     'username' => env('SMS_USERNAME'),
        //     'password' => env('SMS_PASSWORD'),
        //     'text' => "{$user->first_name};{$userMobile};{$nowTime}",
        //     'to' => $user->mobile_number,
        //     'bodyId' => 49554,
        // ]);


        // Try Send Confirm Mobile number welcome Mail to User
        Mail::to($user->email)->send(new MobileVerification($user));
    }
}
