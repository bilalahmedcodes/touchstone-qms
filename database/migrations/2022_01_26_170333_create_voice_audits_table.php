<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoiceAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voice_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('voice_evaluation_id');
            $table->integer('user_id');
            $table->integer('associate_id');
            $table->integer('team_lead_id')->nullable();
            $table->integer('campaign_id')->nullable();
            $table->date('call_date')->nullable();
            $table->float('percentage')->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('record_id')->nullable();
            $table->time('recording_duration', $precision = 0)->nullable();
            $table->string('recording_link')->nullable();
            $table->enum('outcome', ['pending', 'accepted', 'rejected'])->default('accepted');
            $table->enum('billable_status', ['billable', 'non-billable'])->default('billable');
            $table->longText('notes')->nullable();
            $table->integer('review_priority')->unsigned()->default(0);
            $table->enum('status', ['evaluated', 'appeal requested', 'appeal accepted', 'appeal rejected', 'action taken'])->default('evaluated');
            $table->time('evaluation_time', $precision = 0)->nullable();
            $table->float('communication')->default(0);
            $table->float('sales')->default(0);
            $table->float('compliance')->default(0);
            $table->float('customer_service')->default(0);
            $table->float('product_presentation')->default(0);
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
        Schema::dropIfExists('voice_audits');
    }
}
