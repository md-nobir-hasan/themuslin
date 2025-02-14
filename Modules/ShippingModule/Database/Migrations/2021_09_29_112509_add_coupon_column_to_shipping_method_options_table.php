<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCouponColumnToShippingMethodOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("shipping_method_options")){
            Schema::table('shipping_method_options', function (Blueprint $table) {
                $table->string('coupon')->nullable()->after('minimum_order_amount');
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
        Schema::table('shipping_method_options', function (Blueprint $table) {
            $table->dropColumn('coupon');
        });
    }
}
