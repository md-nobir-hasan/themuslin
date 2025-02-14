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
        Schema::create('vendor_shop_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("vendor_id");
            $table->string("location")->nullable();
            $table->string("number")->nullable();
            $table->string("email")->nullable();
            $table->string("facebook_url")->nullable();
            $table->string("website_url")->nullable();
            $table->unsignedBigInteger("logo_id")->nullable();
            $table->unsignedBigInteger("cover_photo_id")->nullable();
            $table->timestamps();
            $table->foreign("vendor_id")->references("id")->on("vendors")->cascadeOnDelete();;
            $table->foreign("logo_id")->references("id")->on("media_uploads");
            $table->foreign("cover_photo_id")->references("id")->on("media_uploads");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_shop_infos');
    }
};
