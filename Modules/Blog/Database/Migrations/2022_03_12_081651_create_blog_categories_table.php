<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoriesTable extends Migration
{
    public function up()
    {
        if(Schema::hasTable("blog_categories")){
            return ;
        }

        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        if(!Schema::hasTable("blog_categories")){
            return ;
        }

        Schema::dropIfExists('blog_categories');
    }
}
