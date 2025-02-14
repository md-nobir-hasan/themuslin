<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mobile_intros', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->unsignedBigInteger("image_id");
            $table->timestamps();
            $table->foreign("image_id")->references("id")->on("media_uploads");
        });
    }

    public function down()
    {
        Schema::dropIfExists('mobile_intros');
    }
};