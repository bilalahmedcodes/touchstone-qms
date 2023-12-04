<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasEagerLimit;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;

class Designation extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit, Sortable;

    protected $fillable = [
        'name',
        'status',
    ];

    public $sortable = [
        'id',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
}
