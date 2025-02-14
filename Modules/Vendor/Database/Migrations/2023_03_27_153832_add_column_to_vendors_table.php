<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string("email")->nullable();
            $table->string("email_verified")->default(0);
            $table->string("email_verify_token")->nullable();
        });
    }
};
