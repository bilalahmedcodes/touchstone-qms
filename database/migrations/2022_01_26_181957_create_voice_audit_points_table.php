<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceAuditPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_audit_points', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_audit_id');
            $table->integer('datapoint_category_id')->nullable()->unsigned();
            $table->integer('datapoint_id')->unsigned();
            $table->integer('answer')->default(6)->unsigned();
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
        Schema::dropIfExists('voice_audit_points');
    }
}
