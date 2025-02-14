<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger("is_taxable")->nullable()->default(false);
            $table->unsignedBigInteger("tax_class_id")->nullable();
            $table->foreign("tax_class_id")->references("id")->on("tax_classes");
        });
    }
};
