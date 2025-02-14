<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('vendor_wallet_gateways', function (Blueprint $table) {
            $table->id();
            $table->string("name")->index();
            $table->longText("filed")->nullable();
            $table->foreignId("status_id")->constrained();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_wallet_gateways');
    }
};