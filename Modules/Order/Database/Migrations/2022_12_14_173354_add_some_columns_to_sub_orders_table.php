<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->bigInteger("order_number");
            $table->string("payment_status");
            $table->string("order_status");
        });
    }

    public function down()
    {
        Schema::table('sub_orders', function (Blueprint $table) {
            $table->dropColumn("order_number");
            $table->dropColumn("payment_status");
            $table->dropColumn("order_status");
        });
    }
};