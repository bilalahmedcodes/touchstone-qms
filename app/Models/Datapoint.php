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

class Datapoint extends Model
{

    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_evaluation_id',
        'datapoint_category_id',
        'project_id',
        'campaign_id',
        'score',
        'name',
        'question',
        'sort',
    ];

    public $sortable = [
        'id',
        'name',
        'sort',
        'course_id',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        Datapoint::creating(function($model) {
            $model->added_by = Auth::user()->id ?? 1;
        });
    }

    /**
     * Get all of the comments for the Datapoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category()
    {
        return $this->belongsTo(DatapointCategory::class, 'datapoint_category_id');
    }

    /**
     * Get all of the comments for the Datapoint
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluation()
    {
        return $this->BelongsToThrough(VoiceEvaluation::class, DatapointCategory::class);
    }


}
