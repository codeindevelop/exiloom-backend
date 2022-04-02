<?php

namespace App\Jobs\User;

use App\Mail\Signup\ActiveUser;
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

class ProcessUserActive implements ShouldQueue
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


        $nowTime = Verta(Carbon::now())->format('H:i');
        $nowDate = Verta(Carbon::now())->format('j-m-Y');




        // Send email for notify user is active
        Mail::to($user->email)->send(new ActiveUser($user));
    }
}
