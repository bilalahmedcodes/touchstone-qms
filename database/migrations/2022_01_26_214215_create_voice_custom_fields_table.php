<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_evaluation_id');
            $table->integer('campaign_id')->nullable()->unsigned();
            $table->string('label');
            $table->string('placeholder')->nullable();
            $table->enum('type', ['text', 'dropdown', 'textarea'])->default('text');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->string('position')->default('top');
            $table->longText('options')->nullable();
            $table->integer('required')->unsigned()->default(0);
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
        Schema::dropIfExists('voice_custom_fields');
    }
}
