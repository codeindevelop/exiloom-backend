<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralRatesToIrtRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_rates_to_irt_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->string('baseCurrencyName');
            $table->string('priceChange');
            $table->string('priceChangePercent');
            $table->string('lastPrice');
            $table->string('highPrice');
            $table->string('lowPrice');
            $table->string('buyRate');
            $table->string('sellRate');
            $table->string('targetCurrencyId');
            $table->string('targetCurrencyLocalizedName');
            $table->string('targetCurrencyName');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_rates_to_irt_rates');
    }
}
