<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VoiceAuditStatus extends Model
{
    use  SoftDeletes;
    protected $table = 'voice_audit_status';
    protected $guarded = [];

}
