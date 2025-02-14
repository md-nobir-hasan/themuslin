<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string("slug");
            $table->tinyText("description");
            $table->unsignedBigInteger('image')->nullable();
            $table->unsignedBigInteger("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("status")->references("id")->on("statuses");
            $table->foreign("image")->references("id")->on("media_uploads");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
