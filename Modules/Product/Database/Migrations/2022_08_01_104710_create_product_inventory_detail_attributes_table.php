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
        Schema::create('product_inventory_detail_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('inventory_details_id');
            $table->string('attribute_name')->nullable();
            $table->string('attribute_value')->nullable();
            $table->foreign("product_id")->references("id")->on("products")->cascadeOnDelete();
            $table->foreign("inventory_details_id")->references("id")->on("product_inventory_details")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_inventory_detail_attributes');
    }
};
