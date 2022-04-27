<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_invoices', function (Blueprint $table) {
            $table->increments('id')->startingValue(217741);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('invoice_id');
            $table->longText('authority_number');
            $table->string('amount');
            $table->string('gateway_name');
            $table->string('status');

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
        Schema::dropIfExists('gateway_invoices');
    }
}
