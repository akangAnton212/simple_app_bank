<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTMutation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_mutation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uid_account');
            $table->timestamp('mutation_date');
            $table->float('nominal');
            $table->string('trans_type', 2)->comment('CR for Credit DT For Debit');
            $table->string('trans_code', 15);
            $table->float('balance');
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
        Schema::dropIfExists('t_mutation');
    }
}
