<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id')->index();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->unsignedBigInteger('vendor_id')->index()->nullable();
            $table->unsignedBigInteger('sub_order_id')->index()->nullable();
            $table->double('amount')->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('type')->nullable()->comment("1 mean's incoming 2 mean's outgoing,3 mean's deposit");
            $table->string('payment_gateway')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();
            $table->foreign('wallet_id')->references("id")->on('wallets');
            $table->foreign('user_id')->references("id")->on('users');
            $table->foreign('vendor_id')->references("id")->on('vendors');
            $table->foreign('sub_order_id')->references("id")->on('sub_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_histories');
    }
}
