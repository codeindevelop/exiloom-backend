<?php

namespace App\Jobs\Auth;

use App\Mail\Signup\UserSignup;
use App\Models\PersonalUserInfo;
use App\Models\User;
use App\Models\Wallet\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessUserRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;

        $personalUserInfo = new PersonalUserInfo([
            'user_id' => $user->id,
            'father_name' => null,
            'gender' => null,
            'date_of_birth' => null,
            'shenasname_no' => null,
            'mellicode' => null,
            'country_id' => 101,
            'home_address' => null,
            'phone_number' => null,
            'image_mellicard' => null,
            'verified' => false,
            'status' => 'user-registered',
            'user_verified_at' => null,
        ]);


        // Save User Personal Info
        $user->personalInfos()->save($personalUserInfo);


        $wallet = new Wallet([
            'profit_balance' => 0,
            'investment_balance' => 0,
            'irt_balance' => 150000,
            'avalible_irt_balance' => 0,
            'usd_balance' => 0,
            'cad_balance' => 0,
            'pound_balance' => 0,
            'btc_balance' => 0,
            'usdt_balance' => 0,
            'eth_balance' => 0,
            'bch_balance' => 0,
            'dash_balance' => 0,
            'ltc_balance' => 0,
            'imc_balance' => 0,
            'usdterc20_balance' => 0,
        ]);

        //save User Wallet
        $user->wallet()->save($wallet);


        // Start Send Welcome SMS to user


        $apikey = env('SMS_API_KEY');
        $client = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
        ]);

        // Values to send
        $patternValues = [
            "name" => $user->first_name,
            "mobile" => $user->mobile_number,
        ];


        // Begin Post sms
        $client->post(
            'https://rest.ippanel.com/v1/messages/patterns/send',
            ['body' => json_encode(
                [
                    'pattern_code' => "pupiq1iq22",
                    'originator' => "+983000505",
                    'recipient' => $user->mobile_number,
                    'values' => $patternValues,
                ]
            )]
        );


        // Try Send Mail to User
        // Mail::to($user->email)->send(new UserSignup($user));
    }
}
