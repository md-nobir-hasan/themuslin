<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->text('sub_category_id')->nullable();
            $table->string('image')->nullable();
            $table->text('product_image_gallery')->nullable();
            $table->double('price')->nullable();
            $table->double('sale_price')->nullable();
            $table->double('tax_percentage')->nullable();
            $table->string('uom')->nullable();
            $table->double('unit')->nullable();
            $table->string('badge')->nullable();
            $table->string('status')->default('draft');
            $table->string('slug')->nullable();
            $table->longText('attributes')->nullable();
            $table->integer('sold_count')->nullable()->after('attributes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
