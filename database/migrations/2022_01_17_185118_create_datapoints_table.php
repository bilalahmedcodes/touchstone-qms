<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatapointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datapoints', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_evaluation_id')->unsigned()->default(1);
            $table->integer('datapoint_category_id')->unsigned();
            $table->string('name');
            $table->longText('question');
            $table->integer('sort')->default(1)->unsigned();
            $table->string('course_id')->nullable();
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->integer('added_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('datapoints');
    }
}
