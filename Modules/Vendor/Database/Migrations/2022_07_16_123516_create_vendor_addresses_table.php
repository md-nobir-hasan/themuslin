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
        Schema::create('vendor_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vendor_id");
            $table->unsignedBigInteger("country_id")->nullable();
            $table->unsignedBigInteger("state_id")->nullable();
            $table->unsignedBigInteger("city_id")->nullable();
            $table->string("zip_code")->nullable();
            $table->tinyText("address")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("vendor_id")->references("id")->on("vendors")->cascadeOnDelete();;
            $table->foreign("country_id")->references("id")->on("countries");
            $table->foreign("state_id")->references("id")->on("states");
            $table->foreign("city_id")->references("id")->on("cities");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_addresses');
    }
};
