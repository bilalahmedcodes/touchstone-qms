<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
use \Znck\Eloquent\Traits\BelongsToThrough;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Project extends Model
{
    use HasFactory, SoftDeletes, HasRelationships, BelongsToThrough, HasEagerLimit;

    protected $fillable = [];
}
