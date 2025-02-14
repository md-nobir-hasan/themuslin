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
        Schema::create('order_payment_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->index();
            $table->decimal("sub_total");
            $table->decimal("coupon_amount")->default(0);
            $table->decimal("shipping_cost")->default(0);
            $table->decimal("tax_amount")->default(0);
            $table->decimal("total_amount")->default(0);
            $table->foreign("order_id")->references("id")->on("orders");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payment_metas');
    }
};
