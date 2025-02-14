<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_order_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('sub_order_id');
            $table->string('commission_type')->nullable();
            $table->string('commission_amount')->nullable();
            $table->boolean('is_individual_commission')->default(false);
            $table->timestamps();
            $table->foreign("vendor_id")->references("id")->on("vendors");
            $table->foreign("sub_order_id")->references("id")->on("sub_orders");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_order_commissions');
    }
};
