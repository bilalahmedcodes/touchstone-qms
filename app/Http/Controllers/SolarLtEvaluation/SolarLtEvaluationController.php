<?php

namespace App\Http\Controllers\SolarLtEvaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolarLtCategory;
use App\Models\SolarLtDatapoint;
class SolarLtEvaluationController extends Controller
{

    public function index()
    {
        $categories = SolarLtCategory::all();
        
        return view('solar-lts.voice-evaluations.index', (compact('categories')));
    }
   
    
}
