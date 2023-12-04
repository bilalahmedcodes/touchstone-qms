<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use \Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoiceAuditAction extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_audit_id',
        'user_id',
        'voice_evaluation_action_id',
        'remarks',
        'status',
    ];

    public $sortable = [
        'id',
        'voice_audit_id',
        'user_id',
        'remarks',
        'status',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        VoiceAuditAction::creating(function($model) {
            $model->user_id = Auth::user()->hrms_id ?? 1;
        });
    }

    public function scopeSearch($query, $request){

        if ($request->has('status')) {
            if (!empty($request->status)) {
                $query = $query->where('status', $request->status);
            }
        }

        return $query;
    }

    /**
     * Get all of the comments for the Datapoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function audit()
    {
        return $this->belongsTo(VoiceAudit::class, 'voice_audit_id');
    }

    /**
     * Get all of the comments for the Datapoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function action()
    {
        return $this->belongsTo(VoiceEvaluationAction::class, 'voice_evaluation_action_id');
    }
}
