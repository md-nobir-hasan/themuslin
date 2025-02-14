<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSellInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sell_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('phone');
            $table->string('country')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('product_id')->nullable();
            $table->string('coupon')->nullable();
            $table->string('coupon_discounted')->nullable();
            $table->string('total_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_gateway')->nullable();
            $table->string('payment_track')->nullable();
            $table->string('transaction_id')->nullable();
            $table->longText('order_details')->nullable();
            $table->string('shipping_address_id')->nullable();
            $table->string('selected_shipping_option')->nullable();
            $table->string('checkout_type')->nullable();
            $table->text('checkout_image_path')->nullable();
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
        Schema::dropIfExists('product_sell_infos');
    }
}
