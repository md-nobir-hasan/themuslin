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
        Schema::table('products', function(Blueprint $table){
            $table->renameColumn('title', 'summary');
            $table->renameColumn('name', 'title');
            $table->renameColumn('is_inventory_worn_able', 'is_inventory_warn_able');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function(Blueprint $table){
            $table->renameColumn('title', 'name');
            $table->renameColumn('summary', 'title');
            $table->renameColumn('is_inventory_warn_able','is_inventory_worn_able');
        });
    }
};
