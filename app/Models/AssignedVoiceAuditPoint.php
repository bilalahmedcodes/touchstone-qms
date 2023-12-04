<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
use \Znck\Eloquent\Traits\BelongsToThrough;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class AssignedVoiceAuditPoint extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit;
    protected $fillable = [
        'voice_audit_id',
        'assigned_voice_audit_id',
        'assigned_voice_audit_detail_id',
        'datapoint_category_id',
        'datapoint_id',
        'answer'
    ];
    public function datapoint()
    {
        return $this->hasOne(Datapoint::class, 'id', 'datapoint_id');
    }

    public function category()
    {
        return $this->hasOne(DatapointCategory::class, 'id', 'datapoint_category_id');
    }

    public function audit()
    {
        return $this->hasOne(AssignedVoiceAuditDetail::class, 'id', 'voice_audit_id');
    }
}
