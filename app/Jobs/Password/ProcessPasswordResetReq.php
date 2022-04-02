<?php

namespace App\Jobs\Password;

use App\Mail\PasswordReset\PasswordResetRequest;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessPasswordResetReq implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private $user;
    private $ip;
    private $passwordReset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip,$passwordReset)
    {
        //
        $this->user = $user;
        $this->ip = $ip;
        $this->token = $passwordReset->token;
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
        $token = $this->token;



        // Send email for notify user is active
        Mail::to($user->email)->send(new PasswordResetRequest($user, $ip,$token));
    }
}
