<?php

namespace App\Http\Controllers\SolarLtEvaluation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SolarLtDatapointRequest;
use App\Models\SolarLtDatapoint;
use App\Models\SolarLtCategory;
use Illuminate\Support\Facades\Session;
class SolarLtDatapointController extends Controller
{

    public function create(SolarLtCategory $category)
    {     
        return view('solar-lts.voice-evaluations.datapoints.create', compact('category'));
    }
    public function store(SolarLtDatapointRequest $request, SolarLtDatapoint $datapoint)
    {
        $datapoint->create($request->all());
        Session::flash('success', 'Datapoint added successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }
    public function edit(SolarLtDatapoint $datapoint)
    {
        return view('solar-lts.voice-evaluations.datapoints.edit', compact('datapoint'));
    }
    public function update(SolarLtDatapointRequest $request, SolarLtDatapoint $datapoint)
    {
        $datapoint->update($request->all());
        Session::flash('success', 'Datapoint updated successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }
    public function destroy(SolarLtDatapoint $datapoint)
    {
        $datapoint->delete();
        Session::flash('success', 'Datapoint Deleted successfully!');
        return redirect()->route('solar-lts.voice-evaluations.index');
    }
}
