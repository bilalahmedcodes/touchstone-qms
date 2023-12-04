<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceAuditAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_audit_appeals', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_audit_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
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
        Schema::dropIfExists('voice_audit_appeals');
    }
}
