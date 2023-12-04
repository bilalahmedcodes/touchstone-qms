<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceEvaluationRequest extends FormRequest
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
            'name' => 'required|unique:voice_evaluations',
            'status' => 'required|string'
        ];

        if ($this->getMethod() == 'PUT') {
            $rules['name']= 'required|unique:voice_evaluations,name,'.$this->voice_evaluation->id;
        }

        return $rules;
    }
}
