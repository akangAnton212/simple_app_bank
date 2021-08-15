<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMAccountCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_account_customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid_customer');
            $table->string('account_number', 100)->unique();
            $table->string('account_pin', 6);
            $table->string('card_number', 100)->unique();
            $table->float('balance');
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
        Schema::dropIfExists('m_account_customer');
    }
}
