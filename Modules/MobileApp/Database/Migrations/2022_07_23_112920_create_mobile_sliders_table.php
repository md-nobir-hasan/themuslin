<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_sliders', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->tinyText("description");
            $table->unsignedBigInteger("image_id");
            $table->string("button_text");
            $table->tinyText("url");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("image_id")->references("id")->on("media_uploads");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_sliders');
    }
}
