<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_transactions', function (Blueprint $table) {
            $table->increments('id')->startingValue(56214);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id');

            $table->string('investment_detail'); // sarmayegozari dar che moredi ?
            $table->string('amount');
            $table->string('note')->nullable();

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
        Schema::dropIfExists('profit_transactions');
    }
}
