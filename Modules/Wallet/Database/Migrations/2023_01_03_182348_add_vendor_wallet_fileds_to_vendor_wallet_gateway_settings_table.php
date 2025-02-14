<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('vendor_wallet_gateway_settings', function (Blueprint $table) {
            $table->text("fileds");
        });
    }

    public function down()
    {
        Schema::table('vendor_wallet_gateway_settings', function (Blueprint $table) {
            $table->dropColumn("fileds");
        });
    }
};