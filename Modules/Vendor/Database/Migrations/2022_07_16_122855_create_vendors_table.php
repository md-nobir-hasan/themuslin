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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string("owner_name");
            $table->string("business_name");
            $table->tinyText("description");
            $table->unsignedBigInteger("business_type_id");
            $table->unsignedBigInteger("status_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("status_id")->references("id")->on("statuses");
            $table->foreign("business_type_id")->references("id")->on("business_types");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};
