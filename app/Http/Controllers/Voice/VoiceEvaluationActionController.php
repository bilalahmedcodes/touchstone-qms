<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VoiceEvaluationAction;
use App\Http\Requests\VocieEvaluationActionRequest;

class VoiceEvaluationActionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $voice_evaluation_actions = VoiceEvaluationAction::when($request, function ($query, $request) {
            $query->search($request);
        })->sortable()->orderBy('sort', 'asc')->paginate(15);

        return view('voice-evaluation-actions.index')->with(compact('voice_evaluation_actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('voice-evaluation-actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VocieEvaluationActionRequest $request)
    {
        VoiceEvaluationAction::create($request->all());
        return redirect()->route('voice-evaluation-actions.index')->with('success', 'Evaluation Action created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceEvaluationAction $voice_evaluation_action)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceEvaluationAction $voice_evaluation_action)
    {
        return view('voice-evaluation-actions.edit')->with(compact('voice_evaluation_action'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VocieEvaluationActionRequest $request, VoiceEvaluationAction $voice_evaluation_action)
    {
        $voice_evaluation_action->update($request->all());
        return redirect()->route('voice-evaluation-actions.index')->with('success', 'Evaluation Action updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceEvaluationAction $voice_evaluation_action)
    {
        $voice_evaluation_action->delete();
        return redirect()->back()->with('success', 'Evaluation Action deleted successfully!');
    }

}
