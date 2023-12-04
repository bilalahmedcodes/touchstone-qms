<?php

namespace App\Http\Controllers\Voice;

use App\Models\Campaign;
use App\Models\VoiceEvaluation;
use App\Models\VoiceCustomField;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoiceCustomFieldRequest;

class VoiceCustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(VoiceEvaluation $voice_evaluation)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('voice-custom-fields.create')->with(compact('voice_evaluation', 'campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoiceCustomFieldRequest $request)
    {
        $voice_custom_field = VoiceCustomField::create($request->except('campaigns'));

        $voice_custom_field->campaigns()->sync($request->campaigns);

        return redirect()->route('voice-evaluations.show', $voice_custom_field->evaluation)->with('success', 'Custom Field created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceCustomField $voice_custom_field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceCustomField $voice_custom_field)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('voice-custom-fields.edit')->with(compact('voice_custom_field', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoiceCustomFieldRequest $request, VoiceCustomField $voice_custom_field)
    {
        $voice_custom_field->update($request->except('campaigns'));

        $voice_custom_field->campaigns()->sync($request->campaigns);

        return redirect()->route('voice-evaluations.show', $voice_custom_field->evaluation)->with('success', 'Custom Field updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceCustomField $voice_custom_field)
    {
        $voice_custom_field->delete();
        return redirect()->back()->with('success', 'Custom Field deleted successfully!');
    }

}
