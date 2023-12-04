<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;

class VoiceAuditPoint extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_audit_id',
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
        return $this->hasOne(VoiceAudit::class, 'id', 'voice_audit_id');
    }
}
