<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SolarLtCategoryRequest extends FormRequest
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
            'name' => 'required|unique:solar_lt_categories',
        ];
        if ($this->getMethod() == "PUT") {
            $rules['name'] = 'required|unique:solar_lt_categories,name,' . $this->category->id;
        }
        return $rules;
    }
}
