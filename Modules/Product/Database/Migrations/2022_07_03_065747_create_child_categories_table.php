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
        Schema::create('product_child_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name")->index()->unique();
            $table->string("slug")->index()->unique();
            $table->tinyText("description")->nullable();
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("sub_category_id");
            $table->unsignedBigInteger("image_id");
            $table->unsignedBigInteger("status_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("category_id")->references("id")->on("product_categories");
            $table->foreign("sub_category_id")->references("id")->on("product_sub_categories");
            $table->foreign("image_id")->references("id")->on("media_uploads");
            $table->foreign("status_id")->references("id")->on("statuses");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('child_categories');
    }
};
