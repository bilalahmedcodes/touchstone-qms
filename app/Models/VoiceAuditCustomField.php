<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
use \Znck\Eloquent\Traits\BelongsToThrough;

class VoiceAuditCustomField extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit;

    protected $fillable = [
        'voice_custom_field_id',
        'voice_audit_id',
        'answer'
    ];

    public function audit()
    {
        return $this->hasOne(VoiceAudit::class, 'id', 'voice_audit_id');
    }

    public function field()
    {
        return $this->hasOne(VoiceCustomField::class, 'id', 'voice_custom_field_id');
    }
}
