<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceAuditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'voice_evaluation_id' => 'required',
            'associate_id' => 'required',
            'record_id' => 'required',
            'call_date' => 'required',
            'percentage' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'recording_duration' => 'required',
            'outcome' => 'required',
            'notes' => 'required'
        ];

        return $rules;
    }
}
