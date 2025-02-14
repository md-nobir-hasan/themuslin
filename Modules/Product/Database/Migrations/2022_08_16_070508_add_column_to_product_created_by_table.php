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
        Schema::table('product_created_by', function (Blueprint $table) {
            $table->unsignedBigInteger("updated_by")->nullable();
            $table->string("updated_by_guard")->nullable();
            $table->unsignedBigInteger("deleted_by")->nullable();
            $table->string("deleted_by_guard")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_created_by', function (Blueprint $table) {

        });
    }
};
