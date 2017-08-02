<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuccessfulLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('successful_login', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('dt');
            $table->integer('user_id');
            $table->string('ip', 45);
            $table->string('user_agent', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('successful_login');
    }
}