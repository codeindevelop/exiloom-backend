<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineDepositTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_deposit_transactions', function (Blueprint $table) {
            $table->increments('id')->startingValue(36741);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('gateway_id');
            $table->string('transaction_type'); // sharje hesab - peyvastan be package
            $table->string('amount');
            $table->dateTime('pay_time')->nullable();
            $table->string('tax')->nullable();
            $table->string('total_discounts')->nullable();
            $table->string('total_paid')->nullable();
            $table->string('status');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('gateway_id')->references('id')->on('gateway_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposit_transactions');
    }
}
