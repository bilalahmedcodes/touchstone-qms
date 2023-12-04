<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarLtEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_lt_evaluations', function (Blueprint $table) {
            $table->id();
            $table->enum('outcome',['Qualified','Not Qualified']);
            $table->string('notes');
            $table->string('customer_name');
            $table->integer('record_id');
            $table->string('customer_phone');
            $table->string('recording_link');
            $table->time('recording_duration');
            $table->date('call_date');
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
        Schema::dropIfExists('solar_lt_evaluations');
    }
}
