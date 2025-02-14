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
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id")->index();
            $table->unsignedBigInteger("vendor_id")->nullable()->index();
            $table->decimal("total_amount");
            $table->decimal("shipping_cost")->nullable();
            $table->decimal("tax_amount")->nullable();
            $table->unsignedBigInteger("order_address_id")->nullable()->index();
            $table->timestamps();
            $table->foreign("order_id")->references("id")->on("orders");
            $table->foreign("vendor_id")->references("id")->on("vendors");
            $table->foreign("order_address_id")->references("id")->on("order_addresses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_orders');
    }
};
