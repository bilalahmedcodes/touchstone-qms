<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceAuditActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_audit_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_audit_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('voice_evaluation_action_id')->unsigned();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('voice_audit_actions');
    }
}
