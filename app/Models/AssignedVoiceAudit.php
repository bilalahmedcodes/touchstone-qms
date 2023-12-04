<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
use \Znck\Eloquent\Traits\BelongsToThrough;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class AssignedVoiceAudit extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit;

    protected $guarded = [];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'assigned_team_lead_id');
    }
    public function voice_audit()
    {
        return $this->hasOne(VoiceAudit::class, 'id', 'voice_audit_id');
    }
}
