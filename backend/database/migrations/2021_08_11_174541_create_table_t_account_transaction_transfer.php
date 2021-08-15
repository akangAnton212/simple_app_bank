<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTAccountTransactionTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_account_transaction_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trans_code', 15);
            $table->uuid('uid_account_sender');
            $table->string('trans_type', 2)->comment('CR for Credit DT For Debit');
            $table->float('nominal');
            $table->uuid('uid_account_destination');
            $table->timestamp('trans_date');
            $table->string('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->uuid('uid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_account_transaction_transfer');
    }
}
