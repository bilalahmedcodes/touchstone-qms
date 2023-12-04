<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'name',
        'table_name',
        'status',
    ];

    public $sortable = [
        'id',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];

    /* protected static function boot()
    {
        parent::boot();

        Campaign::creating(function($model) {
            $model->added_by = Auth::user()->id ?? '1';
        });
    } */

    public function scopeSearch($query, $request){
        if ($request->has('campaign_id')) {
            if ($request->campaign_id > 0) {
                $query = $query->where('id', $request->campaign_id);
            }
        }

        if ($request->has('name')) {
            if (!empty($request->name)) {
                $query = $query->where('name', 'LIKE', "%{$request->name}%");
            }
        }

        if ($request->has('status')) {
            if (!empty($request->status)) {
                $query = $query->where('status', $request->status);
            }
        }


        return $query;
    }

    /**
     * The datapoint categories that belong to the campaigns.
     */
    public function datapointCategories()
    {
        return $this->belongsToMany('App\Models\DatapointCategory');
    }

    /**
     * The datapoint categories that belong to the campaigns.
     */
    public function voiceCustomFields()
    {
        return $this->belongsToMany('App\Models\VoiceCustomField');
    }


    /**
     * Get all of the voiceAudits for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voiceAudits()
    {
        return $this->hasMany(VoiceAudit::class, 'campaign_id', 'id')->where('voice_evaluation_id', 1);
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'campaign_id', 'id');
    }

}
