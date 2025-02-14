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
        Schema::create('vendor_bank_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vendor_id");
            $table->string("bank_name")->nullable();
            $table->string("bank_email")->nullable();
            $table->string("bank_code")->nullable();
            $table->string("account_number")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("vendor_id")->references("id")->on("vendors")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_bank_infos');
    }
};
