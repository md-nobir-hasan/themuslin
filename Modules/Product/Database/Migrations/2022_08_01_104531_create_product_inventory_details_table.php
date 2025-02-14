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
        Schema::create('product_inventory_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->text('hash')->nullable();
            $table->float('additional_price')->default(0);
            $table->float('add_cost')->nullable();
            $table->unsignedBigInteger('image')->nullable();
            $table->bigInteger('stock_count')->default(0);
            $table->bigInteger('sold_count')->default(0);
            $table->foreign("product_id")->references("id")->on("products")->cascadeOnDelete();
            $table->foreign("product_inventory_id")->references("id")->on("product_inventories");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_details');
    }
};
