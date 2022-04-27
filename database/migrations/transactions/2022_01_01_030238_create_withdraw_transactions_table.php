<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_transactions', function (Blueprint $table) {
            $table->increments('id')->startingValue(56214);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('withdraw_bank_id'); // bardasht az kife pol va variz be shomare hesabe ... ?
            $table->string('bank_varizkonandeh')->nullable();
            $table->string('amount');
            $table->string('paygiri_number')->nullable();
            $table->dateTime('pay_time')->nullable();
            $table->string('tax')->nullable();
            $table->string('total_paid')->nullable();
            $table->string('status');
            $table->longText('note')->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('withdraw_bank_id')->references('id')->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_transactions');
    }
}
