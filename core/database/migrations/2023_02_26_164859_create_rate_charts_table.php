<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_charts', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('zone_scheme_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('currency')->default('USD');
            $table->integer('fuel_charge')->nullable(); //percentage
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('rate_charts');
    }
};
