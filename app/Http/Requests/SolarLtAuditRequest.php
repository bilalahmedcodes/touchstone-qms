<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolarLtAuditRequest extends FormRequest
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
            'record_id' => 'required|numeric',
            'user_id' => 'required',
            'customer_name' => 'required',
            'customer_phone' => 'required|regex:/^[]{0,1}[0-9]{1,4}[]{0,1}[\s\.\0-9]*$/',
            'recording_duration' => 'required',
            'recording_link' => 'required',
            'outcome' => 'required',
            'notes' => 'required',
        ];
        return $rules;
    }
}
