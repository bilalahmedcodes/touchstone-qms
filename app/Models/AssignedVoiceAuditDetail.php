<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignedVoiceAuditDetail extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit;

    protected $fillable = ['evaluation_time', 'status', 'review_priority', 'notes', 'outcome', 'recording_link', 'recording_duration', 'customer_phone', 'customer_name', 'percentage', 'call_date', 'project_id', 'campaign_id', 'team_lead_id', 'associate_id', 'voice_evaluation_id', 'assigned_team_lead_id', 'voice_audit_id', 'assigned_voice_audit_id'];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'assigned_team_lead_id');
    }
    public function associate()
    {
        return $this->hasOne(User::class, 'hrms_id', 'associate_id');
    }
    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }
    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function voice_audit()
    {
        return $this->hasOne(VoiceAudit::class,'id', 'voice_audit_id');
    }
    public function scopeSearch($query, $request)
    {
        if ($request->has('assigned_team_lead_id')) {
            if ($request->assigned_team_lead_id > 0) {
                $query = $query->where('assigned_team_lead_id', $request->assigned_team_lead_id);
            }
        }

        if ($request->has('associate_id')) {
            if ($request->associate_id > 0) {
                $query = $query->where('associate_id', $request->associate_id);
            }
        }

        if ($request->has('campaign_id')) {
            if ($request->campaign_id > 0) {
                $query = $query->where('campaign_id', $request->campaign_id);
            }
        }

        if ($request->has('project_id')) {
            if ($request->project_id > 0) {
                $query = $query->where('project_id', $request->project_id);
            }
        }

        if ($request->has('outcome')) {
            if (!empty($request->outcome)) {
                $query = $query->where('outcome', $request->outcome);
            }
        }
        if ($request->has('from_date')) {
            if (!empty($request->from_date) && !empty($request->to_date)) {
                // date
                $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date);
                $to_date = Carbon::createFromFormat('d/m/Y', $request->to_date);
                // from time
                if (!empty($request->from_time)) {
                    $from_time = Carbon::createFromFormat('g:i:s A', $request->from_time);
                    $from_date->hour = $from_time->format('H');
                    $from_date->minute = $from_time->format('i');
                } else {
                    $from_date->startOfDay();
                }
                // to time
                if (!empty($request->to_time)) {
                    $to_time = Carbon::createFromFormat('g:i:s A', $request->to_time);
                    $to_date->hour = $to_time->format('H');
                    $to_date->minute = $to_time->format('i');
                } else {
                    $to_date->startOfDay();
                }
                $query = $query->whereDate('created_at', '>=', $from_date->toDateTimeString());
                $query = $query->whereDate('created_at', '<=', $to_date->toDateTimeString());
            } elseif (!empty($request->from_date)) {
                $from_date = Carbon::createFromFormat('d/m/Y', $request->from_date);
                $query = $query->whereDate('created_at', $from_date->toDateTimeString());
            }
        }

        return $query;
    }
}
