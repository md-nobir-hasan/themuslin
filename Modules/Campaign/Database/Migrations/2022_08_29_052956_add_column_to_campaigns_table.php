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
        Schema::table('campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger("admin_id")->nullable();
            $table->unsignedBigInteger("vendor_id")->nullable();
            $table->string("type")->nullable();
            $table->foreign("admin_id")->references("id")->on("admins");
            $table->foreign("vendor_id")->references("id")->on("vendors");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn("admin_id");
            $table->dropColumn("vendor_id");
            $table->dropColumn("type");
        });
    }
};
