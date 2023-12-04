<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;

class VoiceCustomField extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'voice_evaluation_id',
        'label',
        'placeholder',
        'type',
        'status',
        'position',
        'options',
        'required'
    ];

    public function evaluation()
    {
        return $this->hasOne(VoiceEvaluation::class, 'id', 'voice_evaluation_id');
    }

    /**
     * The campaigns that belong to the categories.
     */
    public function campaigns()
    {
        return $this->belongsToMany('App\Models\Campaign');
    }
}
