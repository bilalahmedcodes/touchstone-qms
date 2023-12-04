<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Models\VoiceEvaluation;

use App\Models\DatapointCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceEvaluationRequest;
use App\Models\VoiceCustomField;

class VoiceEvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $voice_evaluations = VoiceEvaluation::when($request, function ($query, $request) {
            $query->search($request);
        })->sortable()->orderBy('id', 'desc')->paginate(15);

        return view('voice-evaluations.index')->with(compact('voice_evaluations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('voice-evaluations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoiceEvaluationRequest $request)
    {
        $voice_evaluation = VoiceEvaluation::create($request->all());
        return redirect()->route('voice-evaluations.show', $voice_evaluation)->with('success', 'Voice Evaluation created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceEvaluation $voice_evaluation)
    {
        $categories = DatapointCategory::where('voice_evaluation_id', $voice_evaluation->id)->where('status', 'active')->sortable(['sort', 'desc'])->with('datapoints')->paginate(15);
        return view('voice-evaluations.show')->with(compact('voice_evaluation', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceEvaluation $voice_evaluation)
    {
        return view('voice-evaluations.edit')->with(compact('voice_evaluation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoiceEvaluationRequest $request, VoiceEvaluation $voice_evaluation)
    {
        $voice_evaluation->update($request->all());
        return redirect()->route('voice-evaluations.index')->with('success', 'Voice Evaluation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceEvaluation $voice_evaluation)
    {
        $voice_evaluation->delete();
        return redirect()->back()->with('success', 'Voice Evaluation deleted successfully!');
    }
}
