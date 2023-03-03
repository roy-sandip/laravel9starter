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
        Schema::create('rate_extra_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('rate_chart_id');
            $table->string('amount_type')->default('PERCENT'); //PERCENT - FIXED
            $table->string('count_type')->default('FIXED'); //FIXED - PERKG
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('rate_extra_charges');
    }
};
