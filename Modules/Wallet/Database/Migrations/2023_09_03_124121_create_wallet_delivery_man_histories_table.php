<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_delivery_man_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id');
            $table->string('amount');
            $table->string('transaction_id')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('order_id')->nullable()->comment("This column is no needed column for this moment");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("wallet_id")->references("id")->on("wallets");
            $table->foreign("order_id")->references("id")->on("orders");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_delivery_man_histories');
    }
};
