<?php

namespace App\Jobs\Deposit\OnlineDeposit;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVerifyOnlineDeposit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user ;
    public $amountData ;
    public $invoice_id ;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user , $amountData , $invoice_id)
    {
        $this->user =  $user ;
        $this->amountData =  $amountData ;
        $this->invoice_id =  $invoice_id ;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user ;
        $amountData = $this->amountData ;
        $invoice_id = $this->invoice_id ;


        // Send Invoice alert to user
        $apikey = env('SMS_API_KEY');
        $client = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json', 'Authorization' => "AccessKey {$apikey}"]
        ]);

         // Values to send
         $patternValues = [
            "transaction_number" => "$invoice_id->id",
            "amount" => "$amountData",
        ];


        // Begin Post sms
        $client->post(
            'http://rest.ippanel.com/v1/messages/patterns/send',
            ['body' => json_encode(
                [
                    'pattern_code' => "chpxiheea3",
                    'originator' => "+983000505",
                    'recipient' => $user->mobile_number,
                    'values' => $patternValues,
                ]
            )]
        );
    }
}
