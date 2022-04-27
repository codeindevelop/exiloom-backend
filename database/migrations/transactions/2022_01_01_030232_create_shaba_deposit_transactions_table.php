<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShabaDepositTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shaba_deposit_transactions', function (Blueprint $table) {
            $table->increments('id')->startingValue(84725);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id');
            $table->string('deposit_bank_name')->nullable(); // varize shaba bank
            $table->string('paygiri_number')->nullable();
            $table->dateTime('pay_time')->nullable();
            $table->string('tax')->nullable();
            $table->string('total_discounts')->nullable();
            $table->string('total_paid')->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shaba_deposit_transactions');
    }
}
