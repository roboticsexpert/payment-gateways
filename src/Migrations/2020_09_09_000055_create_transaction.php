<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id')->index();

            $table->unsignedDecimal('price', 9, 0);
            $table->unsignedInteger('invoice_id')->nullable()->index();

            $table->unsignedInteger('payment_gateway_id')->index();
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways');

            $table->string('card_number')->nullable()->index();
            $table->string('tracking_code')->nullable()->index();
            $table->string('authority')->nullable()->index();
            $table->dateTime('paid_at')->nullable()->index();
            $table->ipAddress('ip')->nullable();
            $table->text('information')->nullable();
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
        Schema::drop('transactions');

    }
}
