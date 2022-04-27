<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id')->startingValue(567741);
            $table->unsignedInteger('user_id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('amount');
            $table->string('total_discounts')->nullable();
            $table->string('total_paid')->nullable();
            $table->integer('tax_rate')->nullable();
            $table->dateTime('du_time')->nullable(); // modat zaman bagimande ta payane zaman factor
            $table->dateTime('pay_time')->nullable(); // zamani ke variz anjam shod
            $table->string('invoice_creator'); // factor tavasote ki ijad shode ?
            $table->string('status'); // Vaziyate Factor | pardakht shode ya nashode

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
        Schema::dropIfExists('invoices');
    }
}
