<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vendor_withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->decimal("amount")->nullable();
            $table->unsignedBigInteger("gateway_id")->index();
            $table->foreignId("vendor_id")->constrained();
            $table->string("request_status");
            $table->text("gateway_fields");
            $table->text("note")->nullable();
            $table->string("image")->nullable();
            $table->foreign("gateway_id")->references("id")->on("vendor_wallet_gateways");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_withdraw_requests');
    }
};