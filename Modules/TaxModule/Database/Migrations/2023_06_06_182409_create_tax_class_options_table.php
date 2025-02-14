<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tax_class_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('tax_name');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('priority');
            $table->boolean('is_compound')->nullable();
            $table->boolean('is_shipping')->nullable();
            $table->float('rate');
            $table->timestamps();
            $table->foreign("class_id")->references("id")->on("tax_classes");
            $table->foreign("country_id")->references("id")->on("countries");
            $table->foreign("state_id")->references("id")->on("states");
            $table->foreign("city_id")->references("id")->on("cities");
        });
    }
};
