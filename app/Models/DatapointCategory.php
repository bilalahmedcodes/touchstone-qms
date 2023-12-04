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

class DatapointCategory extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_evaluation_id',
        'campaign_id',
        'project_id',
        'name',
        'sort',
        'status',
    ];

    public $sortable = [
        'id',
        'name',
        'sort',
        'status',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        DatapointCategory::creating(function($model) {
            $model->added_by = Auth::user()->id ?? 1;
        });
    }

    public function datapoints()
    {
        return $this->hasMany(Datapoint::class, 'datapoint_category_id', 'id');
    }

    /**
     * Get all of the comments for the Datapoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluation()
    {
        return $this->belongsTo(VoiceEvaluation::class, 'voice_evaluation_id');
    }

    /**
     * The campaigns that belong to the categories.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
