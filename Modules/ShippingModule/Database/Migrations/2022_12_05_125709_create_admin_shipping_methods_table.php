<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("zone_id");
            $table->string("title");
            $table->decimal("cost");
            $table->unsignedBigInteger("status_id");
            $table->boolean("is_default")->default(false);
            $table->timestamps();
            $table->foreign("zone_id")->references("id")->on("zones")->cascadeOnDelete();
            $table->foreign("status_id")->references("id")->on("statuses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_shipping_methods');
    }
};
