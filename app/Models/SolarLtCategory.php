<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarLtCategory extends Model
{
    use HasFactory;
    protected $table = 'solar_lt_categories';
    protected $fillable = [
       'id', 'name',
    ];
    public function datapoints()
    {
        return $this->hasMany(SolarLtDatapoint::class, 'category_id');
    }
}
