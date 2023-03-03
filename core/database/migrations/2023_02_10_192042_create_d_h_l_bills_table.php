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
        Schema::create('d_h_l_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->nullable();
            $table->timestamp('bill_date')->useCurrent();
            $table->integer('total_bill')->nullable();
            $table->integer('balance')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            $table->boolean('readonly')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_h_l_bills');
    }
};
