<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarLtEvaluationResponse extends Model
{
    use HasFactory;
    protected $table = 'solar_lt_evaluation_responses';
    public function evaluation_response()
    {
        return $this->hasMany(SolarLtAudit::class, 'id', 'evaluation_id');
    }

    /**
     * Get the user associated with the EvaluationResponse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function datapoint()
    {
        return $this->hasOne(SolarLtDatapoint::class, 'id', 'datapoint_id');
    }
}
