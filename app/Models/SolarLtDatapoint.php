<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarLtDatapoint extends Model
{
    use HasFactory;
    protected $table = 'solar_lt_datapoints';
    protected $fillable = [
        'id','name','question','category_id'
     ];
     public function category()
     {
         return $this->hasOne(SolarLtCategory::class, 'id', 'category_id');
     }
}
