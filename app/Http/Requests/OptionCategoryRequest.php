<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OptionCategoryRequest extends FormRequest
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
            'non_voice_evaluation_id' => 'required|numeric',
            'name' => 'required',
            'sort' => 'required|numeric'
        ];

        return $rules;
    }
}
