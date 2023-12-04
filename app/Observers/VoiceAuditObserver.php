<?php

namespace App\Observers;

use App\Models\VoiceAudit;

class VoiceAuditObserver
{
    /**
     * Handle the VoiceAudit "created" event.
     *
     * @param  \App\Models\VoiceAudit  $voiceAudit
     * @return void
     */
    public function created(VoiceAudit $voiceAudit)
    {
        //
    }

    /**
     * Handle the VoiceAudit "updated" event.
     *
     * @param  \App\Models\VoiceAudit  $voiceAudit
     * @return void
     */
    public function updated(VoiceAudit $voiceAudit)
    {
        //
    }

    /**
     * Handle the VoiceAudit "deleted" event.
     *
     * @param  \App\Models\VoiceAudit  $voiceAudit
     * @return void
     */
    public function deleted(VoiceAudit $voiceAudit)
    {
        //
    }

    /**
     * Handle the VoiceAudit "restored" event.
     *
     * @param  \App\Models\VoiceAudit  $voiceAudit
     * @return void
     */
    public function restored(VoiceAudit $voiceAudit)
    {
        //
    }

    /**
     * Handle the VoiceAudit "force deleted" event.
     *
     * @param  \App\Models\VoiceAudit  $voiceAudit
     * @return void
     */
    public function forceDeleted(VoiceAudit $voiceAudit)
    {
        //
    }
}
