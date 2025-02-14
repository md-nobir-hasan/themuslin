<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoneRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("zone_regions")){
            Schema::create('zone_regions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('zone_id');
                $table->longText('country')->nullable();
                $table->longText('state')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zone_regions');
    }
}
