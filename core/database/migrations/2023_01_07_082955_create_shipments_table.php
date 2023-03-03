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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('awb')->nullable();
            $table->integer('shipper_id');
            $table->integer('receiver_id');
            $table->integer('service_id')->nullable();
            $table->integer('agent_id');
            $table->string('description')->nullable();
            $table->string('shipper_reference')->nullable();
            $table->timestamp('booking_date')->useCurrent();
            $table->timestamp('estimated_delivery_date')->nullable();
            $table->integer('connected_company')->nullable();
            $table->string('connected_reference')->nullable();
            $table->json('updates')->nullable();
            $table->string('weight')->nullable();
            $table->string('operator')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('shipments');
    }
};
