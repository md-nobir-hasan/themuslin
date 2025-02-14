<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxAmountAndTaxTypeToSubOrderItemsTable extends Migration
{
    public function up(): void
    {
        Schema::table('sub_order_items', function (Blueprint $table) {
            $table->decimal("tax_amount")->default(0);
            $table->string("tax_type")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sub_order_items', function (Blueprint $table) {
            $table->dropColumn("tax_amount");
            $table->dropColumn("tax_type");
        });
    }
}
