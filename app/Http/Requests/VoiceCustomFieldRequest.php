<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceCustomFieldRequest extends FormRequest
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
            'voice_evaluation_id' => 'required|numeric',
            'label' => 'required',
            'type' => 'required',
            'position' => 'required',
            'required' => 'required'
        ];

        return $rules;
    }
}
