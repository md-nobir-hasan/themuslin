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
        Schema::create('zone_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("zone_country_id")->index();
            $table->unsignedBigInteger("state_id")->index();
            $table->foreign("zone_country_id")->references("id")->on("zone_countries")->cascadeOnDelete();
            $table->foreign("state_id")->references("id")->on("states")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zone_states');
    }
};
