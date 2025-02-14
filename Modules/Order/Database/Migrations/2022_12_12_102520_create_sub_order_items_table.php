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
        Schema::create('sub_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sub_order_id")->index();
            $table->unsignedBigInteger("order_id")->index();
            $table->unsignedBigInteger("product_id")->nullable()->index();
            $table->unsignedBigInteger("variant_id")->nullable()->index();
            $table->unsignedBigInteger("quantity")->index();
            $table->decimal("price")->index();
            $table->decimal("sale_price")->index();
            $table->foreign("sub_order_id")->references("id")->on("sub_orders");
            $table->foreign("order_id")->references("id")->on("orders");
            $table->foreign("product_id")->references("id")->on("products");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_order_items');
    }
};
