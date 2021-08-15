<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('NIK', 16)->unique();
            $table->string('name', 50);
            $table->date('DOB');
            $table->string('POB', 50);
            $table->string('email', 50)->unique();
            $table->string('address');
            $table->string('province', 50);
            $table->string('city', 20);
            $table->string('postal_code', 20);
            $table->timestamp('register_date');
            $table->uuid('uid_register_by');
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
        Schema::dropIfExists('m_customer');
    }
}
