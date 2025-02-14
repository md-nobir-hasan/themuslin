<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("coupon")->nullable()->index();
            $table->decimal("coupon_amount")->nullable();
            $table->string("payment_track")->index();
            $table->string("payment_gateway")->index();
            $table->string("transaction_id")->index();
            $table->string("order_status")->index();
            $table->string("payment_status")->index();
            $table->bigInteger("invoice_number")->unique()->index();
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
        Schema::dropIfExists('orders');
    }
};
