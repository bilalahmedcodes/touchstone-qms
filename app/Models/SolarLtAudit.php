<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class SolarLtAudit extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'outcome',
        'notes',
        'customer_name',
        'customer_phone',
        'record_id',
        'recording_link',
        'recording_duration',
        'user_id',
    ];
    protected $table = 'solar_lt_evaluations';
    public $sortable = ['user_id','campaign_id','recording_duration'];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
