<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_payment_metas', function (Blueprint $table) {
            $table->string('payment_attachments')->nullable();
        });
    }

    public function down()
    {
        Schema::table('order_payment_metas', function (Blueprint $table) {
            $table->dropColumn('payment_attachments');
        });
    }
};