<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->integer('profit_balance')->default(0);
            $table->integer('investment_balance')->default(0);
            $table->integer('irt_balance')->default(0);
            $table->integer('avalible_irt_balance')->default(0);
            $table->integer('usd_balance')->default(0);
            $table->integer('cad_balance')->default(0);
            $table->integer('pound_balance')->default(0);
            $table->integer('btc_balance')->default(0);
            $table->integer('usdt_balance')->default(0);
            $table->integer('eth_balance')->default(0);
            $table->integer('bch_balance')->default(0);
            $table->integer('dash_balance')->default(0);
            $table->integer('ltc_balance')->default(0);
            $table->integer('imc_balance')->default(0);
            $table->integer('usdterc20_balance')->default(0);
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
        Schema::dropIfExists('wallets');
    }
}
