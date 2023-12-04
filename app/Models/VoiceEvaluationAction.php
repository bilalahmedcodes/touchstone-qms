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

class VoiceEvaluationAction extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'name',
        'status',
        'sort',
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

        VoiceEvaluationAction::creating(function($model) {
            $model->added_by = Auth::user()->id ?? '1';
        });
    }

    public function scopeSearch($query, $request){

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
}
