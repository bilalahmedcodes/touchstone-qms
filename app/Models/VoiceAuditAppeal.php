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

class VoiceAuditAppeal extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_audit_id',
        'user_id',
        'campaign_id',
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

        VoiceAuditAppeal::creating(function($model) {
            $model->user_id = Auth::user()->hrms_id ?? request()->user_id;
            $model->campaign_id = Auth::user()->campaign_id ?? request()->campaign_id;
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

    public function user()
    {
        return $this->hasOne(User::class, 'hrms_id', 'user_id');
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
}
