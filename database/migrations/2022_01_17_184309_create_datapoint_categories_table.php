<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatapointCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datapoint_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_evaluation_id')->default(1)->unsigned();
            $table->string('name');
            $table->integer('sort')->default(1)->unsigned();
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
        Schema::dropIfExists('datapoint_categories');
    }
}
