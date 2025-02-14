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
    public function up(): void
    {
        Schema::create('zone_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("zone_id")->index();
            $table->unsignedBigInteger("country_id")->index();
            $table->foreign("country_id")->references("id")->on("countries")->cascadeOnDelete();
            $table->foreign("zone_id")->references("id")->on("zones")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zone_countries');
    }
};
