<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('custom_order_tracks', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->unsignedBigInteger("image_id")->nullable();
            $table->timestamps();
            $table->foreign("image_id")->references("id")->on("media_uploads");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_order_tracks');
    }
};