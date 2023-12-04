<?php

namespace App\Http\Controllers\Voice;

use App\Models\Campaign;
use App\Models\Datapoint;

use Illuminate\Http\Request;
use App\Models\DatapointCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataPointRequest;

class DataPointController extends Controller
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
    public function create(DatapointCategory $datapoint_category)
    {
        $data['apiKey'] = 'L8qq9PZyRg6ieKGEKhZolGC0vJWLw8iEJ88DRdyOg';
        $queryString = http_build_query($data);
        $url = 'https://crm.touchstone-communications.com/api/campaigns?' . $queryString; //url of 2nd website where data is to be send+
        $getdata = $data;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $getdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $campaigns = curl_exec($ch);
        curl_close($ch);
        $campaigns = json_decode($campaigns);
        return view('datapoints.create')->with(compact('datapoint_category', 'campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DataPointRequest $request)
    {
        $datapoint = Datapoint::create($request->all());
        return redirect()
            ->route('voice-evaluations.show', $datapoint->evaluation)
            ->with('success', 'Data Point created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Datapoint $datapoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Datapoint $datapoint)
    {
        return view('datapoints.edit')->with(compact('datapoint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DataPointRequest $request, Datapoint $datapoint)
    {
        $datapoint->update($request->all());
        return redirect()
            ->route('voice-evaluations.show', $datapoint->evaluation)
            ->with('success', 'Data Point updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Datapoint $datapoint)
    {
        $datapoint->delete();
        return redirect()
            ->back()
            ->with('success', 'Data Point deleted successfully!');
    }
}
