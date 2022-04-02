<?php

namespace App\Jobs\User;

use App\Mail\Signup\UserSignup;
use App\Models\Profile\PersonalUserInfo;
use App\Models\Setting\UserNotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Crypt;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessUserRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    public $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        //
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $password = $this->password;



        $personalUserInfo = new PersonalUserInfo([
            'user_id' => $user->id,
            'account_type' => null,
            'nationality' => null,
            'father_name' => null,
            'gender' => null,
            'date_of_birth' => null,
            'shenasname_no' => null,
            'mellicode' => null,
            'passport_number' => null,
            'country_id' => 101,
            'home_address' => null,
            'phone_number' => null,
            'image_mellicard' => null,
            'image_shenasname' => null,
            'image_passport' => null,
            'verified' => false,
            'status' => 'تایید نشده',
            'user_verified_at' => null,
        ]);


        // // Save User Personal Info
        $user->personalInfos()->save($personalUserInfo);

        // Create notification setting to user
        $notification_setting = new UserNotificationSetting([
            'user_id' => $user->id,
            'login_email' => true,
            'login_sms' => false,
            'logout_email' => true,
            'logout_sms' => false,
            'create_transaction_email' => true,
            'create_transaction_sms' => true,
            'payed_transaction_email' => true,
            'payed_transaction_sms' => true,
            'create_withdraw_email' => true,
            'create_withdraw_sms' => true,
            'add_bank_email' => true,
            'add_bank_sms' => true,
            'pay_profit_email' => true,
            'pay_profit_sms' => true,
            'create_investment_email' => true,
            'create_investment_sms' => true,
            'create_ticket_email' => true,
            'create_ticket_sms' => true,
            'answer_ticket_email' => true,
            'answer_ticket_sms' => true,
            'store_verification_email' => true,
            'store_verification_sms' => true,
            'change_verification_email' => true,
            'change_verification_sms' => true
        ]);

        $notification_setting->save();




        // Check If Register type in register setting - mobie
        if (DB::table('auth_settings')->where('id', 1)->value('register_type') == "mobile") {

            // If not register type mobile
        } else {

            // check if email verification setting is on sending email else verify email now
            if (DB::table('auth_settings')->where('id', 1)->value('email_verification') != 1) {

                // update user email verify at now time
                DB::table('users')->where('id', $user->id)->update(array('email_verified_at' => Carbon::now()));
            } else {

                // Check notification system setting
                if (DB::table('notification_settings')->where('id', 1)->value('register_sms') == true) {
                    // Send Sms Config
                }

                // Check notification system setting
                if (DB::table('notification_settings')->where('id', 1)->value('register_email') == true) {

                    // Try Send Mail to User
                    Mail::to($user->email)->send(new UserSignup($user));
                }
            }
        }
    }
}
