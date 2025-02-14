<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeDataTypeOfCountryTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country_taxes', function (Blueprint $table) {
            $this->changeColumnType('country_taxes','tax_percentage','float(8,2)');
        });
    }
    
    public function changeColumnType($table, $column, $newColumnType) {                
        DB::statement("ALTER TABLE $table CHANGE $column $column $newColumnType");
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_taxes', function (Blueprint $table) {
            $this->changeColumnType('country_taxes','tax_percentage','bigint(11)');
        });
    }
}
