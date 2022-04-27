<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investment_contracts', function (Blueprint $table) {
            $table->increments('id')->startingValue(3843741);
            $table->unsignedInteger('user_id');
            $table->string('subject');
            $table->longText('contract_text');
            $table->string('contract_month');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('creator'); // Admin Or User
            $table->string('contract_amount'); 
          
            $table->timestamps();

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
        Schema::dropIfExists('investment_contracts');
    }
}
