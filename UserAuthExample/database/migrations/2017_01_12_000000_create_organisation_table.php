<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->string('al1');
            $table->string('al2')->nullable();
            $table->string('town');
            $table->string('region');
            $table->string('postcode');
            $table->string('country');

            $table->string('vat_number')->nullable();
            $table->string('company_number')->nullable();

            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();

            $table->integer('company_type_id');

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
        Schema::drop('organisation');
    }
}
