<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable("shipping_method_options")){
            Schema::create('shipping_method_options', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->unsignedBigInteger('shipping_method_id');
                $table->boolean('status')->default(true);
                $table->boolean('tax_status')->default(true);
                $table->string('setting_preset')->nullable();
                $table->float('cost')->default(0);
                $table->float('minimum_order_amount')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_method_options');
    }
}
