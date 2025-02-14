<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInventoryDetailsTable extends Migration
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
            $table->bigInteger('inventory_id');
            $table->bigInteger('product_id');
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->text('hash')->nullable();
            $table->float('additional_price')->default(0);
            $table->string('image')->nullable();
            $table->bigInteger('stock_count')->default(0);
            $table->bigInteger('sold_count')->default(0);
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
        Schema::dropIfExists('product_inventory_details');
    }
}
