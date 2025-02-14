<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{

    public function up()
    {
        if(Schema::hasTable('blogs')){
            return ;
        }

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id');
            $table->text('title');
            $table->text('slug')->nullable();
            $table->longText('blog_content');
            $table->string('image');
            $table->string('author')->nullable();
            $table->string('excerpt')->nullable();
            $table->string('tags')->nullable();
            $table->string('image_gallery')->nullable();
            $table->string('views')->nullable();
            $table->text('video_url')->nullable();
            $table->string('visibility')->nullable();
            $table->string('featured')->nullable();
            $table->string('created_by')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        if(!Schema::hasTable('blogs')){
            return ;
        }

        Schema::dropIfExists('blogs');
    }
}
