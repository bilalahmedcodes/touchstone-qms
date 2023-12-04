<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolarLtEvaluationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solar_lt_evaluation_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('answer');
            $table->integer('evaluation_id');
            $table->integer('datapoint_id');
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
        Schema::dropIfExists('solar_lt_evaluation_responses');
    }
}
